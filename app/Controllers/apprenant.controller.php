<?php
require_once __DIR__ . '/../utils/utils.php';



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
                    throw new Exception(ApprenantTexts::MISSING_ID->value);
                }

                $apprenant = get_apprenant_by_id($apprenantId);
                if (!$apprenant) {
                    throw new Exception(ApprenantTexts::LEARNER_NOT_FOUND->value);
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
                $apprenantId = isset($_GET['id']) ? (int) $_GET['id'] : null;
                if (!$apprenantId) {
                    throw new Exception(ApprenantTexts::MISSING_ID->value);
                }

                $apprenant = get_apprenant_by_id($apprenantId);
                if (!$apprenant) {
                    throw new Exception(ApprenantTexts::LEARNER_NOT_FOUND->value);
                }
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

            case 'download_pdf':
                $apprenants = get_all_apprenant();
                generateApprenantsPDF($apprenants);
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
        setSessionMessage('flash_message', ApprenantTexts::NO_ACTIVE_PROMOTION->value);
        header('Location: ?page=promotions');
        exit;
    }

    $referentiels = get_referentiels_by_promo_id($activePromo['referentiels']);

    if (empty($referentiels)) {
        setSessionMessage('flash_message', ApprenantTexts::NO_REFERENTIELS->value);
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
        setSessionMessage('flash_message', ApprenantTexts::NO_ACTIVE_PROMOTION->value);
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
        setSessionMessage('flash_message', ApprenantTexts::SUCCESS_ADD->value);
    } else {
        setSessionMessage('flash_message', ApprenantTexts::ERROR_SAVE->value);
        setFormData($_POST);
    }

    header('Location: ?page=apprenant&action=liste-apprenant');
    exit;
}

function handleListeApprenantParReferentiel()
{
    $referentiel_id = $_GET['referentiel_id'] ?? null;

    if (!$referentiel_id) {
        setSessionMessage('flash_message', ApprenantTexts::MISSING_REFERENTIEL_ID->value);
        header('Location: ?page=referentiel&action=liste-ref');
        exit;
    }

    $apprenants = get_apprenants_by_referentiel($referentiel_id);
}

function validate_apprenant_form($post_data, $files_data)
{
    $errors = [];

    $required_fields = [
        'prenom' => ApprenantTexts::FIRSTNAME_REQUIRED->value,
        'nom' => ApprenantTexts::LASTNAME_REQUIRED->value,
        'dateNaissance' => ApprenantTexts::BIRTHDATE_REQUIRED->value,
        'lieuNaissance' => ApprenantTexts::BIRTHPLACE_REQUIRED->value,
        'adresse' => ApprenantTexts::ADDRESS_REQUIRED->value,
        'email' => ApprenantTexts::EMAIL_REQUIRED->value,
        'telephone' => ApprenantTexts::PHONE_REQUIRED->value,
        'tuteurNom' => ApprenantTexts::TUTOR_NAME_REQUIRED->value,
        'parente' => ApprenantTexts::RELATIONSHIP_REQUIRED->value,
        'tuteurAdresse' => ApprenantTexts::TUTOR_ADDRESS_REQUIRED->value,
        'tuteurTelephone' => ApprenantTexts::TUTOR_PHONE_REQUIRED->value,
        'referentiel_id' => ApprenantTexts::REFERENTIEL_REQUIRED->value
    ];

    foreach ($required_fields as $field => $message) {
        if (empty($post_data[$field])) {
            $errors[$field] = $message;
        }
    }

    if (!empty($post_data['email']) && !filter_var($post_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ApprenantTexts::INVALID_EMAIL->value;
    }

    if (!empty($post_data['dateNaissance'])) {
        $date = DateTime::createFromFormat('Y-m-d', $post_data['dateNaissance']);
        if (!$date || $date->format('Y-m-d') !== $post_data['dateNaissance']) {
            $errors['dateNaissance'] = ApprenantTexts::INVALID_DATE_FORMAT->value;
        }
    }

    return $errors;
}
function handleInscriptionGroupee()
{
    $activePromo = getActivePromotion();

    if (!$activePromo) {
        setSessionMessage('flash_message', ApprenantTexts::NO_ACTIVE_PROMOTION->value);
        header('Location: ?page=promotions');
        exit;
    }

    require_once __DIR__ . '/../Views/apprenants/inscription.groupe.php';
}