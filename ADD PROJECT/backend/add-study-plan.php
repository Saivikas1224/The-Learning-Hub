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


$user_id = intval($_SESSION["user_id"]);


/*
    Get study plan information
*/

$subject = trim(
    $_POST["subject"] ?? ""
);

$topic = trim(
    $_POST["topic"] ?? ""
);

$study_date = trim(
    $_POST["study_date"] ?? ""
);

$start_time = trim(
    $_POST["start_time"] ?? ""
);

$end_time = trim(
    $_POST["end_time"] ?? ""
);

$duration = intval(
    $_POST["duration"] ?? 0
);

$priority = trim(
    $_POST["priority"] ?? "medium"
);

$notes = trim(
    $_POST["notes"] ?? ""
);


/*
    Validate required fields
*/

if (
    empty($subject) ||
    empty($topic) ||
    empty($study_date) ||
    empty($start_time) ||
    empty($end_time)
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Please fill in all required fields."
    ]);

    exit;
}


/*
    Validate date
*/

$date_object =
    DateTime::createFromFormat(
        "Y-m-d",
        $study_date
    );


if (
    !$date_object ||
    $date_object->format("Y-m-d")
    !== $study_date
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid study date."
    ]);

    exit;
}


/*
    Validate time format
*/

$start_object =
    DateTime::createFromFormat(
        "H:i",
        $start_time
    );

$end_object =
    DateTime::createFromFormat(
        "H:i",
        $end_time
    );


if (
    !$start_object ||
    !$end_object
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid time format."
    ]);

    exit;
}


/*
    Check time order
*/

if ($start_time >= $end_time) {

    echo json_encode([
        "success" => false,
        "message" =>
            "End time must be after start time."
    ]);

    exit;
}


/*
    Calculate duration automatically
    if not provided
*/

if ($duration <= 0) {

    $start_minutes =
        ($start_object->format("H") * 60)
        + $start_object->format("i");

    $end_minutes =
        ($end_object->format("H") * 60)
        + $end_object->format("i");

    $duration =
        $end_minutes - $start_minutes;

}


/*
    Validate duration
*/

if ($duration <= 0) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid study duration."
    ]);

    exit;
}


/*
    Validate priority
*/

$allowed_priorities = [
    "low",
    "medium",
    "high"
];


if (
    !in_array(
        $priority,
        $allowed_priorities,
        true
    )
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid priority."
    ]);

    exit;
}


/*
    Add study plan
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
        duration,
        priority,
        status,
        notes,
        created_at
    )

    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        'pending',
        ?,
        NOW()
    )
";


$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to prepare database query."
    ]);

    $conn->close();

    exit;
}


/*
    Bind values
*/

$stmt->bind_param(
    "isssssiss",
    $user_id,
    $subject,
    $topic,
    $study_date,
    $start_time,
    $end_time,
    $duration,
    $priority,
    $notes
);


/*
    Execute insert
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Study plan added successfully.",

        "plan_id" =>
            $stmt->insert_id

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to add study plan."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>