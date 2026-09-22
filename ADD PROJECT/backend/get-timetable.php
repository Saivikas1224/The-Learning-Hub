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
    Get timetable entries
*/

$sql = "
    SELECT
        timetable_id,
        subject,
        topic,
        day,
        start_time,
        end_time,
        location
    FROM timetable
    WHERE user_id = ?
    ORDER BY
        FIELD(
            day,
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ),
        start_time ASC
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


$timetable = [];


/*
    Fetch timetable entries
*/

while ($row = $result->fetch_assoc()) {

    $timetable[] = $row;

}


/*
    Return timetable data
*/

echo json_encode([
    "success" => true,
    "timetable" => $timetable
]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>