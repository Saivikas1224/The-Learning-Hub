<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check login
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
    Get logged-in student
*/

$user_id = $_SESSION["user_id"];


/*
    Get AI chat history
*/

$sql = "
    SELECT
        chat_id,
        question,
        answer,
        created_at
    FROM ai_chats
    WHERE user_id = ?
    ORDER BY created_at DESC
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


$chats = [];


/*
    Fetch chat history
*/

while ($row = $result->fetch_assoc()) {

    $chats[] = $row;

}


/*
    Return chat history
*/

echo json_encode([

    "success" => true,

    "chats" => $chats,

    "total" => count($chats)

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>  