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
    Get logged-in user's ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get study plans
*/

$sql = "
    SELECT
        plan_id,
        subject,
        topic,
        study_date,
        start_time,
        end_time,
        status
    FROM study_plans
    WHERE user_id = ?
    ORDER BY study_date ASC, start_time ASC
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


$plans = [];


/*
    Fetch all study plans
*/

while ($row = $result->fetch_assoc()) {

    $plans[] = $row;

}


/*
    Return study plans
*/

echo json_encode([
    "success" => true,
    "plans" => $plans
]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>