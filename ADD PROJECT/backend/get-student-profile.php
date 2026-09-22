<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check student login
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


$user_id = intval($_SESSION["user_id"]);


/*
    Get student profile
*/

$sql = "
    SELECT
        user_id,
        name,
        email,
        created_at
    FROM users
    WHERE user_id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

    exit;
}


/*
    Bind user ID
*/

$stmt->bind_param(
    "i",
    $user_id
);


/*
    Execute query
*/

$stmt->execute();

$result = $stmt->get_result();


/*
    Check student
*/

if ($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Student profile not found."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}


/*
    Get profile
*/

$profile = $result->fetch_assoc();


/*
    Return profile
*/

echo json_encode([

    "success" => true,

    "profile" => $profile

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>