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
    Return JSON response
*/

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
    Get logged-in user
*/

$user_id = $_SESSION["user_id"];


/*
    Get form data
*/

$subject = trim($_POST["subject"] ?? "");

$topic = trim($_POST["topic"] ?? "");

$study_date = trim($_POST["study_date"] ?? "");

$start_time = trim($_POST["start_time"] ?? "");

$end_time = trim($_POST["end_time"] ?? "");

$status = trim($_POST["status"] ?? "Pending");


/*
    Validate required fields
*/

if (
    empty($subject) ||
    empty($study_date)
) {

    echo json_encode([
        "success" => false,
        "message" => "Subject and study date are required."
    ]);

    exit;
}


/*
    Insert study plan
*/

$sql = "
    INSERT INTO study_plans
    (
        user_id,
        subject,
        topic,
        study_date,
        start_time,
        end_time,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
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
    "issssss",
    $user_id,
    $subject,
    $topic,
    $study_date,
    $start_time,
    $end_time,
    $status
);


/*
    Execute
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Study plan saved successfully.",
        "plan_id" => $stmt->insert_id
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to save study plan."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>