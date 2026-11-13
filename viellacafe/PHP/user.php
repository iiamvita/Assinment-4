<?php

require_once "auth.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {

    echo json_encode([
        "logged_in" => false
    ]);

    exit;
}

$users = getUsers();

foreach ($users as $user) {

    if ($user["id"] === $_SESSION["user_id"]) {

        echo json_encode([

            "logged_in" => true,

            "full_name" => $user["name"],

            "email" => $user["email"],

            "phone" => $user["phone"] ?? "",

            "notifications" => $user["notifications"] ?? true

        ]);

        exit;
    }
}

session_destroy();

echo json_encode([
    "logged_in" => false
]);

?>