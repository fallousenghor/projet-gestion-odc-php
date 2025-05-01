<?php
require_once __DIR__ . '/../Services/session.service.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Controllers/ref.controller.php';
require_once __DIR__ . '/../Controllers/controller.php';
require_once __DIR__ . '/../Services/validator.service.php';
require_once __DIR__ . '/../Models/apprenant.model.php';
function handlePromoListAction()
{
    $searchTerm = $_GET['search'] ?? '';
    $allPromotions = getAllPromotions();
    $allPromotions = searchItems($allPromotions, $searchTerm);
    $activePromo = getActivePromotion();
    $stats = calculateStatistics($activePromo, $allPromotions);


    $viewData = [
        'promotions' => $allPromotions,
        'activePromo' => $activePromo,
        'totalApprenants' => $stats['apprenants'],
        'currentPage' => $_GET['page_num'] ?? 1,
        'totalPages' => max(ceil(count($allPromotions) / 5), 1),
        'searchTerm' => $searchTerm
    ];


    extract($viewData);

    require_once __DIR__ . '/../Views/promotions/promo.liste.view.php';
}

function handleTogglePromoAction()
{
    $promoId = $_GET['id'] ?? null;

    if ($promoId) {
        $filePath = __DIR__ . '/../../public/data/data.json';
        if (file_exists($filePath)) {
            $json = file_get_contents($filePath);
            $data = json_decode($json, true);

            if (isset($data['promotions'])) {
                $promoEnCours = null;

                foreach ($data['promotions'] as &$promo) {
                    if ($promo['id'] == $promoId) {
                        if ($promo['statut'] === 'Inactive') {
                            foreach ($data['promotions'] as &$p) {
                                $p['statut'] = 'Inactive';
                            }
                            $promo['statut'] = 'Actif';
                        } else {
                            $promo['statut'] = 'Inactive';
                        }
                        $promoEnCours = $promo;
                        break;
                    }
                }

                if ($promoEnCours) {
                    $data['promotions'] = array_filter($data['promotions'], fn($p) => $p['id'] != $promoEnCours['id']);
                    if ($promoEnCours['statut'] === 'Actif') {
                        array_unshift($data['promotions'], $promoEnCours);
                    } else {
                        $data['promotions'][] = $promoEnCours;
                    }
                }

                file_put_contents($filePath, encode_json($data));
            }
        }
    }

    header('Location: ?page=promotions&action=liste-promo');
    exit;
}

function handleAddPromoAction()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData = [
            'nom' => trim($_POST['nom'] ?? ''),
            'debut' => trim($_POST['debut'] ?? ''),
            'fin' => trim($_POST['fin'] ?? ''),
            'referentiels' => $_POST['referentiels'] ?? []
        ];

        $validation = validatePromotionData($formData, $_FILES);

        if (!$validation['isValid']) {
            setSessionMessage('form_errors', $validation['errors']);
            setSessionMessage('form_data', $formData);
            header('Location: ?page=promotions&action=add-promo');
            exit;
        }

        $imagePath = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $imagePath = uploadImage($_FILES['photo']);
            if (!$imagePath) {
                setSessionMessage('form_errors', ['photo' => 'Erreur lors du téléchargement de l\'image']);
                setSessionMessage('form_data', $formData);
                header('Location: ?page=promotions&action=add-promo');
                exit;
            }
        }

        $newPromo = [
            'titre' => $formData['nom'],
            'image' => $imagePath,
            'date_debut' => $formData['debut'],
            'date_fin' => $formData['fin'],
            'referentiels' => $formData['referentiels'],
            'statut' => 'Inactive',
            'apprenants' => 0,

        ];

        if (!addPromotion($newPromo)) {
            setSessionMessage('error_message', 'Erreur lors de la création de la promotion');
            header('Location: ?page=promotions&action=add-promo');
            exit;
        }

        setSessionMessage('success_message', 'Promotion créée avec succès');
        header('Location: ?page=promotions&action=liste-promo');
        exit;
    }

    require_once __DIR__ . '/../Views/promotions/promo.form.view.php';
}

function handlePromoListPaginatedAction()
{
    $searchTerm = $_GET['search'] ?? '';
    $itemsPerPage = 5;
    $currentPage = isset($_GET['page_num']) ? max((int) $_GET['page_num'], 1) : 1;

    $offset = ($currentPage - 1) * $itemsPerPage;
    $allPromotions = getAllPromotions();
    $allPromotions = searchItems($allPromotions, $searchTerm);

    $totalPromotions = count($allPromotions);
    $promotions = array_slice($allPromotions, $offset, $itemsPerPage);
    $totalPages = max(ceil($totalPromotions / $itemsPerPage), 1);

    $activePromo = getActivePromotion();
    $stats = calculateStatistics($activePromo, $allPromotions);

    require_once __DIR__ . '/../Views/promotions/promo.af.liste.view.php';
}
function calculateStatistics($activePromo, $allPromotions)
{

    $apprenants = 0;
    if ($activePromo) {

        $apprenants = count_apprenants_by_promotion($activePromo['id']);
    }

    $referentiels = $activePromo ? count($activePromo['referentiels']) : 0;
    $promotions_actives = $activePromo ? 1 : 0;

    return [
        'apprenants' => $apprenants,
        'referentiels' => $referentiels,
        'promotions_actives' => $promotions_actives,
        'total_promotions' => count($allPromotions)
    ];
}
function handleDefaultPromoAction()
{
    show404Error("Erreur : l'action demandée n'existe pas dans le module des promotions.");
}

function handle_request_promo()
{
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $action = $_GET['action'] ?? 'liste-promo';

    try {
        switch ($action) {
            case 'liste-promo':
                handlePromoListAction();
                break;
            case 'toggle-promo':
                handleTogglePromoAction();
                break;
            case 'add-promo':
                handleAddPromoAction();
                break;
            case 'promo-liste':
                handlePromoListPaginatedAction();
                break;
            default:
                handleDefaultPromoAction();
                break;
        }
    } catch (Exception $e) {
        setSessionMessage('error_message', 'Une erreur est survenue: ' . $e->getMessage());
        header('Location: ?page=promotions&action=liste-promo');
        exit;
    }
}


if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    handle_request_promo();
}