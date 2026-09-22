<?php

session_start();

require_once "admin-auth.php";
require_once "db.php";

header("Content-Type: application/json");


/*
    Get all registered students
*/

$sql = "
    SELECT
        user_id,
        full_name,
        email,
        course,
        created_at
    FROM users
    WHERE role = 'student'
    ORDER BY created_at DESC
";


$result = $conn->query($sql);


if (!$result) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to load students."
    ]);

    exit;
}


$students = [];


/*
    Fetch students
*/

while ($row = $result->fetch_assoc()) {

    $students[] = $row;

}


/*
    Return student list
*/

echo json_encode([

    "success" => true,

    "students" => $students,

    "total" => count($students)

]);


/*
    Close connection
*/

$conn->close();

?>