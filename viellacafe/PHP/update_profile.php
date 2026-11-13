<?php

require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request.");
}

if (!isset($_SESSION["user_id"])) {
    sendResponse(false, "Please log in first.");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    sendResponse(false, "Invalid information received.");
}

$name = trim($data["name"] ?? "");
$phone = trim($data["phone"] ?? "");
$notifications = $data["notifications"] ?? false;

if ($name === "") {
    sendResponse(false, "Your name cannot be empty.");
}

if (!is_bool($notifications)) {
    sendResponse(false, "Invalid notification preference.");
}

$users = getUsers();

$found = false;

foreach ($users as &$user) {

    if ($user["id"] === $_SESSION["user_id"]) {

        $user["name"] = $name;
        $user["phone"] = $phone;
        $user["notifications"] = $notifications;

        $found = true;

        break;
    }
}

unset($user);

if (!$found) {
    sendResponse(false, "Account could not be found.");
}

if (!saveUsers($users)) {
    sendResponse(false, "Unable to update your profile.");
}

// Update session information
$_SESSION["user_name"] = $name;
$_SESSION["user_phone"] = $phone;
$_SESSION["notifications"] = $notifications;

sendResponse(true, "Your profile has been updated!");

?>