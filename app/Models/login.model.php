<?php
require_once(__DIR__ . '/../translate/fr/error.fr.php');
require_once(__DIR__ . '/../Services/validator.service.php');
require_once(__DIR__ . '/../Services/mail.service.php');


function getUsers()
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = file_get_contents($filePath);
    return json_decode($data, true)['users'];
}

function findUser($login, $password)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = json_decode(file_get_contents($filePath), true);

    foreach ($data['users'] as $user) {
        if (($user['email'] === $login || $user['matricule'] === $login) && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'email' => $user['email'],
                'matricule' => $user['matricule'],
                'role' => $user['role']
            ];

        }
    }

    foreach ($data['apprenants'] as $apprenant) {
        if (($apprenant['email'] === $login || $apprenant['matricule'] === $login) && password_verify($password, $apprenant['password'])) {
            return [
                'id' => $apprenant['id'],
                'email' => $apprenant['email'],
                'matricule' => $apprenant['matricule'],
                'role' => 'Apprenant',
                'must_change_password' => $apprenant['must_change_password']
            ];
        }
    }

    return null;
}

function saveUsers($users)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $fullData = json_decode(file_get_contents($filePath), true);
    $fullData['users'] = $users;
    file_put_contents($filePath, json_encode($fullData, JSON_PRETTY_PRINT));
}

function findUserByEmail($email)
{
    $filePath = __DIR__ . '/../../public/data/data.json';
    $data = json_decode(file_get_contents($filePath), true);


    foreach ($data['users'] as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }


    foreach ($data['apprenants'] as $apprenant) {
        if ($apprenant['email'] === $email) {
            return $apprenant;
        }
    }

    return null;
}

function verifyUserForReset($email, $securityQuestion, $securityAnswer)
{
    $user = findUserByEmail($email);
    if (!$user) {
        return MessagesUser::USER_NOT_FOUND->value;
    }

    if ($user['security_question'] === $securityQuestion && $user['security_answer'] === $securityAnswer) {
        return true;
    }

    return MessagesUser::SECURITY_ANSWER_INCORRECT->value;
}


use MessagesUser;

function resetPasswordDirectly($email, $newPassword)
{
    $strengthCheck = verifyPasswordStrength($newPassword);
    if ($strengthCheck !== true) {
        return $strengthCheck;
    }

    $filePath = __DIR__ . '/../../public/data/data.json';
    $fullData = json_decode(file_get_contents($filePath), true);
    $updated = false;


    foreach ($fullData['users'] as &$user) {
        if ($user['email'] === $email) {
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            $user['must_change_password'] = false;
            $updated = true;
            break;
        }
    }


    if (!$updated) {
        foreach ($fullData['apprenants'] as &$apprenant) {
            if ($apprenant['email'] === $email) {
                $apprenant['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                $apprenant['must_change_password'] = false;
                $updated = true;
                break;
            }
        }
    }

    if ($updated) {
        file_put_contents($filePath, json_encode($fullData, JSON_PRETTY_PRINT));
        return MessagesUser::PASSWORD_RESET_SUCCESS->value;
    }

    return MessagesUser::PASSWORD_RESET_FAILED->value;
}



function createUser($email, $prenom)
{
    $users = getUsers();

    if (findUserByEmail($email)) {
        return MessagesUser::USER_ALREADY_EXISTS->value;
    }

    $passwordTemp = bin2hex(random_bytes(4));
    $hashedPassword = password_hash($passwordTemp, PASSWORD_DEFAULT);

    $newUser = [
        "email" => $email,
        "prenom" => $prenom,
        "password" => $hashedPassword,
        "must_change_password" => true
    ];

    $users[] = $newUser;
    saveUsers($users);


    sendEmail($email, $prenom, $passwordTemp, "http://senghor.fallou.sa.edu.sn/projet");

    return MessagesUser::USER_CREATED->value;
}