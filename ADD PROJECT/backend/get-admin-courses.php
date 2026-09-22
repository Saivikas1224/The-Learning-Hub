<?php

session_start();

require_once "admin-auth.php";
require_once "db.php";

header("Content-Type: application/json");


/*
    Get all courses
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
        status,
        created_at
    FROM courses
    ORDER BY created_at DESC
";


$result = $conn->query($sql);


if (!$result) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to load courses."
    ]);

    exit;
}


$courses = [];


/*
    Fetch courses
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

$conn->close();

?>