<?php

require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request.");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    sendResponse(false, "Invalid information received.");
}

$name = trim($data["name"] ?? "");
$email = strtolower(trim($data["email"] ?? ""));
$password = $data["password"] ?? "";
$confirmPassword = $data["confirmPassword"] ?? "";

// Validate fields
if (
    $name === "" ||
    $email === "" ||
    $password === "" ||
    $confirmPassword === ""
) {
    sendResponse(false, "Please complete all fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(false, "Please enter a valid email address.");
}

if (strlen($password) < 8) {
    sendResponse(false, "Password must be at least 8 characters.");
}

if ($password !== $confirmPassword) {
    sendResponse(false, "Passwords do not match.");
}

// Get existing users
$users = getUsers();

// Check duplicate email
foreach ($users as $user) {

    if ($user["email"] === $email) {

        sendResponse(false, "This email is already registered.");
    }
}

// Create new account
$newUser = [

    "id" => bin2hex(random_bytes(8)),

    "name" => $name,

    "email" => $email,

    "password" => password_hash(
        $password,
        PASSWORD_DEFAULT
    ),

    "phone" => "",

    "notifications" => true

];

// Save account
$users[] = $newUser;

if (!saveUsers($users)) {
    sendResponse(
        false,
        "Unable to save your account to PHP/data/user.json. Check that the folder and file in the copy served by Apache are writable, then check the Apache/PHP error log for details.",
        [],
        500
    );
}

session_regenerate_id(true);
$_SESSION["user_id"] = $newUser["id"];
$_SESSION["user_name"] = $newUser["name"];
$_SESSION["user_email"] = $newUser["email"];
$_SESSION["user_phone"] = $newUser["phone"];
$_SESSION["notifications"] = $newUser["notifications"];

sendResponse(
    true,
    "Your account has been created successfully!"
);

?>