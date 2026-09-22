<?php

session_start();

header("Content-Type: application/json");


/*
    Check whether the user is logged in
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Admin login required."
    ]);

    exit;
}


/*
    Check admin role
*/

if (
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {

    http_response_code(403);

    echo json_encode([
        "success" => false,
        "message" => "Access denied. Admin privileges required."
    ]);

    exit;
}


/*
    Admin is authenticated
*/

echo json_encode([
    "success" => true,
    "message" => "Admin authenticated.",
    "admin" => [
        "user_id" => $_SESSION["user_id"],
        "full_name" => $_SESSION["full_name"] ?? "",
        "email" => $_SESSION["email"] ?? "",
        "role" => $_SESSION["role"]
    ]
]);

?>
