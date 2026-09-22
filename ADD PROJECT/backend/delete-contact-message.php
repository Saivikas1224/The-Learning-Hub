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
    Allow only POST requests
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);

    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);

    exit;
}


/*
    Get message ID
*/

$message_id = intval(
    $_POST["message_id"] ?? 0
);


/*
    Validate message ID
*/

if ($message_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid message ID."
    ]);

    exit;
}


/*
    Delete contact message
*/

$sql = "
    DELETE FROM contact_messages
    WHERE message_id = ?
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
    $message_id
);


/*
    Execute deletion
*/

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "Contact message deleted successfully."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Message not found."
        ]);

    }

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete contact message."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>