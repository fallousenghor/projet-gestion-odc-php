<?php
// Services/apprenant.service.php

require_once __DIR__ . '/../Models/apprenant.model.php';
require_once __DIR__ . '/../Models/promo.model.php';
require_once __DIR__ . '/../Models/ref.model.php';
require_once __DIR__ . '/session.service.php';
require_once __DIR__ . '/validator.service.php';
require_once __DIR__ . '/mail.service.php';

function get_liste_apprenant($filters, $page, $perPage)
{
    $result = get_paginated_apprenants($filters, $page, $perPage);

    $startItem = ($page - 1) * $perPage + 1;
    $endItem = min($page * $perPage, $result['total']);
    $totalApprenants = $result['total'];
    $totalPages = $result['total_pages'];

    return [
        'apprenants' => $result['data'],
        'startItem' => $startItem,
        'endItem' => $endItem,
        'totalApprenants' => $totalApprenants,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'perPage' => $perPage,
        'search' => $filters['search'] ?? '',
        'referentiel' => $filters['referentiel'] ?? '',
        'status' => $filters['status'] ?? ''
    ];
}

function get_apprenant_by_id_service($apprenantId)
{
    return get_apprenant_by_id($apprenantId);
}

function handle_ad_apprenant_service($activePromo, $filters, $page, $perPage)
{
    $referentiels = get_referentiels_by_promo_id($activePromo['referentiels']);

    if (empty($referentiels)) {
        $_SESSION['flash_message'] = "Aucun référentiel associé à la promotion active";
        header('Location: ?page=referentiel&action=ad-ref');
        exit;
    }

    $result = get_paginated_apprenants($filters, $page, $perPage);

    $startItem = ($page - 1) * $perPage + 1;
    $endItem = min($page * $perPage, $result['total']);
    $totalApprenants = $result['total'];
    $totalPages = $result['total_pages'];

    return [
        'apprenants' => $result['data'],
        'startItem' => $startItem,
        'endItem' => $endItem,
        'totalApprenants' => $totalApprenants,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'perPage' => $perPage,
        'search' => $filters['search'] ?? '',
        'referentiel' => $filters['referentiel'] ?? '',
        'status' => $filters['status'] ?? ''
    ];
}

function save_apprenant_service($postData, $filesData, $activePromo)
{
    $errors = validate_apprenant_data($postData);

    if (!$errors['isValid']) {
        $_SESSION['form_errors'] = $errors['errors'];
        $_SESSION['form_data'] = $postData;
        header('Location: ?page=apprenant&action=ad-apprenant');
        exit;
    }

    $documents = upload_documents($filesData);

    $apprenant = [
        'prenom' => htmlspecialchars($postData['prenom']),
        'nom' => htmlspecialchars($postData['nom']),
        'date_naissance' => htmlspecialchars($postData['dateNaissance']),
        'lieu_naissance' => htmlspecialchars($postData['lieuNaissance']),
        'adresse' => htmlspecialchars($postData['adresse']),
        'email' => htmlspecialchars($postData['email']),
        'telephone' => htmlspecialchars($postData['telephone']),
        'documents' => $documents,
        'status' => 'active',
        'tuteur' => [
            'nom' => htmlspecialchars($postData['tuteurNom']),
            'lien_parente' => htmlspecialchars($postData['parente']),
            'adresse' => htmlspecialchars($postData['tuteurAdresse']),
            'telephone' => htmlspecialchars($postData['tuteurTelephone'])
        ],
        'promotion_id' => $activePromo['id'],
        'referentiel_id' => htmlspecialchars($postData['referentiel_id'])
    ];

    return save_apprenant($apprenant);
}

function get_apprenants_by_referentiel_service($referentielId)
{
    return get_apprenants_by_referentiel($referentielId);
}