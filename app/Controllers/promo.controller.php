<?php
require_once __DIR__ . '/../utils/utils.php';

function handlePromoListAction()
{
    $searchTerm = $_GET['search'] ?? '';
    $itemsPerPage = 6;
    $currentPage = isset($_GET['page_num']) ? max((int) $_GET['page_num'], 1) : 1;

    $offset = ($currentPage - 1) * $itemsPerPage;
    $allPromotions = getAllPromotions();
    $allPromotions = searchItems($allPromotions, $searchTerm);

    $totalPromotions = count($allPromotions);
    $promotions = array_slice($allPromotions, $offset, $itemsPerPage);
    $totalPages = max(ceil($totalPromotions / $itemsPerPage), 1);

    $activePromo = getActivePromotion();
    $stats = calculateStatistics($activePromo, $allPromotions);


    $viewData = [
        // 'promotions' => $promotionsForPage,
        'activePromo' => $activePromo,
        'totalApprenants' => $stats['apprenants'],
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'searchTerm' => $searchTerm
    ];

    extract($viewData);
    require_once __DIR__ . '/../Views/promotions/promo.liste.view.php';
}
function handleTogglePromoAction()
{
    $promoId = $_GET['id'] ?? null;

    if ($promoId) {
        $filePath = getPromoDataFilePath();

        if (file_exists($filePath)) {
            $data = readPromoDataFromFile($filePath);

            if (isset($data['promotions'])) {
                $promoEnCours = togglePromotionStatus($data['promotions'], $promoId);

                if ($promoEnCours) {
                    reorderPromotions($data['promotions'], $promoEnCours);
                    savePromoDataToFile($filePath, $data);
                }
            }
        }
    }

    redirectToPromoList();
}


function readPromoDataFromFile(string $filePath): array
{
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

function togglePromotionStatus(array &$promotions, string $promoId): ?array
{
    $promoEnCours = null;

    foreach ($promotions as &$promo) {
        if ($promo['id'] == $promoId) {
            if ($promo['statut'] === TextPromo::INACTIF->value) {
                foreach ($promotions as &$p) {
                    $p['statut'] = TextPromo::INACTIF->value;
                }
                $promo['statut'] = TextPromo::ACTIF->value;
            } else {
                $promo['statut'] = TextPromo::INACTIF->value;
            }
            $promoEnCours = $promo;
            break;
        }
    }

    return $promoEnCours;
}

function reorderPromotions(array &$promotions, array $promoEnCours): void
{
    $promotions = array_filter($promotions, fn($p) => $p['id'] != $promoEnCours['id']);

    if ($promoEnCours['statut'] === TextPromo::ACTIF->value) {
        array_unshift($promotions, $promoEnCours);
    } else {
        $promotions[] = $promoEnCours;
    }
}

function savePromoDataToFile(string $filePath, array $data): void
{
    file_put_contents($filePath, encode_json($data));
}

function redirectToPromoList(): void
{
    header('Location: ?page=promotions&action=liste-promo');
    exit;
}

function handleAddPromoAction()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formData = collectPromoFormData();
        $validation = validatePromotionData($formData, $_FILES);

        if (!$validation['isValid']) {
            handleValidationErrors($validation['errors'], $formData);
        }

        $imagePath = handlePromoImageUpload($_FILES, $formData);

        $newPromo = preparePromoData($formData, $imagePath);

        if (!addPromotion($newPromo)) {
            handlePromoCreationError();
        }

        handlePromoCreationSuccess();
    }

    renderPromoFormView();
}

function collectPromoFormData(): array
{
    return [
        'nom' => trim($_POST['nom'] ?? ''),
        'debut' => trim($_POST['debut'] ?? ''),
        'fin' => trim($_POST['fin'] ?? ''),
        'referentiels' => $_POST['referentiels'] ?? []
    ];
}

function handleValidationErrors(array $errors, array $formData): void
{
    setSessionMessage('form_errors', $errors);
    setSessionMessage('form_data', $formData);
    header('Location: ?page=promotions&action=add-promo');
    exit;
}

function handlePromoImageUpload(array $files, array $formData): string
{
    if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
        $imagePath = uploadImage($files['photo']);
        if (!$imagePath) {
            setSessionMessage('form_errors', [TextPromo::ERREUR_TELECHARGEMENT_IMAGE->value]);
            setSessionMessage('form_data', $formData);
            header('Location: ?page=promotions&action=add-promo');
            exit;
        }
        return $imagePath;
    }
    return '';
}

function preparePromoData(array $formData, string $imagePath): array
{
    return [
        'titre' => $formData['nom'],
        'image' => $imagePath,
        'date_debut' => $formData['debut'],
        'date_fin' => $formData['fin'],
        'referentiels' => $formData['referentiels'],
        'statut' => TextPromo::INACTIF->value,
        'apprenants' => 0,
    ];
}

function handlePromoCreationError(): void
{
    setSessionMessage('error_message', TextPromo::ERREUR_CREATION_PROMOTION->value);
    header('Location: ?page=promotions&action=add-promo');
    exit;
}

function handlePromoCreationSuccess(): void
{
    setSessionMessage('success_message', TextPromo::SUCCES_CREATION_PROMOTION->value);
    header('Location: ?page=promotions&action=liste-promo');
    exit;
}

function renderPromoFormView(): void
{
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
    show404Error(TextPromo::ERREUR_ACTION_INEXISTANTE->value);
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
        setSessionMessage('error_message', TextPromo::ERREUR_SURVENUE->value . $e->getMessage());
        header('Location: ?page=promotions&action=liste-promo');
        exit;
    }
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    handle_request_promo();
}