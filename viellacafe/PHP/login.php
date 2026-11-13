<?php

require_once "auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    sendResponse(false, "Invalid request.");
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    sendResponse(false, "Invalid login information.");
}

$email = strtolower(trim($data["email"] ?? ""));
$password = $data["password"] ?? "";

if ($email === "" || $password === "") {
    sendResponse(false, "Please enter your email and password.");
}

$users = getUsers();

foreach ($users as $user) {

    if (
        $user["email"] === $email &&
        password_verify($password, $user["password"])
    ) {

        // Prevent session fixation
        session_regenerate_id(true);

        // Store customer information in session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["user_phone"] = $user["phone"] ?? "";
        $_SESSION["notifications"] = $user["notifications"] ?? true;

        sendResponse(
            true,
            "Login successful!",
            [
                "name" => $user["name"]
            ]
        );
    }
}

sendResponse(false, "Incorrect email or password.");

?>