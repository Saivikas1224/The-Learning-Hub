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
        "message" => "Please login to enroll in a course."
    ]);

    exit;
}


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
    Get user and course
*/

$user_id = $_SESSION["user_id"];

$course_id = intval(
    $_POST["course_id"] ?? 0
);


/*
    Validate course ID
*/

if ($course_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid course."
    ]);

    exit;
}


/*
    Check whether course exists
*/

$course_sql = "
    SELECT course_id
    FROM courses
    WHERE course_id = ?
    AND status = 'active'
";

$course_stmt =
    $conn->prepare($course_sql);

$course_stmt->bind_param(
    "i",
    $course_id
);

$course_stmt->execute();

$course_result =
    $course_stmt->get_result();


if ($course_result->num_rows !== 1) {

    echo json_encode([
        "success" => false,
        "message" => "Course not found."
    ]);

    $course_stmt->close();
    $conn->close();

    exit;
}

$course_stmt->close();


/*
    Check whether student is
    already enrolled
*/

$check_sql = "
    SELECT enrollment_id
    FROM enrollments
    WHERE user_id = ?
    AND course_id = ?
";

$check_stmt =
    $conn->prepare($check_sql);

$check_stmt->bind_param(
    "ii",
    $user_id,
    $course_id
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows > 0) {

    $check_stmt->close();
    $conn->close();

    echo json_encode([
        "success" => false,
        "message" => "You are already enrolled in this course."
    ]);

    exit;
}

$check_stmt->close();


/*
    Enroll student
*/

$sql = "
    INSERT INTO enrollments
    (
        user_id,
        course_id,
        enrolled_at,
        status
    )
    VALUES
    (
        ?,
        ?,
        NOW(),
        'active'
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


$stmt->bind_param(
    "ii",
    $user_id,
    $course_id
);


/*
    Save enrollment
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Course enrolled successfully.",
        "enrollment_id" => $stmt->insert_id
    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to enroll in course."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>