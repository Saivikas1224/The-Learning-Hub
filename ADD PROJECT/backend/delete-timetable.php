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
    Get logged-in user's ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get timetable ID
*/

$timetable_id = intval(
    $_POST["timetable_id"] ?? 0
);


/*
    Validate timetable ID
*/

if ($timetable_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid timetable ID."
    ]);

    exit;
}


/*
    Delete only the timetable entry
    belonging to the logged-in student
*/

$sql = "
    DELETE FROM timetable
    WHERE timetable_id = ?
    AND user_id = ?
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
    "ii",
    $timetable_id,
    $user_id
);


/*
    Execute delete
*/

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "Timetable entry deleted successfully."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Timetable entry not found."
        ]);

    }

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete timetable entry."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>