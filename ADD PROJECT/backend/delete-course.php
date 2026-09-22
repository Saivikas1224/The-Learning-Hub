<?php

session_start();

require_once "admin-auth.php";
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
    Get course ID
*/

$course_id = intval(
    $_POST["course_id"] ?? 0
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
    Check whether course exists
*/

$check_sql = "
    SELECT course_id
    FROM courses
    WHERE course_id = ?
    LIMIT 1
";

$check_stmt = $conn->prepare($check_sql);


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
    "i",
    $course_id
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "message" => "Course not found."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}


$check_stmt->close();


/*
    Delete course
*/

$sql = "
    DELETE FROM courses
    WHERE course_id = ?
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
    Bind course ID
*/

$stmt->bind_param(
    "i",
    $course_id
);


/*
    Execute deletion
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Course deleted successfully.",

        "course_id" =>
            $course_id

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete course."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>