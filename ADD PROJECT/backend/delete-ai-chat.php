<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


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
    Get logged-in user's ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get chat ID
*/

$chat_id = intval(
    $_POST["chat_id"] ?? 0
);


/*
    Validate chat ID
*/

if ($chat_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid chat ID."
    ]);

    exit;
}


/*
    Delete only the chat belonging
    to the logged-in student
*/

$sql = "
    DELETE FROM ai_chats
    WHERE chat_id = ?
    AND user_id = ?
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
    "ii",
    $chat_id,
    $user_id
);


/*
    Execute deletion
*/

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "AI chat deleted successfully."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Chat not found."
        ]);

    }

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete AI chat."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>
