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
    Return JSON response
*/

header("Content-Type: application/json");


/*
    Get logged-in user's ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get all quiz results
*/

$sql = "
    SELECT
        qr.result_id,
        qr.quiz_id,
        q.quiz_title,
        qr.score,
        qr.total_questions,
        qr.percentage,
        qr.completed_at
    FROM quiz_results qr

    INNER JOIN quizzes q
        ON qr.quiz_id = q.quiz_id

    WHERE qr.user_id = ?

    ORDER BY qr.completed_at DESC
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


$stmt->bind_param(
    "i",
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();


$quiz_results = [];


/*
    Fetch results
*/

while ($row = $result->fetch_assoc()) {

    $quiz_results[] = $row;

}


/*
    Calculate summary
*/

$total_quizzes = count($quiz_results);

$total_score = 0;

$total_questions = 0;


foreach ($quiz_results as $quiz) {

    $total_score +=
        (int) $quiz["score"];

    $total_questions +=
        (int) $quiz["total_questions"];

}


$overall_percentage = 0;


if ($total_questions > 0) {

    $overall_percentage =
        ($total_score / $total_questions) * 100;

}


/*
    Return results
*/

echo json_encode([

    "success" => true,

    "results" => $quiz_results,

    "summary" => [

        "total_quizzes" =>
            $total_quizzes,

        "total_score" =>
            $total_score,

        "total_questions" =>
            $total_questions,

        "overall_percentage" =>
            round(
                $overall_percentage,
                2
            )

    ]

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>