<?php

function get_all_ref()
{
    $filePath = __DIR__ . '/../../public/data/data.json';


    if (!file_exists($filePath)) {
        if (!is_writable(dirname($filePath))) {
            throw new Exception("Le répertoire de données n'est pas accessible en écriture");
        }

        $initialData = ['referentiels' => [], 'last_id' => 0];
        if (file_put_contents($filePath, json_encode($initialData)) === false) {
            throw new Exception("Impossible de créer le fichier de données");
        }
        return [];
    }


    if (!is_readable($filePath)) {
        throw new Exception("Le fichier de données n'est pas accessible en lecture");
    }


    $json = file_get_contents($filePath);
    if ($json === false) {
        throw new Exception("Impossible de lire le fichier de données");
    }


    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur de format dans le fichier JSON: " . json_last_error_msg());
    }

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

    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
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
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
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

    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];
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