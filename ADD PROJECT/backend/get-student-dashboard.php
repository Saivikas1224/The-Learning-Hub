<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check student login
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


$user_id = intval($_SESSION["user_id"]);


/*
    Get student information
*/

$user_sql = "
    SELECT
        user_id,
        name,
        email
    FROM users
    WHERE user_id = ?
    LIMIT 1
";


$user_stmt = $conn->prepare($user_sql);


if (!$user_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

    exit;
}


$user_stmt->bind_param(
    "i",
    $user_id
);

$user_stmt->execute();

$user_result =
    $user_stmt->get_result();


if ($user_result->num_rows === 0) {

    echo json_encode([
        "success" => false,
        "message" => "Student not found."
    ]);

    $user_stmt->close();
    $conn->close();

    exit;
}


$student =
    $user_result->fetch_assoc();


$user_stmt->close();


/*
    Get enrolled courses
*/

$course_sql = "
    SELECT
        ce.enrollment_id,
        ce.course_id,
        ce.progress,
        ce.status,
        ce.enrolled_at,

        c.course_name,
        c.category,
        c.instructor,
        c.duration,
        c.image

    FROM course_enrollments ce

    INNER JOIN courses c
        ON ce.course_id = c.course_id

    WHERE ce.user_id = ?

    ORDER BY ce.enrolled_at DESC
";


$course_stmt =
    $conn->prepare($course_sql);


if (!$course_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to load courses."
    ]);

    $conn->close();

    exit;
}


$course_stmt->bind_param(
    "i",
    $user_id
);

$course_stmt->execute();

$course_result =
    $course_stmt->get_result();


$enrolled_courses = [];


while (
    $row =
    $course_result->fetch_assoc()
) {

    $enrolled_courses[] = $row;

}


$course_stmt->close();


/*
    Calculate course statistics
*/

$total_courses =
    count($enrolled_courses);

$completed_courses = 0;

$total_progress = 0;


foreach (
    $enrolled_courses
    as $course
) {

    $progress =
        intval($course["progress"]);

    $total_progress += $progress;


    if (
        $course["status"]
        === "completed"
    ) {

        $completed_courses++;

    }

}


$average_progress = 0;


if ($total_courses > 0) {

    $average_progress =
        round(
            $total_progress /
            $total_courses
        );

}


/*
    Get recent courses
*/

$recent_courses =
    array_slice(
        $enrolled_courses,
        0,
        5
    );


/*
    Return dashboard
*/

echo json_encode([

    "success" => true,

    "student" => $student,

    "statistics" => [

        "total_courses" =>
            $total_courses,

        "completed_courses" =>
            $completed_courses,

        "average_progress" =>
            $average_progress

    ],

    "courses" =>
        $recent_courses

]);


/*
    Close connection
*/

$conn->close();

?>