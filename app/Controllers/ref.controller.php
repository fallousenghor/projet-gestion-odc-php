<?php
require_once __DIR__ . '/../Services/session.service.php';
require_once __DIR__ . '/../Models/ref.model.php';
require_once __DIR__ . '/../Controllers/controller.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Services/validator.service.php';
require_once __DIR__ . '/../Services/PromoStateService.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function handle_request_ref()
{
    $action = getRequestAction();

    try {
        handleAction($action);
    } catch (Exception $e) {
        logError($e);
        require_once __DIR__ . '/../Views/error.view.php';
    }
}

function getRequestAction(): string
{
    return isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 'liste-ref';
}

function handleListeRef(): void
{
    $searchTerm = getSearchTerm();
    $activePromo = getActivePromotion();
    $paginationData = getPaginationData();

    if ($activePromo) {
        $allReferentiels = get_referentiels_by_promo_id($activePromo['referentiels']);

        $allReferentiels = array_map(function ($ref) {
            $ref['apprenants_count'] = count_apprenants_by_referentiel($ref['id']);
            return $ref;
        }, $allReferentiels);

        $allReferentiels = searchReferentiels($allReferentiels, $searchTerm);
        $referentiels = paginateItems($allReferentiels, $paginationData);
        $totalPages = calculateTotalPages(count($allReferentiels), $paginationData['itemsPerPage']);
    } else {
        $referentiels = [];
        $totalPages = 1;
    }

    displayView('../Views/referentiels/liste.ref.view.php', [
        'referentiels' => $referentiels,
        'totalPages' => $totalPages,
        'currentPage' => $paginationData['currentPage'],
        'searchTerm' => $searchTerm,
        'activePromo' => $activePromo
    ]);
}

function handleToutRef(): void
{
    $searchTerm = getSearchTerm();
    $paginationData = getPaginationData();
    $activePromo = getActivePromotion();
    $allReferentiels = get_all_ref();

    $allReferentiels = array_map(function ($ref) {
        $ref['apprenants_count'] = count_apprenants_by_referentiel($ref['id']);
        return $ref;
    }, $allReferentiels);

    $allReferentiels = searchReferentiels($allReferentiels, $searchTerm);
    $referentiels = paginateItems($allReferentiels, $paginationData);
    $totalPages = calculateTotalPages(count($allReferentiels), $paginationData['itemsPerPage']);

    displayView('../Views/referentiels/liste.tous.ref.view.php', [
        'referentiels' => $referentiels,
        'totalPages' => $totalPages,
        'currentPage' => $paginationData['currentPage'],
        'searchTerm' => $searchTerm,
        'activePromo' => $activePromo,
        'action' => 'tout-ref',
    ]);
}

function handleAdRef(): void
{
    $activePromo = getActivePromotion();

    if (!$activePromo) {
        setFlashMessageAndRedirect("Aucune promotion active sélectionnée", '?page=referentiel');
        return;
    }


    require_once __DIR__ . '/../Services/PromoStateService.php';
    $activePromo['etat'] = \App\Services\PromoStateService::getState($activePromo);

    $referentiels = get_all_ref();
    displayView('referentiels/ad.ref.php', [
        'activePromo' => $activePromo,
        'referentiels' => $referentiels
    ]);
}

function handleNewRef(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $validation = validateReferentielData($_POST, $_FILES);

        if ($validation['isValid']) {
            $imagePath = uploadImage($_FILES['photo']);

            if ($imagePath === false) {
                displayViewWithError('../Views/referentiels/new.ref.php', ['photo' => "Erreur lors du traitement de l'image"]);
                return;
            }

            $newRef = [
                'id' => uniqid(),
                'titre' => trim(htmlspecialchars($_POST['nom'])),
                'description' => htmlspecialchars($_POST['desc']),
                'modules' => (int) $_POST['sessions'],
                'apprenants' => (int) $_POST['capacite'],
                'image' => $imagePath,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if (saveReferentielToFile($newRef)) {
                setFlashMessageAndRedirect("Référentiel ajouté avec succès", '?page=referentiel&action=liste-ref');
            } else {
                displayViewWithError('../Views/referentiels/new.ref.php', ['general' => "Erreur lors de la sauvegarde"]);
            }
        } else {
            displayViewWithError('../Views/referentiels/new.ref.php', $validation['errors']);
        }
    } else {
        displayView('../Views/referentiels/new.ref.php');
    }
}

