<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// session_start();

function setUserSession(array $user): void {
    $_SESSION['user'] = $user;
}

function getUserSession(): ?array {
    return $_SESSION['user'] ?? null;
}


function isAuthenticated(): bool {
    return isset($_SESSION['user']);
}


function logout(): void {
    $_SESSION = [];
    session_destroy();
    header('Location: /projet/');
    exit();
}

function storeResetEmail(string $email): void {
    $_SESSION['reset_email'] = $email;
}

function getResetEmail(): ?string {
    return $_SESSION['reset_email'] ?? null;
}

function clearResetEmail(): void {
    unset($_SESSION['reset_email']);
}



function setSessionMessage($key, $message) {
    $_SESSION[$key] = $message;
}

function getSessionMessage($key) {
    if (isset($_SESSION[$key])) {
        $message = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $message;
    }
    return null;
}

function setFormData($data) {
    $_SESSION['form_data'] = $data;
}

function getFormData() {
    if (isset($_SESSION['form_data'])) {
        $data = $_SESSION['form_data'];
        unset($_SESSION['form_data']);
        return $data;
    }
    return [];
}