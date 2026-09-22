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
    Get course ID
*/

$course_id = intval(
    $_POST["course_id"] ?? 0
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
    Get current course status
*/

$sql = "
    SELECT status
    FROM courses
    WHERE course_id = ?
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


$stmt->bind_param(
    "i",
    $course_id
);

$stmt->execute();

$stmt->bind_result($current_status);


/*
    Check whether course exists
*/

if (!$stmt->fetch()) {

    echo json_encode([
        "success" => false,
        "message" => "Course not found."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}

$stmt->close();


/*
    Toggle status
*/

if ($current_status === "active") {

    $new_status = "inactive";

} else {

    $new_status = "active";

}


/*
    Update status
*/

$update_sql = "
    UPDATE courses
    SET status = ?
    WHERE course_id = ?
";

$update_stmt =
    $conn->prepare($update_sql);


if (!$update_stmt) {

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

$update_stmt->bind_param(
    "si",
    $new_status,
    $course_id
);


/*
    Execute update
*/

if ($update_stmt->execute()) {

    echo json_encode([

        "success" => true,

        "message" =>
            "Course status updated successfully.",

        "course_id" =>
            $course_id,

        "status" =>
            $new_status

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to update course status."
    ]);
}


/*
    Close connection
*/

$update_stmt->close();

$conn->close();

?>