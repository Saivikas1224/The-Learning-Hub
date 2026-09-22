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

$plan_id = intval(
    $_POST["plan_id"] ?? 0
);

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

$status = trim(
    $_POST["status"] ?? "pending"
);

$notes = trim(
    $_POST["notes"] ?? ""
);


/*
    Validate plan ID
*/

if ($plan_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid study plan ID."
    ]);

    exit;
}


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
        "message" => "Invalid study date."
    ]);

    exit;
}


/*
    Validate time
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
        "message" => "Invalid time format."
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
    Calculate duration
*/

if ($duration <= 0) {

    $start_minutes =
        ($start_object->format("H") * 60)
        + intval($start_object->format("i"));

    $end_minutes =
        ($end_object->format("H") * 60)
        + intval($end_object->format("i"));

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
    Validate status
*/

$allowed_statuses = [
    "pending",
    "completed",
    "skipped"
];


if (
    !in_array(
        $status,
        $allowed_statuses,
        true
    )
) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Invalid study plan status."
    ]);

    exit;
}


/*
    Check whether plan belongs
    to logged-in student
*/

$check_sql = "
    SELECT plan_id
    FROM study_plans
    WHERE plan_id = ?
    AND user_id = ?
    LIMIT 1
";


$check_stmt =
    $conn->prepare($check_sql);


if (!$check_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

    exit;
}


$check_stmt->bind_param(
    "ii",
    $plan_id,
    $user_id
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Study plan not found."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}


$check_stmt->close();


/*
    Update study plan
*/

$sql = "
    UPDATE study_plans

    SET
        subject = ?,
        topic = ?,
        study_date = ?,
        start_time = ?,
        end_time = ?,
        duration = ?,
        priority = ?,
        status = ?,
        notes = ?

    WHERE plan_id = ?
    AND user_id = ?
";


$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

    exit;
}


/*
    Bind values
*/

$stmt->bind_param(
    "sssssisssii",
    $subject,
    $topic,
    $study_date,
    $start_time,
    $end_time,
    $duration,
    $priority,
    $status,
    $notes,
    $plan_id,
    $user_id
);


/*
    Execute update
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Study plan updated successfully.",

        "plan_id" =>
            $plan_id

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to update study plan."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>