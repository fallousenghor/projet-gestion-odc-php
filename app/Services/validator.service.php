<?php
require_once __DIR__ . '/../Models/promo.model.php';

function promotionExistsByName($name)
{
    $allPromotions = getAllPromotions();
    foreach ($allPromotions as $promo) {
        if ($promo['titre'] === $name) {
            return true;
        }
    }
    return false;
}

function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function validatePromotionData($data, $files = [])
{
    $errors = [];

    if (empty(trim($data['nom'] ?? ''))) {
        $errors['nom'] = "Le nom de la promotion est requis";
    } elseif (promotionExistsByName(trim($data['nom']))) {
        $errors['nom'] = "Une promotion avec ce nom existe déjà";
    }

    if (empty(trim($data['debut'] ?? ''))) {
        $errors['debut'] = "La date de début est requise";
    } elseif (!validateDate(trim($data['debut']))) {
        $errors['debut'] = "Format invalide (JJ/MM/AAAA requis)";
    }

    if (empty(trim($data['fin'] ?? ''))) {
        $errors['fin'] = "La date de fin est requise";
    } elseif (!validateDate(trim($data['fin']))) {
        $errors['fin'] = "Format invalide (JJ/MM/AAAA requis)";
    }

    if (empty($errors['debut']) && empty($errors['fin'])) {
        $debut = DateTime::createFromFormat('d/m/Y', $data['debut']);
        $fin = DateTime::createFromFormat('d/m/Y', $data['fin']);

        if ($debut > $fin) {
            $errors['fin'] = "La date de fin doit être postérieure à la date de début";
        }
    }

    if (empty($files['photo']['name'] ?? '')) {
        $errors['photo'] = "Une photo est requise";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($files['photo']['type'], $allowedTypes)) {
            $errors['photo'] = "Format invalide (seuls JPG et PNG sont acceptés)";
        } elseif ($files['photo']['size'] > $maxSize) {
            $errors['photo'] = "La taille de l'image ne doit pas dépasser 2MB";
        }
    }

    return [
        'isValid' => empty($errors),
        'errors' => $errors
    ];
}

function verifyPasswordStrength($password)
{
    return strlen($password) < 8
        ? MessagesUser::PASSWORD_TOO_SHORT->value
        : true;
}

function validateReferentielData($data, $files = [])
{
    $errors = [];

    if (empty(trim($data['nom'] ?? ''))) {
        $errors['nom'] = "Le nom du référentiel est requis";
    } else {
        $nom = trim($data['nom']);
        $allReferentiels = get_all_ref();
        foreach ($allReferentiels as $ref) {
            if (strtolower($ref['titre']) === strtolower($nom)) {
                $errors['nom'] = "Un référentiel avec ce nom existe déjà";
                break;
            }
        }
    }

    if (empty(trim($data['desc'] ?? ''))) {
        $errors['desc'] = "La description est requise";
    }

    if (empty($data['capacite'] ?? '')) {
        $errors['capacite'] = "La capacité est requise";
    } elseif (!is_numeric($data['capacite']) || $data['capacite'] <= 0) {
        $errors['capacite'] = "La capacité doit être un nombre positif";
    }

    if (empty($data['sessions'] ?? '')) {
        $errors['sessions'] = "Le nombre de sessions est requis";
    } elseif (!in_array($data['sessions'], ['1', '2', '3'])) {
        $errors['sessions'] = "Nombre de sessions invalide";
    }

    if (empty($files['photo']['name'] ?? '')) {
        $errors['photo'] = "Une photo est requise";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($files['photo']['type'], $allowedTypes)) {
            $errors['photo'] = "Format invalide (seuls JPG et PNG sont acceptés)";
        } elseif ($files['photo']['size'] > $maxSize) {
            $errors['photo'] = "La taille de l'image ne doit pas dépasser 2MB";
        }
    }

    return [
        'isValid' => empty($errors),
        'errors' => $errors
    ];
}

function validate_apprenant_data(array $data): array
{
    $errors = [];

    $requiredFields = [
        'prenom',
        'nom',
        'dateNaissance',
        'lieuNaissance',
        'adresse',
        'email',
        'telephone',
        'tuteurNom',
        'parente',
        'tuteurAdresse',
        'tuteurTelephone',
        'referentiel_id'
    ];

    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            $errors[$field] = "Ce champ est obligatoire";
        }
    }

    if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email invalide";
    } elseif (emailExists($data['email'])) {
        $errors['email'] = "Cet email est déjà utilisé";
    }
    if (telephoneExists($data['telephone'])) {
        $errors['telephone'] = "Ce numéro de téléphone est déjà utilisé";
    }

    // if (!preg_match('/^[0-9]{10,15}$/', $data['tuteurTelephone'] ?? '')) {
    //     $errors['tuteurTelephone'] = "Numéro de téléphone du tuteur invalide";
    // }

    return [
        'isValid' => empty($errors),
        'errors' => $errors
    ];
}


function emailExists($email)
{
    $apprenants = get_all_apprenant();
    foreach ($apprenants as $apprenant) {
        if ($apprenant['email'] === $email) {
            return true;
        }
    }
    return false;
}

function telephoneExists($telephone)
{
    $apprenants = get_all_apprenant();
    foreach ($apprenants as $apprenant) {
        if ($apprenant['telephone'] === $telephone) {
            return true;
        }
    }
    return false;
}