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
    Get logged-in user
*/

$user_id = $_SESSION["user_id"];


/*
    Get quiz result data
*/

$quiz_id = intval(
    $_POST["quiz_id"] ?? 0
);

$score = intval(
    $_POST["score"] ?? 0
);

$total_questions = intval(
    $_POST["total_questions"] ?? 0
);


/*
    Validate data
*/

if (
    $quiz_id <= 0 ||
    $total_questions <= 0
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid quiz result."
    ]);

    exit;
}


/*
    Make sure score is valid
*/

if ($score < 0) {

    $score = 0;

}

if ($score > $total_questions) {

    $score = $total_questions;

}


/*
    Calculate percentage
*/

$percentage =
    ($score / $total_questions) * 100;


/*
    Insert result
*/

$sql = "
    INSERT INTO quiz_results
    (
        user_id,
        quiz_id,
        score,
        total_questions,
        percentage,
        completed_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
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

    exit;
}


/*
    Bind values

    i = integer
    d = decimal
*/

$stmt->bind_param(
    "iiiid",
    $user_id,
    $quiz_id,
    $score,
    $total_questions,
    $percentage
);


/*
    Save result
*/

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Quiz result saved successfully.",
        "result_id" => $stmt->insert_id,
        "score" => $score,
        "total_questions" => $total_questions,
        "percentage" => round($percentage, 2)
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to save quiz result."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>