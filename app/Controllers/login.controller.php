<?php
require_once(__DIR__ . '/../Services/session.service.php');
require_once(__DIR__ . '/../translate/fr/error.fr.php');
require_once(__DIR__ . '/../Models/login.model.php');
require_once(__DIR__ . '/../helpers/redirection.php');
require_once(__DIR__ . '/../Services/mail.service.php');



function handle_login_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        return process_login();
    }
    display_login_page();
}

function process_login()
{
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = authenticate_user($login, $password);

    if ($user) {
        redirect_after_successful_login($user);
    } else {
        display_login_page_with_error(Messageslogin::LOGIN_FAILED->value, $login);
    }
}

function authenticate_user($login, $password)
{
    $user = findUser($login, $password);

    if ($user && isset($user['must_change_password']) && $user['must_change_password']) {
        setUserSession($user);

        header("Location: ?page=login&action=reinitialise");
        exit;
    }

    return $user;
}

function redirect_after_successful_login($user)
{
    setUserSession($user);

    if (isset($user['must_change_password']) && $user['must_change_password']) {
        header("Location: ?page=login&action=reinitialise");
        exit;
    }

    if ($user['role'] === 'Apprenant') {
        header("Location: ?page=apprenant&action=detail&id=" . $user['id']);
        // require_once(__DIR__ . '/../Views/apprenants/detail.apprenant.php');
        exit;
    }

    redirectToPromotions();
}

function handle_password_reset_request()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['email']) && !isset($_POST['password'])) {
            return process_email_submission();
        }

        if (isset($_POST['password']) && isset($_SESSION['reset_email'])) {
            return process_password_reset();
        }
    }

    display_password_reset_page();
}


function process_email_submission()
{
    $email = $_POST['email'];
    $user = findUserByEmail($email);

    if ($user) {
        storeResetEmail($email);
        display_password_update_page();
    } else {
        display_password_reset_page_with_error(Messageslogin::EMAIL_NOT_FOUND->value);
    }
}

function process_password_reset()
{
    $newPassword = $_POST['password'];
    $email = $_SESSION['reset_email'];

    $resetResult = update_user_password($email, $newPassword);

    if ($resetResult === Messageslogin::PASSWORD_RESET_SUCCESS->value) {
        unset($_SESSION['reset_email']);
        display_login_page_with_success(Messageslogin::PASSWORD_RESET_SUCCESS->value);
    } else {
        display_password_update_page_with_error($resetResult);
    }
}



function update_user_password($email, $newPassword)
{
    return resetPasswordDirectly($email, $newPassword);
}



function handle_login_actions()
{
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'forgot':
            handle_password_reset_request();
            break;
        case 'reinitialise':
            handle_password_reset_request();
            // require_once(__DIR__ . '/../Views/login/reinitialise.password.php');
            break;
        default:
            handle_login_request();
            break;
    }
}