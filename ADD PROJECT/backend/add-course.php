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
    Limit input length
*/

if (strlen($course_name) > 150) {

    echo json_encode([
        "success" => false,
        "message" => "Course name is too long."
    ]);

    exit;
}


if (strlen($description) > 5000) {

    echo json_encode([
        "success" => false,
        "message" => "Description is too long."
    ]);

    exit;
}


if (strlen($category) > 100) {

    echo json_encode([
        "success" => false,
        "message" => "Category is too long."
    ]);

    exit;
}


if (strlen($instructor) > 150) {

    echo json_encode([
        "success" => false,
        "message" => "Instructor name is too long."
    ]);

    exit;
}


if (strlen($duration) > 100) {

    echo json_encode([
        "success" => false,
        "message" => "Duration is too long."
    ]);

    exit;
}


/*
    Check whether course already exists
*/

$check_sql = "
    SELECT course_id
    FROM courses
    WHERE course_name = ?
    LIMIT 1
";

$check_stmt =
    $conn->prepare($check_sql);

$check_stmt->bind_param(
    "s",
    $course_name
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows > 0) {

    echo json_encode([
        "success" => false,
        "message" => "A course with this name already exists."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}

$check_stmt->close();


/*
    Add course
*/

$sql = "
    INSERT INTO courses
    (
        course_name,
        description,
        category,
        instructor,
        duration,
        image,
        status,
        created_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        'active',
        NOW()
    )
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
    Bind course information
*/

$stmt->bind_param(
    "ssssss",
    $course_name,
    $description,
    $category,
    $instructor,
    $duration,
    $image
);


/*
    Save course
*/

if ($stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Course added successfully.",

        "course_id" =>
            $stmt->insert_id

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to add course."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>