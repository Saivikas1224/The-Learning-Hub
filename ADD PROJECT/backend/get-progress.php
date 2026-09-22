<?php

session_start();

require_once "db.php";


/*
    Check whether the student is logged in
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


/*
    Return JSON
*/

header("Content-Type: application/json");


/*
    Get logged-in user ID
*/

$user_id = $_SESSION["user_id"];



/*
    -----------------------------------------------------
    1. Get course progress
    -----------------------------------------------------
*/

$course_sql = "
    SELECT
        p.progress_id,
        p.course_id,
        c.course_name,
        p.completed_topics,
        p.total_topics,
        p.percentage,
        p.last_updated

    FROM progress p

    LEFT JOIN courses c
        ON p.course_id = c.course_id

    WHERE p.user_id = ?

    ORDER BY p.last_updated DESC
";


$course_stmt =
    $conn->prepare($course_sql);


if (!$course_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;

}


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
    -----------------------------------------------------
    2. Get quiz performance
    -----------------------------------------------------
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


if (!$quiz_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;

}


$quiz_stmt->bind_param(
    "i",
    $user_id
);


$quiz_stmt->execute();


$quiz_result =
    $quiz_stmt->get_result();


$quiz_summary =
    $quiz_result->fetch_assoc();



/*
    -----------------------------------------------------
    3. Get notes count
    -----------------------------------------------------
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


$notes_summary =
    $notes_result->fetch_assoc();



/*
    -----------------------------------------------------
    4. Get study-plan count
    -----------------------------------------------------
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


$study_summary =
    $study_result->fetch_assoc();



/*
    -----------------------------------------------------
    5. Calculate overall course progress
    -----------------------------------------------------
*/

$overall_progress = 0;


if (count($courses) > 0) {

    $total_percentage = 0;

    foreach ($courses as $course) {

        $total_percentage +=
            (float) $course["percentage"];

    }

    $overall_progress =
        $total_percentage /
        count($courses);

}



/*
    -----------------------------------------------------
    6. Return all progress information
    -----------------------------------------------------
*/

echo json_encode([

    "success" => true,

    "overall_progress" =>
        round(
            $overall_progress,
            2
        ),

    "courses" =>
        $courses,

    "quiz" => [

        "total_quizzes" =>
            (int)
            $quiz_summary["total_quizzes"],

        "total_correct" =>
            (int)
            $quiz_summary["total_correct"],

        "total_questions" =>
            (int)
            $quiz_summary["total_questions"],

        "average_percentage" =>
            (float)
            $quiz_summary["average_percentage"]

    ],

    "notes" => [

        "total_notes" =>
            (int)
            $notes_summary["total_notes"]

    ],

    "study_plan" => [

        "total_study_plans" =>
            (int)
            $study_summary["total_study_plans"]

    ]

]);



/*
    -----------------------------------------------------
    Close connections
    -----------------------------------------------------
*/

$course_stmt->close();

$quiz_stmt->close();

$notes_stmt->close();

$study_stmt->close();

$conn->close();

?>