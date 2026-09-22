<?php

require_once "db.php";

header("Content-Type: application/json");


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
    Get course information
*/

$sql = "
    SELECT
        course_id,
        course_name,
        description,
        category,
        instructor,
        duration,
        image,
        status
    FROM courses
    WHERE course_id = ?
    AND status = 'active'
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
    "i",
    $course_id
);


$stmt->execute();


$result = $stmt->get_result();


/*
    Check course
*/

if ($result->num_rows !== 1) {

    http_response_code(404);

    echo json_encode([
        "success" => false,
        "message" => "Course not found."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}


$course = $result->fetch_assoc();


/*
    Return course
*/

echo json_encode([
    "success" => true,
    "course" => $course
]);


$stmt->close();

$conn->close();

?>