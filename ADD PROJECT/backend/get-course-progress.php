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
    Get course ID
*/

$course_id = intval(
    $_GET["course_id"] ?? 0
);


/*
    Validate course ID
*/

if ($course_id <= 0) {

    http_response_code(400);

    echo json_encode([
        "success" => false,
        "message" => "Invalid course ID."
    ]);

    exit;
}


/*
    Get student's course progress
*/

$sql = "
    SELECT
        ce.enrollment_id,
        ce.course_id,
        ce.progress,
        ce.status,
        ce.enrolled_at,

        c.course_name,
        c.category,
        c.instructor,
        c.duration,
        c.image

    FROM course_enrollments ce

    INNER JOIN courses c
        ON ce.course_id = c.course_id

    WHERE ce.user_id = ?
    AND ce.course_id = ?

    LIMIT 1
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
    "ii",
    $user_id,
    $course_id
);


/*
    Execute query
*/

$stmt->execute();

$result = $stmt->get_result();


/*
    Check enrollment
*/

if ($result->num_rows === 0) {

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" =>
            "You are not enrolled in this course."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}


/*
    Get progress
*/

$course = $result->fetch_assoc();


/*
    Return progress
*/

echo json_encode([

    "success" => true,

    "course" => $course

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>