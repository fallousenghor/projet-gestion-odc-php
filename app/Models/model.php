<?php

function encode_json($data)
{
    $json = json_encode($data, JSON_PRETTY_PRINT);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur lors de l'encodage JSON: " . json_last_error_msg());
    }
    return $json;
}

function decode_json($json)
{
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur lors du décodage JSON: " . json_last_error_msg());
    }
    return $data;
}

function readJsonFile($filePath)
{
    if (!file_exists($filePath)) {
        return null;
    }
    $json = file_get_contents($filePath);
    return json_decode($json, true);
}

function writeJsonFile($filePath, $data)
{
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT)) !== false;
}