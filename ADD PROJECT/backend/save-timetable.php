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
    Get logged-in user ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get timetable data
*/

$subject = trim($_POST["subject"] ?? "");

$topic = trim($_POST["topic"] ?? "");

$day = trim($_POST["day"] ?? "");

$start_time = trim($_POST["start_time"] ?? "");

$end_time = trim($_POST["end_time"] ?? "");

$location = trim($_POST["location"] ?? "");


/*
    Validate required fields
*/

if (
    empty($subject) ||
    empty($day)
) {

    echo json_encode([
        "success" => false,
        "message" => "Subject and day are required."
    ]);

    exit;
}


/*
    Insert timetable entry
*/

$sql = "
    INSERT INTO timetable
    (
        user_id,
        subject,
        topic,
        day,
        start_time,
        end_time,
        location
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
    $day,
    $start_time,
    $end_time,
    $location
);


/*
    Save timetable entry
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Timetable saved successfully.",
        "timetable_id" => $stmt->insert_id
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to save timetable."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>