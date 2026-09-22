

<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Get all active courses
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
    WHERE status = 'active'
    ORDER BY created_at DESC
";


$result = $conn->query($sql);


if (!$result) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to load courses."
    ]);

    $conn->close();

    exit;
}


/*
    Store courses
*/

$courses = [];


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