function handleAssignRef(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        setFlashMessageAndRedirect("Méthode non autorisée", '?page=referentiel');
        return;
    }

    $activePromo = getActivePromotion();

    if (!$activePromo) {
        setFlashMessageAndRedirect("Aucune promotion active sélectionnée", '?page=referentiel');
        return;
    }


    $promoState = \App\Services\PromoStateService::getState($activePromo);
    if ($promoState === 'termine') {
        setFlashMessageAndRedirect(
            "Les modifications sont désactivées pour les promotions terminées",
            '?page=referentiel&action=liste-ref'
        );
        return;
    }


    $newRefs = $_POST['referentiels'] ?? [];
    $currentAssignedRefs = $activePromo['referentiels'] ?? [];
    $remainingRefs = $_POST['assigned_refs'] ?? [];


    $allRefs = get_all_ref();
    $validRefs = array_filter($allRefs, function ($ref) use ($newRefs, $remainingRefs) {
        return in_array($ref['id'] ?? null, array_merge($newRefs, $remainingRefs));
    });

    $updatedRefs = array_column($validRefs, 'id');


    if (updatePromotionReferentiels($activePromo['id'], $updatedRefs)) {
        $message = "Référentiels mis à jour avec succès";


        $removedCount = count($currentAssignedRefs) - count($remainingRefs);
        if ($removedCount > 0) {
            $message .= sprintf(" (%d référentiel(s) supprimé(s))", $removedCount);
        }

        if (!empty($newRefs)) {
            $message .= sprintf(" (%d nouveau(x) référentiel(s) ajouté(s))", count($newRefs));
        }

        setFlashMessageAndRedirect($message, '?page=referentiel&action=liste-ref');
    } else {
        setFlashMessageAndRedirect(
            "Erreur lors de la mise à jour des référentiels",
            '?page=referentiel&action=ad-ref'
        );
    }
}



function getSearchTerm(): string
{
    return $_GET['search'] ?? '';
}

function getPaginationData(): array
{
    $itemsPerPage = 4;
    $currentPage = isset($_GET['page_num']) ? (int) $_GET['page_num'] : 1;
    $currentPage = max(1, $currentPage);
    $offset = ($currentPage - 1) * $itemsPerPage;

    return [
        'itemsPerPage' => $itemsPerPage,
        'currentPage' => $currentPage,
        'offset' => $offset
    ];
}

function paginateItems(array $items, array $paginationData): array
{
    return array_slice($items, $paginationData['offset'], $paginationData['itemsPerPage']);
}

function calculateTotalPages(int $totalItems, int $itemsPerPage): int
{
    return ceil($totalItems / $itemsPerPage);
}

function saveReferentielToFile(array $newRef): bool
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : ['referentiels' => []];
    $data['referentiels'][] = $newRef;
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
}

function displayView(string $viewPath, array $data = []): void
{
    extract($data);
    require_once __DIR__ . '/../Views/' . $viewPath;
}

function displayViewWithError(string $viewPath, array $errors): void
{
    displayView($viewPath, ['errors' => $errors]);
}

function setFlashMessageAndRedirect(string $message, string $url): void
{
    $_SESSION['flash_message'] = $message;
    header('Location: ' . $url);
    exit;
}

function logError(Exception $e): void
{
    error_log("Erreur dans handle_request_ref: " . $e->getMessage());
}

function updatePromotionReferentiels(string $promoId, array $referentiels): bool
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    foreach ($data['promotions'] as &$promo) {
        if ($promo['id'] == $promoId) {
            $promo['referentiels'] = $referentiels;
            return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
        }
    }
    return false;
}

function handleDefaultRef(): void
{
    $paginationData = getPaginationData();
    $activePromo = getActivePromotion();

    if ($activePromo) {
        $allReferentiels = get_referentiels_by_promo_id($activePromo['referentiels']);
        $referentiels = paginateItems($allReferentiels, $paginationData);
        $totalPages = calculateTotalPages(count($allReferentiels), $paginationData['itemsPerPage']);
    } else {
        $referentiels = [];
        $totalPages = 1;
    }

    displayView('../Views/referentiels/liste.ref.view.php', [
        'referentiels' => $referentiels,
        'totalPages' => $totalPages,
        'currentPage' => $paginationData['currentPage'],
        'activePromo' => $activePromo,
    ]);
}

function handleAction(string $action): void
{
    switch ($action) {
        case 'liste-ref':
            handleListeRef();
            break;
        case 'tout-ref':
            handleToutRef();
            break;
        case 'ad-ref':
            handleAdRef();
            break;
        case 'new-ref':
            handleNewRef();
            break;
        case 'assign-ref':
            handleAssignRef();
            break;
        default:
            handleDefaultRef();
            break;
    }
}

if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
    handle_request_ref();
}