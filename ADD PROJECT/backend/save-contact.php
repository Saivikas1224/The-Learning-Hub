<?php

require_once "db.php";

header("Content-Type: application/json");


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
    Get form data
*/

$name = trim(
    $_POST["name"] ?? ""
);

$email = trim(
    $_POST["email"] ?? ""
);

$subject = trim(
    $_POST["subject"] ?? ""
);

$message = trim(
    $_POST["message"] ?? ""
);


/*
    Validate required fields
*/

if (
    empty($name) ||
    empty($email) ||
    empty($subject) ||
    empty($message)
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill in all fields."
    ]);

    exit;
}


/*
    Validate email
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);

    exit;
}


/*
    Limit input length
*/

if (strlen($name) > 100) {

    echo json_encode([
        "success" => false,
        "message" => "Name is too long."
    ]);

    exit;
}


if (strlen($email) > 150) {

    echo json_encode([
        "success" => false,
        "message" => "Email is too long."
    ]);

    exit;
}


if (strlen($subject) > 200) {

    echo json_encode([
        "success" => false,
        "message" => "Subject is too long."
    ]);

    exit;
}


if (strlen($message) > 5000) {

    echo json_encode([
        "success" => false,
        "message" => "Message is too long."
    ]);

    exit;
}


/*
    Insert contact message
*/

$sql = "
    INSERT INTO contact_messages
    (
        name,
        email,
        subject,
        message,
        created_at
    )
    VALUES
    (
        ?,
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


/*
    Bind values
*/

$stmt->bind_param(
    "ssss",
    $name,
    $email,
    $subject,
    $message
);


/*
    Save message
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Your message has been sent successfully."
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to send your message."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>