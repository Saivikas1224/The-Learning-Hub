<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check login
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


$user_id = $_SESSION["user_id"];


/*
    --------------------------------------------------
    1. Student Profile
    --------------------------------------------------
*/

$user_sql = "
    SELECT
        user_id,
        full_name,
        email,
        course
    FROM users
    WHERE user_id = ?
";

$user_stmt = $conn->prepare($user_sql);

$user_stmt->bind_param(
    "i",
    $user_id
);

$user_stmt->execute();

$user_result = $user_stmt->get_result();

$user = $user_result->fetch_assoc();


/*
    --------------------------------------------------
    2. Enrolled Courses
    --------------------------------------------------
*/

$course_sql = "
    SELECT
        e.enrollment_id,
        e.course_id,
        c.course_name,
        c.description,
        c.category,
        c.instructor,
        c.duration,
        c.image

    FROM enrollments e

    INNER JOIN courses c
        ON e.course_id = c.course_id

    WHERE e.user_id = ?
    AND e.status = 'active'

    ORDER BY e.enrolled_at DESC
";

$course_stmt = $conn->prepare($course_sql);

$course_stmt->bind_param(
    "i",
    $user_id
);

$course_stmt->execute();

$course_result =
    $course_stmt->get_result();

$courses = [];

while (
    $row =
    $course_result->fetch_assoc()
) {

    $courses[] = $row;

}


/*
    --------------------------------------------------
    3. Course Progress
    --------------------------------------------------
*/

$progress_sql = "
    SELECT
        p.course_id,
        c.course_name,
        p.completed_topics,
        p.total_topics,
        p.percentage

    FROM progress p

    INNER JOIN courses c
        ON p.course_id = c.course_id

    WHERE p.user_id = ?

    ORDER BY p.last_updated DESC
";

$progress_stmt =
    $conn->prepare($progress_sql);

$progress_stmt->bind_param(
    "i",
    $user_id
);

$progress_stmt->execute();

$progress_result =
    $progress_stmt->get_result();

$progress = [];

while (
    $row =
    $progress_result->fetch_assoc()
) {

    $progress[] = $row;

}


/*
    --------------------------------------------------
    4. Quiz Summary
    --------------------------------------------------
*/

$quiz_sql = "
    SELECT

        COUNT(*) AS total_quizzes,

        COALESCE(
            SUM(score),
            0
        ) AS total_correct,

        COALESCE(
            SUM(total_questions),
            0
        ) AS total_questions,

        COALESCE(
            ROUND(
                AVG(percentage),
                2
            ),
            0
        ) AS average_percentage

    FROM quiz_results

    WHERE user_id = ?
";

$quiz_stmt =
    $conn->prepare($quiz_sql);

$quiz_stmt->bind_param(
    "i",
    $user_id
);

$quiz_stmt->execute();

$quiz_result =
    $quiz_stmt->get_result();

$quiz =
    $quiz_result->fetch_assoc();


/*
    --------------------------------------------------
    5. Notes Count
    --------------------------------------------------
*/

$notes_sql = "
    SELECT COUNT(*) AS total_notes
    FROM notes
    WHERE user_id = ?
";

$notes_stmt =
    $conn->prepare($notes_sql);

$notes_stmt->bind_param(
    "i",
    $user_id
);

$notes_stmt->execute();

$notes_result =
    $notes_stmt->get_result();

$notes =
    $notes_result->fetch_assoc();


/*
    --------------------------------------------------
    6. Study Plan Count
    --------------------------------------------------
*/

$study_sql = "
    SELECT COUNT(*) AS total_study_plans
    FROM study_plans
    WHERE user_id = ?
";

$study_stmt =
    $conn->prepare($study_sql);

$study_stmt->bind_param(
    "i",
    $user_id
);

$study_stmt->execute();

$study_result =
    $study_stmt->get_result();

$study =
    $study_result->fetch_assoc();


/*
    --------------------------------------------------
    7. Calculate Overall Progress
    --------------------------------------------------
*/

$overall_progress = 0;

if (count($progress) > 0) {

    $total_percentage = 0;

    foreach ($progress as $item) {

        $total_percentage +=
            (float) $item["percentage"];

    }

    $overall_progress =
        $total_percentage /
        count($progress);
}


/*
    --------------------------------------------------
    8. Return Dashboard Data
    --------------------------------------------------
*/

echo json_encode([

    "success" => true,

    "student" => [

        "user_id" =>
            $user["user_id"],

        "full_name" =>
            $user["full_name"],

        "email" =>
            $user["email"],

        "course" =>
            $user["course"]

    ],

    "courses" => $courses,

    "progress" => [

        "overall_percentage" =>
            round(
                $overall_progress,
                2
            ),

        "courses" =>
            $progress

    ],

    "quiz" => [

        "total_quizzes" =>
            (int)
            $quiz["total_quizzes"],

        "total_correct" =>
            (int)
            $quiz["total_correct"],

        "total_questions" =>
            (int)
            $quiz["total_questions"],

        "average_percentage" =>
            (float)
            $quiz["average_percentage"]

    ],

    "notes" => [

        "total_notes" =>
            (int)
            $notes["total_notes"]

    ],

    "study_plan" => [

        "total_study_plans" =>
            (int)
            $study["total_study_plans"]

    ]

]);


/*
    Close statements
*/

$user_stmt->close();

$course_stmt->close();

$progress_stmt->close();

$quiz_stmt->close();

$notes_stmt->close();

$study_stmt->close();

$conn->close();

?>