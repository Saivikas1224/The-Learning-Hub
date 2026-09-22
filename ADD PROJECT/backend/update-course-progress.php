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


$user_id = intval($_SESSION["user_id"]);


/*
    Get input
*/

$course_id = intval(
    $_POST["course_id"] ?? 0
);

$progress = intval(
    $_POST["progress"] ?? -1
);


/*
    Validate course ID
*/

if ($course_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid course ID."
    ]);

    exit;
}


/*
    Validate progress
*/

if ($progress < 0 || $progress > 100) {

    echo json_encode([
        "success" => false,
        "message" =>
            "Progress must be between 0 and 100."
    ]);

    exit;
}


/*
    Check enrollment
*/

$check_sql = "
    SELECT enrollment_id
    FROM course_enrollments
    WHERE user_id = ?
    AND course_id = ?
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
    $user_id,
    $course_id
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "message" =>
            "You are not enrolled in this course."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}


$check_stmt->close();


/*
    Determine enrollment status
*/

$enrollment_status = "active";

if ($progress >= 100) {

    $progress = 100;

    $enrollment_status = "completed";
}


/*
    Update progress
*/

$sql = "
    UPDATE course_enrollments

    SET
        progress = ?,
        status = ?

    WHERE user_id = ?
    AND course_id = ?
";


$stmt = $conn->prepare($sql);


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
    "isii",
    $progress,
    $enrollment_status,
    $user_id,
    $course_id
);


/*
    Execute update
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Course progress updated successfully.",

        "course_id" =>
            $course_id,

        "progress" =>
            $progress,

        "status" =>
            $enrollment_status

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to update course progress."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>