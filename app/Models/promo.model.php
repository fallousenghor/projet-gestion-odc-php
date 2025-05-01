<?php

function encode_json($data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur lors de l'encodage JSON: " . json_last_error_msg());
    }
    return $json;
}

function getAllPromotions()
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    if (!file_exists($filePath)) {
        return [];
    }

    $data = json_decode(file_get_contents($filePath), true);
    $promotions = $data['promotions'] ?? [];


    foreach ($promotions as &$promo) {
        $promo['apprenants_count'] = count_apprenants_by_promotion($promo['id']);
    }

    return $promotions;
}

function deactivateAllPromotionsExcept($promotionId)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    foreach ($data['promotions'] as &$promo) {
        if ($promo['id'] != $promotionId) {
            $promo['statut'] = 'Inactive';
        }
    }

    file_put_contents($filePath, encode_json($data));
}

function addPromotion($newPromo)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    $newId = ($data['last_id'] ?? 0) + 1;
    $newPromo['id'] = $newId;

    $data['promotions'][] = $newPromo;
    $data['last_id'] = $newId;

    file_put_contents($filePath, encode_json($data));

    return $newId;
}

function getCurrentPromotions()
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : ['promotions' => []];

    $currentDate = date('d-m-Y');
    $currentPromotions = [];

    foreach ($data['promotions'] as $promo) {
        if ($promo['date_fin'] >= $currentDate) {
            $currentPromotions[] = $promo;
        }
    }

    return $currentPromotions;
}

function getNextPromoId()
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    if (!file_exists($filePath)) {
        return 1;
    }
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);
    return ($data['last_id'] ?? 0) + 1;
}

function isAnyPromotionActive()
{
    $promotions = getAllPromotions();
    foreach ($promotions as $promo) {
        if ($promo['statut'] === 'Actif') {
            return true;
        }
    }
    return false;
}

function getActivePromotion()
{
    $promotions = getAllPromotions();
    foreach ($promotions as $promo) {
        if ($promo['statut'] === 'Actif') {
            return $promo;
        }
    }
    return null;
}

function updatePromotionStatus($promotionId, $newStatus)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    foreach ($data['promotions'] as &$promo) {
        if ($promo['id'] == $promotionId) {
            $promo['statut'] = $newStatus;
            file_put_contents($filePath, encode_json($data));
            return true;
        }
    }
    return false;
}

function getPromotionById($promotionId)
{
    $promotions = getAllPromotions();
    foreach ($promotions as $promo) {
        if ($promo['id'] == $promotionId) {
            return $promo;
        }
    }
    return null;
}

function count_apprenants_by_promotion($promotion_id)
{

    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
    $apprenants = $data['apprenants'] ?? [];

    $count = 0;
    foreach ($apprenants as $apprenant) {
        if (($apprenant['promotion_id'] ?? null) == $promotion_id) {
            $count++;
        }
    }
    return $count;
}