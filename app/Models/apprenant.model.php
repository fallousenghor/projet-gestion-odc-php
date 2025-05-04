<?php

function get_all_apprenant()
{
    $filePath = __DIR__ . '/../../public/data/data.json';

    if (!file_exists($filePath)) {
        file_put_contents($filePath, encode_json(['apprenants' => [], 'last_id' => 0]));
        return [];
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    return $data['apprenants'] ?? [];
}

function generateMatricule($apprenantData)
{
    $initials = '';
    if (isset($apprenantData['prenom']) && !empty($apprenantData['prenom'])) {
        $initials .= strtoupper(substr($apprenantData['prenom'], 0, 1));
    }
    if (isset($apprenantData['nom']) && !empty($apprenantData['nom'])) {
        $initials .= strtoupper(substr($apprenantData['nom'], 0, 1));
    }

    if (empty($initials)) {
        $initials = 'AN';
    }

    $datePart = date('Ym');

    $filePath = __DIR__ . '/../../public/data/data.json';
    $json = file_get_contents($filePath);
    $data = json_decode($json, true);
    $last_id = $data['last_id'] ?? 0;
    $seq = str_pad($last_id + 1, 4, '0', STR_PAD_LEFT);

    $matricule = $initials . $datePart . $seq;

    return $matricule;
}

function save_apprenant($apprenant)
{
    $filePath = __DIR__ . '/../../public/data/data.json';

    if (!file_exists($filePath)) {
        file_put_contents($filePath, json_encode(['apprenants' => [], 'last_id' => 0, 'promotions' => [], 'referentiels' => []]));
        $data = ['apprenants' => [], 'last_id' => 0, 'promotions' => [], 'referentiels' => []];
    } else {
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);
    }

    $last_id = $data['last_id'] ?? 0;
    $new_id = $last_id + 1;
    $apprenant['id'] = $new_id;

    $apprenant['matricule'] = generateMatricule($apprenant);

    $temporaryPassword = generateTemporaryPassword();
    $apprenant['password'] = password_hash($temporaryPassword, PASSWORD_DEFAULT);
    $apprenant['must_change_password'] = true;
    $apprenant['created_at'] = date('Y-m-d H:i:s');

    $data['apprenants'][] = $apprenant;
    $data['last_id'] = $new_id;

    $saved = file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;

    if ($saved) {
        $loginUrl = 'http://senghor.fallou.sa.edu.sn/projet/';

        try {
            sendEmail(
                $apprenant['email'],
                $apprenant['prenom'],
                $temporaryPassword,
                $loginUrl
            );
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $e->getMessage());
        }
    }

    return $saved;
}

function get_apprenants_by_promotion($promotion_id)
{
    $apprenants = get_all_apprenant();
    return array_filter($apprenants, function ($apprenant) use ($promotion_id) {
        return isset($apprenant['promotion_id']) && $apprenant['promotion_id'] == $promotion_id;
    });
}

function get_apprenants_by_referentiel($referentiel_id)
{
    $apprenants = get_all_apprenant();
    return array_filter($apprenants, function ($apprenant) use ($referentiel_id) {
        return isset($apprenant['referentiel_id']) && $apprenant['referentiel_id'] == $referentiel_id;
    });
}

function upload_documents($files)
{
    if (empty($files) || !isset($files['documents']) || $files['documents']['error'][0] == UPLOAD_ERR_NO_FILE) {
        return [];
    }

    $uploaded_files = [];
    $upload_dir = __DIR__ . '/../../public/uploads/documents/';

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_count = count($files['documents']['name']);

    for ($i = 0; $i < $file_count; $i++) {
        if ($files['documents']['error'][$i] == UPLOAD_ERR_OK) {
            $tmp_name = $files['documents']['tmp_name'][$i];
            $name = basename($files['documents']['name'][$i]);
            $unique_name = uniqid() . '_' . $name;
            $destination = $upload_dir . $unique_name;

            if (move_uploaded_file($tmp_name, $destination)) {
                $uploaded_files[] = $unique_name;
            }
        }
    }

    return $uploaded_files;
}

function get_promotion_by_id($id)
{
    $promotions = getAllPromotions();
    foreach ($promotions as $promotion) {
        if (isset($promotion['id']) && $promotion['id'] == $id) {
            return $promotion;
        }
    }
    return null;
}

function filter_apprenants($filters = [])
{
    $all_apprenants = get_all_apprenant();
    $filtered = [];

    foreach ($all_apprenants as $apprenant) {
        $match = true;

        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $nom = strtolower($apprenant['nom'] ?? '');
            $prenom = strtolower($apprenant['prenom'] ?? '');
            $matricule = strtolower($apprenant['matricule'] ?? '');

            if (
                strpos($nom, $search) === false &&
                strpos($prenom, $search) === false &&
                strpos($matricule, $search) === false
            ) {
                $match = false;
            }
        }

        if (!empty($filters['referentiel']) && isset($apprenant['referentiel_id'])) {
            if ($apprenant['referentiel_id'] != $filters['referentiel']) {
                $match = false;
            }
        }

        if (!empty($filters['status'])) {
            $currentStatus = strtolower($apprenant['status'] ?? 'actif');
            if ($currentStatus != strtolower($filters['status'])) {
                $match = false;
            }
        }

        if (!empty($filters['promotion_id']) && isset($apprenant['promotion_id'])) {
            if ($apprenant['promotion_id'] != $filters['promotion_id']) {
                $match = false;
            }
        }

        if ($match) {
            $filtered[] = $apprenant;
        }
    }

    return $filtered;
}

function get_paginated_apprenants($filters = [], $page = 1, $perPage = 5)
{
    $filtered = filter_apprenants($filters);

    $total = count($filtered);
    $totalPages = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    $paginated = array_slice($filtered, $offset, $perPage);

    return [
        'data' => $paginated,
        'total' => $total,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'per_page' => $perPage
    ];
}

function generateTemporaryPassword($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function get_apprenant_by_id($id)
{
    $filePath = __DIR__ . '/../../public/data/data.json';

    if (!file_exists($filePath)) {
        return null;
    }

    $json = file_get_contents($filePath);
    $data = json_decode($json, true);

    foreach ($data['apprenants'] as $apprenant) {
        if ($apprenant['id'] == $id) {
            return $apprenant;
        }
    }

    return null;
}