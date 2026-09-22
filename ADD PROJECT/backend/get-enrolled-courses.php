<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


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
    Get logged-in student's ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get enrolled courses
*/

$sql = "
    SELECT
        e.enrollment_id,
        e.course_id,
        e.enrolled_at,
        e.status,

        c.course_name,
        c.description,
        c.category,
        c.instructor,
        c.duration,
        c.image

    FROM enrollments e

    INNER JOIN courses c
        ON e.course_id = c.course_id

    WHERE e.user_id = ?
    AND e.status = 'active'

    ORDER BY e.enrolled_at DESC
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
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();


$courses = [];


/*
    Fetch enrolled courses
*/

while ($row = $result->fetch_assoc()) {

    $courses[] = $row;

}


/*
    Return courses
*/

echo json_encode([
    "success" => true,
    "courses" => $courses,
    "total" => count($courses)
]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>