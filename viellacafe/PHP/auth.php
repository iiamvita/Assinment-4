<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dataDirectory = __DIR__ . "/data";
$dataFile = $dataDirectory . "/users.json";

// Create data folder if it doesn't exist
if (!is_dir($dataDirectory)) {
    mkdir($dataDirectory, 0755, true);
}

// Create JSON file if it doesn't exist
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, "[]");
}

// Read registered users
function getUsers() {

    global $dataFile;

    $json = file_get_contents($dataFile);

    $users = json_decode($json, true);

    return is_array($users) ? $users : [];
}

// Save registered users
function saveUsers($users) {

    global $dataFile;

    $json = json_encode(
        $users,
        JSON_PRETTY_PRINT
    );

    return file_put_contents(
        $dataFile,
        $json,
        LOCK_EX
    ) !== false;
}

// Return JSON response
function sendResponse($success, $message, $extra = []) {

    header("Content-Type: application/json");

    echo json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $extra));

    exit;
}

// Check whether customer is logged in
function requireLogin() {

    if (!isset($_SESSION["user_id"])) {

        header("Location: ../HTML/account.php");
        exit;
    }
}
?>