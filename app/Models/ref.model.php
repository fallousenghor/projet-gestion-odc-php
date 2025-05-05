<?php

function get_all_ref()
{
    $filePath = getReferentielsFilePath();

    if (!file_exists($filePath)) {
        if (!is_writable(dirname($filePath))) {
            throw new Exception(MessageReferentiel::REPERTOIRE_NON_ACCESSIBLE->value);
        }

        $initialData = ['referentiels' => [], 'last_id' => 0];
        if (file_put_contents($filePath, encode_json($initialData)) === false) {
            throw new Exception(MessageReferentiel::IMPOSSIBLE_CREER_FICHIER->value);
        }
        return [];
    }

    if (!is_readable($filePath)) {
        throw new Exception(MessageReferentiel::FICHIER_NON_LECTURE->value);
    }

    $json = file_get_contents($filePath);
    if ($json === false) {
        throw new Exception(MessageReferentiel::FICHIER_NON_LISIBLE->value);
    }

    $data = decode_json($json);
    return $data['referentiels'] ?? [];
}


function get_referentiel_by_id($id)
{
    $referentiels = get_all_ref();

    foreach ($referentiels as $referentiel) {
        if (isset($referentiel['id']) && $referentiel['id'] == $id) {
            return $referentiel;
        }
    }

    return null;
}

function get_referentiels_by_promo_id($promoId)
{
    $referentiels = get_all_ref();
    $promoReferentiels = [];

    foreach ($referentiels as $referentiel) {
        if (in_array($referentiel['id'], $promoId)) {
            $promoReferentiels[] = $referentiel;
        }
    }

    return $promoReferentiels;
}

function searchReferentiels($referentiels, $searchTerm)
{
    if (empty($searchTerm)) {
        return $referentiels;
    }

    $filtered = [];
    $searchTerm = strtolower($searchTerm);

    foreach ($referentiels as $ref) {
        if (
            strpos(strtolower($ref['titre']), $searchTerm) !== false ||
            strpos(strtolower($ref['description'] ?? ''), $searchTerm) !== false
        ) {
            $filtered[] = $ref;
        }
    }

    return $filtered;
}

function referentiel_exists($nom)
{
    $all = get_all_ref();
    foreach ($all as $ref) {
        if (strtolower(trim($ref['titre'])) === strtolower(trim($nom))) {
            return true;
        }
    }
    return false;
}

function count_apprenants_by_referentiel($referentiel_id)
{
    $filePath = getReferentielsFilePath();
    $data = file_exists($filePath) ? decode_json(file_get_contents($filePath)) : [];
    $apprenants = $data['apprenants'] ?? [];

    $count = 0;
    foreach ($apprenants as $apprenant) {
        if (($apprenant['referentiel_id'] ?? null) == $referentiel_id) {
            $count++;
        }
    }
    return $count;
}

function has_apprenants_in_referentiel($referentiel_id, $promotion_id)
{
    $filePath = getReferentielsFilePath();
    $data = file_exists($filePath) ? decode_json(file_get_contents($filePath)) : [];
    $apprenants = $data['apprenants'] ?? [];

    foreach ($apprenants as $apprenant) {
        if (
            ($apprenant['referentiel_id'] ?? null) == $referentiel_id
            && ($apprenant['promotion_id'] ?? null) == $promotion_id
        ) {
            return true;
        }
    }
    return false;
}

function count_apprenants_in_referentiel($referentiel_id, $promotion_id)
{
    $filePath = getReferentielsFilePath();
    $data = file_exists($filePath) ? decode_json(file_get_contents($filePath)) : [];
    $apprenants = $data['apprenants'] ?? [];

    $count = 0;
    foreach ($apprenants as $apprenant) {
        if (
            ($apprenant['referentiel_id'] ?? null) == $referentiel_id
            && ($apprenant['promotion_id'] ?? null) == $promotion_id
        ) {
            $count++;
        }
    }
    return $count;
}