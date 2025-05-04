<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../Models/apprenant.model.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Models/ref.model.php';
require_once __DIR__ . '/../Services/session.service.php';
require_once __DIR__ . '/../Services/validator.service.php';
require_once __DIR__ . '/../Services/mail.service.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function handle_request_apprenant()
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $action = $_GET['action'] ?? 'liste-apprenant';

    try {
        switch ($action) {
            case 'liste-apprenant':
                handleListeApprenant();
                break;

            case 'detail':
                $apprenantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
                if (!$apprenantId) {
                    throw new Exception("ID de l'apprenant manquant");
                }

                $apprenant = get_apprenant_by_id($apprenantId);
                if (!$apprenant) {
                    throw new Exception("Apprenant non trouvé");
                }

                require_once __DIR__ . '/../Views/apprenants/detail.apprenant.php';
                break;

            case 'ad-apprenant':
                handleAdApprenant();
                break;

            case 'save-apprenant':
                handleSaveApprenant();
                break;

            case 'dashboard':
                require_once __DIR__ . '/../Views/dashboard/dashboard.apprenant.php';
                break;

            case 'liste-apprenant-par-referentiel':
                handleListeApprenantParReferentiel();
                break;

            case 'upload-excel':
                if (isset($_GET['download_template'])) {
                    generate_example_excel();
                    exit;
                }
                handle_upload_excel();
                break;

            case 'inscription-groupee':
                handleInscriptionGroupee();
                break;

            default:
                header('Location: ?page=apprenant&action=liste-apprenant');
                exit;
        }
    } catch (Exception $e) {
        echo 'Erreur : ' . htmlspecialchars($e->getMessage());
    }
}

function handleListeApprenant()
{
    $activePromo = getActivePromotion();
    $apprenants = [];
    $totalApprenants = $totalPages = $startItem = $endItem = 0;
    $search = $_GET['search'] ?? '';
    $referentiel = $_GET['referentiel'] ?? '';
    $status = $_GET['status'] ?? '';
    $page = max(1, (int) ($_GET['p'] ?? 1));
    $perPage = (int) ($_GET['per_page'] ?? 5);

    if ($activePromo) {
        $filters = compact('search', 'referentiel', 'status');
        $filters['promotion_id'] = $activePromo['id'];
        $result = get_paginated_apprenants($filters, $page, $perPage);

        $apprenants = $result['data'];
        $totalApprenants = $result['total'];
        $totalPages = $result['total_pages'];
        $startItem = ($page - 1) * $perPage + 1;
        $endItem = min($page * $perPage, $totalApprenants);
    }

    $viewData = compact(
        'apprenants',
        'startItem',
        'endItem',
        'totalApprenants',
        'totalPages',
        'page',
        'perPage',
        'search',
        'referentiel',
        'status'
    );

    extract($viewData);
    require_once __DIR__ . '/../Views/apprenants/liste.apprenant.view.php';
}

function handleAdApprenant()
{
    $activePromo = getActivePromotion();

    if (!$activePromo) {
        $_SESSION['flash_message'] = "Aucune promotion active sélectionnée";
        header('Location: ?page=promotions');
        exit;
    }

    $referentiels = get_referentiels_by_promo_id($activePromo['referentiels']);

    if (empty($referentiels)) {
        $_SESSION['flash_message'] = "Aucun référentiel associé à la promotion active";
        header('Location: ?page=referentiel&action=ad-ref');
        exit;
    }

    $search = $_GET['search'] ?? '';
    $referentiel = $_GET['referentiel'] ?? '';
    $status = $_GET['status'] ?? '';
    $page = max(1, (int) ($_GET['p'] ?? 1));
    $perPage = (int) ($_GET['per_page'] ?? 5);

    $filters = compact('search', 'referentiel', 'status');
    $result = get_paginated_apprenants($filters, $page, $perPage);

    $startItem = ($page - 1) * $perPage + 1;
    $endItem = min($page * $perPage, $result['total']);
    $totalApprenants = $result['total'];
    $totalPages = $result['total_pages'];

    $viewData = [
        'apprenants' => $result['data'],
        'startItem' => $startItem,
        'endItem' => $endItem,
        'totalApprenants' => $totalApprenants,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'perPage' => $perPage,
        'search' => $search,
        'referentiel' => $referentiel,
        'status' => $status
    ];

    require_once __DIR__ . '/../Views/apprenants/inscrire.apprenant.php';
}

function handleSaveApprenant()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ?page=apprenant&action=ad-apprenant');
        exit;
    }

    $activePromo = getActivePromotion();

    if (!$activePromo) {
        $_SESSION['flash_message'] = "Aucune promotion active sélectionnée";
        header('Location: ?page=promotions');
        exit;
    }

    $errors = validate_apprenant_data($_POST);

    if (!$errors['isValid']) {
        $_SESSION['form_errors'] = $errors['errors'];
        $_SESSION['form_data'] = $_POST;
        header('Location: ?page=apprenant&action=ad-apprenant');
        exit;
    }

    $documents = upload_documents($_FILES);

    $apprenant = [
        'prenom' => htmlspecialchars($_POST['prenom']),
        'nom' => htmlspecialchars($_POST['nom']),
        'date_naissance' => htmlspecialchars($_POST['dateNaissance']),
        'lieu_naissance' => htmlspecialchars($_POST['lieuNaissance']),
        'adresse' => htmlspecialchars($_POST['adresse']),
        'email' => htmlspecialchars($_POST['email']),
        'telephone' => htmlspecialchars($_POST['telephone']),
        'documents' => $documents,
        'status' => 'active',
        'tuteur' => [
            'nom' => htmlspecialchars($_POST['tuteurNom']),
            'lien_parente' => htmlspecialchars($_POST['parente']),
            'adresse' => htmlspecialchars($_POST['tuteurAdresse']),
            'telephone' => htmlspecialchars($_POST['tuteurTelephone'])
        ],
        'promotion_id' => $activePromo['id'],
        'referentiel_id' => htmlspecialchars($_POST['referentiel_id'])
    ];

    if (save_apprenant($apprenant)) {
        $_SESSION['flash_message'] = "Apprenant ajouté avec succès";
    } else {
        $_SESSION['flash_message'] = "Erreur lors de l'enregistrement de l'apprenant";
        $_SESSION['form_data'] = $_POST;
    }

    header('Location: ?page=apprenant&action=liste-apprenant');
    exit;
}

