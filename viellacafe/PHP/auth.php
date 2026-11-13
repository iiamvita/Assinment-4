<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dataDirectory = __DIR__ . "/data";
$dataFile = $dataDirectory . "/user.json";

function storageError($details) {
    error_log("Viella account storage error: " . $details);
    sendResponse(
        false,
        "Account storage is unavailable. Check that PHP can write to PHP/data.",
        [],
        500
    );
}

function ensureUserStore() {
    global $dataDirectory, $dataFile;

    if (!is_dir($dataDirectory) && !@mkdir($dataDirectory, 0755, true) && !is_dir($dataDirectory)) {
        storageError("Could not create " . $dataDirectory);
    }

    if (!file_exists($dataFile)) {
        $handle = @fopen($dataFile, "x");
        if ($handle === false) {
            if (!file_exists($dataFile)) {
                storageError("Could not create " . $dataFile);
            }
        } else {
            fclose($handle);
            if (@file_put_contents($dataFile, "[]", LOCK_EX) === false) {
                storageError("Could not initialize " . $dataFile);
            }
        }
    }
}

// Read registered users
function getUsers() {
    global $dataFile;

    ensureUserStore();
    $json = @file_get_contents($dataFile);
    if ($json === false) {
        storageError("Could not read " . $dataFile);
    }
    $users = json_decode($json, true);

    if (!is_array($users) || json_last_error() !== JSON_ERROR_NONE) {
        storageError("Invalid JSON in " . $dataFile . ": " . json_last_error_msg());
    }

    return $users;
}

// Save registered users
function saveUsers($users) {
    global $dataFile;

    ensureUserStore();
    $json = json_encode($users, JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE);
    if ($json === false) {
        error_log("Viella account storage error: Could not encode account data: " . json_last_error_msg());
        return false;
    }

    $directory = dirname($dataFile);
    if (!is_writable($directory)) {
        error_log("Viella account storage error: PHP cannot write to " . $directory);
        return false;
    }

    if (file_exists($dataFile) && !is_writable($dataFile)) {
        error_log("Viella account storage error: PHP cannot write to " . $dataFile);
        return false;
    }

    if (@file_put_contents($dataFile, $json, LOCK_EX) === false) {
        $error = error_get_last();
        error_log(
            "Viella account storage error: Could not write " . $dataFile .
            ($error && isset($error["message"]) ? ": " . $error["message"] : "")
        );
        return false;
    }

    return true;
}

// Return JSON response
function sendResponse($success, $message, $extra = [], $statusCode = 200) {
    http_response_code($statusCode);
    header("Content-Type: application/json; charset=utf-8");

    $response = json_encode(array_merge([
        "success" => $success,
        "message" => $message
    ], $extra), JSON_INVALID_UTF8_SUBSTITUTE);

    if ($response === false) {
        http_response_code(500);
        echo '{"success":false,"message":"Unable to create a response."}';
        exit;
    }

    echo $response;
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