<?php

require_once "db.php";


/*
    Return JSON response
*/

header("Content-Type: application/json");


/*
    Get all available courses
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
    WHERE status = 'active'
    ORDER BY course_name ASC
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
    "courses" => $courses
]);


/*
    Close connection
*/

$conn->close();

?>