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
    Get course details
*/

$course_id = intval(
    $_POST["course_id"] ?? 0
);

$course_name = trim(
    $_POST["course_name"] ?? ""
);

$description = trim(
    $_POST["description"] ?? ""
);

$category = trim(
    $_POST["category"] ?? ""
);

$instructor = trim(
    $_POST["instructor"] ?? ""
);

$duration = trim(
    $_POST["duration"] ?? ""
);

$image = trim(
    $_POST["image"] ?? ""
);

$status = trim(
    $_POST["status"] ?? "active"
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
    Validate required fields
*/

if (
    empty($course_name) ||
    empty($description) ||
    empty($category) ||
    empty($instructor) ||
    empty($duration)
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill in all required fields."
    ]);

    exit;
}


/*
    Validate status
*/

$allowed_statuses = [
    "active",
    "inactive"
];

if (!in_array($status, $allowed_statuses, true)) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid course status."
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
";

$check_stmt =
    $conn->prepare($check_sql);

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
    Check duplicate course name
*/

$duplicate_sql = "
    SELECT course_id
    FROM courses
    WHERE course_name = ?
    AND course_id != ?
    LIMIT 1
";

$duplicate_stmt =
    $conn->prepare($duplicate_sql);

$duplicate_stmt->bind_param(
    "si",
    $course_name,
    $course_id
);

$duplicate_stmt->execute();

$duplicate_stmt->store_result();


if ($duplicate_stmt->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "Another course already has this name."
    ]);

    $duplicate_stmt->close();
    $conn->close();

    exit;
}

$duplicate_stmt->close();


/*
    Update course
*/

$sql = "
    UPDATE courses

    SET
        course_name = ?,
        description = ?,
        category = ?,
        instructor = ?,
        duration = ?,
        image = ?,
        status = ?

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
    Bind values
*/

$stmt->bind_param(
    "sssssssi",
    $course_name,
    $description,
    $category,
    $instructor,
    $duration,
    $image,
    $status,
    $course_id
);


/*
    Execute update
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Course updated successfully.",

        "course_id" =>
            $course_id

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update course."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>