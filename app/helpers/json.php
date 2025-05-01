<?php

function encode_json($data) {
    $json = json_encode($data);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur lors de l'encodage JSON: " . json_last_error_msg());
    }
    return $json;
}

function decode_json($json) {
    $data = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Erreur lors du décodage JSON: " . json_last_error_msg());
    }
    return $data;
}