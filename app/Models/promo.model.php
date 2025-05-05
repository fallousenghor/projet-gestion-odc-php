<?php

require_once __DIR__ . '/../utils/utils.php';


function getAllPromotions()
{
    $filePath = getPromoDataFilePath();
    if (!file_exists($filePath)) {
        return [];
    }

    $data = decode_json(file_get_contents($filePath));
    $promotions = $data['promotions'] ?? [];

    foreach ($promotions as &$promo) {
        $promo['apprenants_count'] = count_apprenants_by_promotion($promo['id']);
    }

    return $promotions;
}

function deactivateAllPromotionsExcept($promotionId)
{
    $filePath = getPromoDataFilePath();
    $json = file_get_contents($filePath);
    $data = decode_json($json);

    foreach ($data['promotions'] as &$promo) {
        if ($promo['id'] != $promotionId) {
            $promo['statut'] = 'Inactive';
        }
    }

    file_put_contents($filePath, encode_json($data));
}

function addPromotion($newPromo)
{
    $filePath = getPromoDataFilePath();
    $json = file_get_contents($filePath);
    $data = decode_json($json);

    $newId = ($data['last_id'] ?? 0) + 1;
    $newPromo['id'] = $newId;

    $data['promotions'][] = $newPromo;
    $data['last_id'] = $newId;

    file_put_contents($filePath, encode_json($data));

    return $newId;
}

function getCurrentPromotions()
{
    $filePath = getPromoDataFilePath();
    $data = file_exists($filePath) ? decode_json(file_get_contents($filePath)) : ['promotions' => []];

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
    $filePath = getPromoDataFilePath();
    if (!file_exists($filePath)) {
        return 1;
    }
    $json = file_get_contents($filePath);
    $data = decode_json($json);
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
    $filePath = getPromoDataFilePath();
    $json = file_get_contents($filePath);
    $data = decode_json($json);

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
    $filePath = getPromoDataFilePath();
    $data = file_exists($filePath) ? decode_json(file_get_contents($filePath)) : [];
    $apprenants = $data['apprenants'] ?? [];

    $count = 0;
    foreach ($apprenants as $apprenant) {
        if (($apprenant['promotion_id'] ?? null) == $promotion_id) {
            $count++;
        }
    }
    return $count;
}