function handleListeApprenantParReferentiel()
{
    $referentiel_id = $_GET['referentiel_id'] ?? null;

    if (!$referentiel_id) {
        $_SESSION['flash_message'] = "ID de référentiel manquant";
        header('Location: ?page=referentiel&action=liste-ref');
        exit;
    }

    $apprenants = get_apprenants_by_referentiel($referentiel_id);
    // require_once __DIR__ . '/../Views/apprenants/liste.apprenant.par.referentiel.view.php';
}

function validate_apprenant_form($post_data, $files_data)
{
    $errors = [];

    $required_fields = [
        'prenom' => 'Le prénom est obligatoire',
        'nom' => 'Le nom est obligatoire',
        'dateNaissance' => 'La date de naissance est obligatoire',
        'lieuNaissance' => 'Le lieu de naissance est obligatoire',
        'adresse' => 'L\'adresse est obligatoire',
        'email' => 'L\'email est obligatoire',
        'telephone' => 'Le téléphone est obligatoire',
        'tuteurNom' => 'Le nom du tuteur est obligatoire',
        'parente' => 'Le lien de parenté est obligatoire',
        'tuteurAdresse' => 'L\'adresse du tuteur est obligatoire',
        'tuteurTelephone' => 'Le téléphone du tuteur est obligatoire',
        'referentiel_id' => 'Veuillez sélectionner un référentiel'
    ];

    foreach ($required_fields as $field => $message) {
        if (empty($post_data[$field])) {
            $errors[$field] = $message;
        }
    }

    if (!empty($post_data['email']) && !filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }

    $phone_pattern = '/^[0-9\+\-\s\(\)]{8,15}$/';
    if (!empty($post_data['telephone'])) {
        $cleaned_phone = preg_replace('/[^0-9]/', '', $post_data['telephone']);
        if (strlen($cleaned_phone) < 8) {
            $errors['telephone'] = 'Numéro de téléphone trop court (min 8 chiffres)';
        } elseif (!preg_match($phone_pattern, $post_data['telephone'])) {
            $errors['telephone'] = 'Format de téléphone invalide';
        }
    }

    if (!empty($post_data['dateNaissance'])) {
        $date = DateTime::createFromFormat('Y-m-d', $post_data['dateNaissance']);
        if (!$date || $date->format('Y-m-d') !== $post_data['dateNaissance']) {
            $errors['dateNaissance'] = 'Format de date invalide (YYYY-MM-DD requis)';
        }
    }

    return $errors;
}

function handleInscriptionGroupee()
{
    $activePromo = getActivePromotion();

    if (!$activePromo) {
        $_SESSION['flash_message'] = "Aucune promotion active sélectionnée";
        header('Location: ?page=promotions');
        exit;
    }

    require_once __DIR__ . '/../Views/apprenants/inscription.groupe.php';
}

function handle_upload_excel()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_csv'])) {
        $file = $_FILES['import_csv']['tmp_name'];
        if (!is_uploaded_file($file)) {
            $_SESSION['flash_message'] = "Fichier invalide";
            header('Location: ?page=apprenant&action=inscription-groupee');
            exit;
        }

        $activePromo = getActivePromotion();
        if (!$activePromo) {
            $_SESSION['flash_message'] = "Aucune promotion active sélectionnée";
            header('Location: ?page=promotions');
            exit;
        }

        $result = import_apprenants_from_excel($file, $activePromo['id']);

        if ($result['success']) {
            $_SESSION['flash_message'] = "Importation réussie. " . $result['imported'] . " apprenants importés.";
            if (!empty($result['errors'])) {
                $_SESSION['flash_message'] .= " Erreurs rencontrées : " . implode(', ', $result['errors']);
            }
        } else {
            $_SESSION['flash_message'] = "Échec de l'importation : " . $result['message'];
        }

        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    } else {
        $_SESSION['flash_message'] = "Aucun fichier envoyé";
        header('Location: ?page=apprenant&action=inscription-groupee');
        exit;
    }
}

function generate_example_excel()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    $headers = [
        'prenom',
        'nom',
        'dateNaissance',
        'lieuNaissance',
        'adresse',
        'email',
        'telephone',
        'tuteurNom',
        'parente',
        'tuteurAdresse',
        'tuteurTelephone',
        'referentiel'
    ];


    $sheet->fromArray($headers, null, 'A1');


    $exampleData = [

    ];

    foreach ($exampleData as $rowIndex => $row) {
        $sheet->fromArray($row, null, 'A' . ($rowIndex + 2));
    }


    foreach (range('A', 'L') as $columnID) {
        $sheet->getColumnDimension($columnID)->setWidth(20);
    }


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="exemple_apprenants.xlsx"');
    header('Cache-Control: max-age=0');


    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}