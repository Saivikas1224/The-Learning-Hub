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
    Get logged-in student
*/

$user_id = $_SESSION["user_id"];


/*
    Get chat data
*/

$question = trim(
    $_POST["question"] ?? ""
);

$answer = trim(
    $_POST["answer"] ?? ""
);


/*
    Validate data
*/

if (
    empty($question) ||
    empty($answer)
) {

    echo json_encode([
        "success" => false,
        "message" => "Question and answer are required."
    ]);

    exit;
}


/*
    Limit very large messages
*/

if (
    strlen($question) > 5000 ||
    strlen($answer) > 10000
) {

    echo json_encode([
        "success" => false,
        "message" => "Message is too long."
    ]);

    exit;
}


/*
    Save AI conversation
*/

$sql = "
    INSERT INTO ai_chats
    (
        user_id,
        question,
        answer,
        created_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        NOW()
    )
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
    "iss",
    $user_id,
    $question,
    $answer
);


/*
    Execute
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "AI chat saved successfully.",
        "chat_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to save AI chat."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>