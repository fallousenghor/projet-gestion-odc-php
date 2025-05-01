<?php

function redirectToPromotions()
{
    header("Location: /projet/?page=promotions");
    exit();
}

function redirectToLogin()
{
    header("Location: /?page=login");
    exit();
}

function redirectToDashboard()
{
    header("Location: /?page=dashboard");
    exit();
}


function display_login_page($error = "", $login = "")
{
    include __DIR__ . '/../Views/login/login.view.php';
}

function display_login_page_with_error($error, $login = "")
{
    include __DIR__ . '/../Views/login/login.view.php';
}

function display_password_reset_page($error = "")
{
    include __DIR__ . '/../Views/login/fotget.password.php';
}

function display_password_reset_page_with_error($error)
{
    include __DIR__ . '/../Views/login/fotget.password.php';
}

function display_password_update_page($error = "")
{
    include __DIR__ . '/../Views/login/reinitialise.password.php';
}

function display_password_update_page_with_error($error)
{
    include __DIR__ . '/../Views/login/reinitialise.password.php';
}

function display_login_page_with_success($message)
{
    include __DIR__ . '/../Views/login/login.view.php';
}