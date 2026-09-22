<?php

session_start();

require_once "db.php";


/*
    Check whether the student is logged in
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


/*
    Return JSON
*/

header("Content-Type: application/json");


/*
    Get logged-in user ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get user information
*/

$sql = "
    SELECT
        user_id,
        full_name,
        email,
        phone,
        course,
        created_at
    FROM users
    WHERE user_id = ?
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


$stmt->bind_param(
    "i",
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();


/*
    Check whether user exists
*/

if ($result->num_rows !== 1) {

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "User profile not found."
    ]);

    exit;
}


$user = $result->fetch_assoc();


/*
    Return profile
*/

echo json_encode([

    "success" => true,

    "profile" => [

        "user_id" =>
            $user["user_id"],

        "full_name" =>
            $user["full_name"],

        "email" =>
            $user["email"],

        "phone" =>
            $user["phone"],

        "course" =>
            $user["course"],

        "created_at" =>
            $user["created_at"]

    ]

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>