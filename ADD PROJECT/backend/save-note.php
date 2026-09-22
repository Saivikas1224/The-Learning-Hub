<?php

session_start();

require_once "db.php";


/*
    Check whether the student is logged in
*/

if (!isset($_SESSION["user_id"])) {

    die("Please login first.");

}


/*
    Allow only POST requests
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    die("Invalid request.");

}


/*
    Get form data
*/

$user_id = $_SESSION["user_id"];

$title = trim($_POST["title"] ?? "");

$subject = trim($_POST["subject"] ?? "");

$content = trim($_POST["content"] ?? "");



/*
    Validate data
*/

if (
    empty($title) ||
    empty($content)
) {

    die("Title and note content are required.");

}



/*
    Insert note into database
*/

$sql = "
    INSERT INTO notes
    (
        user_id,
        title,
        subject,
        content,
        created_at,
        updated_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        NOW(),
        NOW()
    )
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Database error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "isss",
    $user_id,
    $title,
    $subject,
    $content
);



/*
    Save note
*/

if ($stmt->execute()) {

    echo "Note saved successfully.";

}

else {

    echo "Failed to save note.";

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>