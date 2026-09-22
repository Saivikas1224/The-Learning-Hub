<?php

session_start();

require_once "admin-auth.php";
require_once "db.php";

header("Content-Type: application/json");


/*
    --------------------------------------------------
    1. Total Students
    --------------------------------------------------
*/

$student_sql = "
    SELECT COUNT(*) AS total_students
    FROM users
    WHERE role = 'student'
";

$student_result = $conn->query($student_sql);

$total_students = 0;

if ($student_result) {

    $row = $student_result->fetch_assoc();

    $total_students =
        (int) $row["total_students"];
}


/*
    --------------------------------------------------
    2. Total Courses
    --------------------------------------------------
*/

$course_sql = "
    SELECT COUNT(*) AS total_courses
    FROM courses
    WHERE status = 'active'
";

$course_result = $conn->query($course_sql);

$total_courses = 0;

if ($course_result) {

    $row = $course_result->fetch_assoc();

    $total_courses =
        (int) $row["total_courses"];
}


/*
    --------------------------------------------------
    3. Total Enrollments
    --------------------------------------------------
*/

$enrollment_sql = "
    SELECT COUNT(*) AS total_enrollments
    FROM enrollments
    WHERE status = 'active'
";

$enrollment_result =
    $conn->query($enrollment_sql);

$total_enrollments = 0;

if ($enrollment_result) {

    $row =
        $enrollment_result->fetch_assoc();

    $total_enrollments =
        (int) $row["total_enrollments"];
}


/*
    --------------------------------------------------
    4. Total Quiz Attempts
    --------------------------------------------------
*/

$quiz_sql = "
    SELECT COUNT(*) AS total_quiz_attempts
    FROM quiz_results
";

$quiz_result =
    $conn->query($quiz_sql);

$total_quiz_attempts = 0;

if ($quiz_result) {

    $row =
        $quiz_result->fetch_assoc();

    $total_quiz_attempts =
        (int) $row["total_quiz_attempts"];
}


/*
    --------------------------------------------------
    5. Total Notes
    --------------------------------------------------
*/

$notes_sql = "
    SELECT COUNT(*) AS total_notes
    FROM notes
";

$notes_result =
    $conn->query($notes_sql);

$total_notes = 0;

if ($notes_result) {

    $row =
        $notes_result->fetch_assoc();

    $total_notes =
        (int) $row["total_notes"];
}


/*
    --------------------------------------------------
    6. Total Study Plans
    --------------------------------------------------
*/

$study_sql = "
    SELECT COUNT(*) AS total_study_plans
    FROM study_plans
";

$study_result =
    $conn->query($study_sql);

$total_study_plans = 0;

if ($study_result) {

    $row =
        $study_result->fetch_assoc();

    $total_study_plans =
        (int) $row["total_study_plans"];
}


/*
    --------------------------------------------------
    7. Contact Messages
    --------------------------------------------------
*/

$contact_sql = "
    SELECT COUNT(*) AS total_messages
    FROM contact_messages
";

$contact_result =
    $conn->query($contact_sql);

$total_messages = 0;

if ($contact_result) {

    $row =
        $contact_result->fetch_assoc();

    $total_messages =
        (int) $row["total_messages"];
}


/*
    --------------------------------------------------
    8. AI Conversations
    --------------------------------------------------
*/

$ai_sql = "
    SELECT COUNT(*) AS total_ai_chats
    FROM ai_chats
";

$ai_result =
    $conn->query($ai_sql);

$total_ai_chats = 0;

if ($ai_result) {

    $row =
        $ai_result->fetch_assoc();

    $total_ai_chats =
        (int) $row["total_ai_chats"];
}


/*
    --------------------------------------------------
    Return Dashboard Statistics
    --------------------------------------------------
*/

echo json_encode([

    "success" => true,

    "statistics" => [

        "total_students" =>
            $total_students,

        "total_courses" =>
            $total_courses,

        "total_enrollments" =>
            $total_enrollments,

        "total_quiz_attempts" =>
            $total_quiz_attempts,

        "total_notes" =>
            $total_notes,

        "total_study_plans" =>
            $total_study_plans,

        "total_messages" =>
            $total_messages,

        "total_ai_chats" =>
            $total_ai_chats

    ]

]);


/*
    Close database connection
*/

$conn->close();

?>