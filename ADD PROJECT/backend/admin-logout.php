<?php

session_start();

header("Content-Type: application/json");


/*
    Check whether an admin session exists
*/

if (
    !isset($_SESSION["user_id"]) ||
    !isset($_SESSION["role"]) ||
    $_SESSION["role"] !== "admin"
) {

    echo json_encode([
        "success" => false,
        "message" => "No active admin session."
    ]);

    exit;
}


/*
    Remove all session data
*/

$_SESSION = [];


/*
    Delete session cookie
*/

if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        "",
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}


/*
    Destroy session
*/

session_destroy();


/*
    Return response
*/

echo json_encode([
    "success" => true,
    "message" => "Admin logged out successfully."
]);

?>