<?php
function uploadImage(array $file): string|false {
   
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileInfo = getimagesize($file['tmp_name']);
    
    if (!$fileInfo || !in_array($fileInfo['mime'], $allowedTypes)) {
        return false;
    }

    
    $uploadDir = __DIR__ . '/../../public/uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid('img_') . '.' . $fileExt;
    $uploadFile = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
        return false;
    }

    return '/uploads/' . $fileName;
}

function searchItems(array $items, string $searchTerm, string $searchField = 'titre'): array {
    if (empty($searchTerm)) {
        return $items;
    }

    return array_filter($items, function($item) use ($searchTerm, $searchField) {
       
        return isset($item[$searchField]) && 
               stripos($item[$searchField], $searchTerm) !== false;
    });
}


function show404Error($message = 'Page non trouvée.') {
    setSessionMessage('error_message', $message);
    require_once __DIR__ . '/../Controllers/error.controller.php';
    exit;
}