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
// require_once '../../vendor/autoload.php';
// require_once '../Views/apprenants/pdf.php';

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
                handleDetailApprenant();
                break;

            case 'ad-apprenant':
                handleAdApprenant();
                break;

            case 'save-apprenant':
                handleSaveApprenant();
                break;

            case 'dashboard':
                handleDashboard();
                break;


            case 'liste-apprenant-par-referentiel':
                handleListeApprenantParReferentiel();
                break;

            case 'upload-excel':
                if (isset($_GET['download_template'])) {
                    download_csv_template();
                    exit;
                }
                handle_upload_excel();
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

function handleDetailApprenant()
{
    $apprenantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
    if (!$apprenantId) {
        setSessionMessage('flash_message', "ID de l'apprenant manquant");
        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    }

    $apprenant = get_apprenant_by_id($apprenantId);
    if (!$apprenant) {
        setSessionMessage('flash_message', "Apprenant non trouvé");
        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    }


    $referentiel = get_referentiel_by_id($apprenant['referentiel_id']);
    $promotion = get_promotion_by_id($apprenant['promotion_id']);

    require_once __DIR__ . '/../Views/apprenants/detail.apprenant.php';
}

function handleAdApprenant()
{
    $activePromo = getActivePromotion();

    if (!$activePromo) {
        setSessionMessage('flash_message', "Aucune promotion active sélectionnée");
        header('Location: ?page=promotions');
        exit;
    }

    $referentiels = get_referentiels_by_promo_id($activePromo['referentiels']);

    if (empty($referentiels)) {
        setSessionMessage('flash_message', "Aucun référentiel associé à la promotion active");
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
        setSessionMessage('flash_message', "Aucune promotion active sélectionnée");
        header('Location: ?page=promotions');
        exit;
    }

    $errors = validate_apprenant_data($_POST);

    if (!$errors['isValid']) {
        setSessionMessage('form_errors', $errors['errors']);
        setFormData($_POST);
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
        setSessionMessage('flash_message', "Apprenant ajouté avec succès");
    } else {
        setSessionMessage('flash_message', "Erreur lors de l'enregistrement de l'apprenant");
        setFormData($_POST);
    }

    header('Location: ?page=apprenant&action=liste-apprenant');
    exit;
}

function handleDashboard()
{
    $apprenantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
    if (!$apprenantId) {
        setSessionMessage('flash_message', "ID de l'apprenant manquant");
        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    }

    $apprenant = get_apprenant_by_id($apprenantId);
    if (!$apprenant) {
        setSessionMessage('flash_message', "Apprenant non trouvé");
        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    }


    $referentiel = get_referentiel_by_id($apprenant['referentiel_id']);
    $promotion = get_promotion_by_id($apprenant['promotion_id']);

    require_once __DIR__ . '/../Views/dashboard/dashboard.apprenant.php';
}

function handleListeApprenantParReferentiel()
{
    $referentiel_id = $_GET['referentiel_id'] ?? null;

    if (!$referentiel_id) {
        setSessionMessage('flash_message', "ID de référentiel manquant");
        header('Location: ?page=referentiel&action=liste-ref');
        exit;
    }

    $apprenants = get_apprenants_by_referentiel($referentiel_id);
    // require_once __DIR__ . '/../Views/apprenants/liste.apprenant.par.referentiel.view.php';
}

function handle_upload_excel()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['import_csv'])) {
        $file = $_FILES['import_csv']['tmp_name'];
        if (!is_uploaded_file($file)) {
            setSessionMessage('flash_message', "Fichier invalide");
            header('Location: ?page=apprenant&action=ad-apprenant');
            exit;
        }

        $activePromo = getActivePromotion();
        if (!$activePromo) {
            setSessionMessage('flash_message', "Aucune promotion active sélectionnée");
            header('Location: ?page=promotions');
            exit;
        }

        $referentiel_id = $_POST['referentiel_id'] ?? null;
        if (!$referentiel_id) {
            setSessionMessage('flash_message', "Veuillez sélectionner un référentiel");
            header('Location: ?page=apprenant&action=ad-apprenant');
            exit;
        }

        $result = import_apprenants_from_csv($file, $activePromo['id'], $referentiel_id);

        if ($result['success']) {
            setSessionMessage('flash_message', "Importation réussie. " . $result['imported'] . " apprenants importés.");
            if (!empty($result['errors'])) {
                setSessionMessage('flash_message', getSessionMessage('flash_message') . " Erreurs rencontrées : " . implode(', ', $result['errors']));
            }
        } else {
            setSessionMessage('flash_message', "Échec de l'importation : " . $result['message']);
        }

        header('Location: ?page=apprenant&action=liste-apprenant');
        exit;
    } else {
        setSessionMessage('flash_message', "Aucun fichier envoyé");
        header('Location: ?page=apprenant&action=ad-apprenant');
        exit;
    }
}

function download_csv_template()
{
    $filename = "modele_apprenant.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $out = fopen('php://output', 'w');

    if (!$out) {
        error_log("Erreur lors de l'ouverture du flux de sortie pour le fichier CSV.");
        return;
    }

    $headers = [
        'prenom',
        'nom',
        'date_naissance',
        'lieu_naissance',
        'adresse',
        'email',
        'telephone',
        'tuteur_nom',
        'tuteur_parente',
        'tuteur_adresse',
        'tuteur_telephone'
    ];

    fputcsv($out, $headers, ';');

    $exampleData = [
        'Jean',
        'Dupont',
        '2000-01-15',
        'Paris',
        '12 Rue des Exemples',
        'jean.dupont@example.com',
        '771234567',
        'Marie Dupont',
        'Mère',
        '12 Rue des Exemples',
        '771234568'
    ];

    fputcsv($out, $exampleData, ';');

    fclose($out);
}