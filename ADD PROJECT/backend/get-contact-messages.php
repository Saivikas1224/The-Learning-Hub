<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check whether the user is logged in
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
    Get contact messages
*/

$sql = "
    SELECT
        message_id,
        name,
        email,
        subject,
        message,
        created_at
    FROM contact_messages
    ORDER BY created_at DESC
";


$result = $conn->query($sql);


if (!$result) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to load contact messages."
    ]);

    exit;
}


$messages = [];


/*
    Fetch messages
*/

while ($row = $result->fetch_assoc()) {

    $messages[] = $row;

}


/*
    Return messages
*/

echo json_encode([

    "success" => true,

    "messages" => $messages,

    "total" => count($messages)

]);


/*
    Close connection
*/

$conn->close();

?>