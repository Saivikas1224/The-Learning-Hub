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
    Return JSON data
*/

header("Content-Type: application/json");


/*
    Get logged-in user's ID
*/

$user_id = $_SESSION["user_id"];



/*
    Get notes belonging to this user
*/

$sql = "
    SELECT
        note_id,
        title,
        subject,
        content,
        created_at,
        updated_at
    FROM notes
    WHERE user_id = ?
    ORDER BY updated_at DESC
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


$notes = [];



/*
    Fetch notes
*/

while ($row = $result->fetch_assoc()) {

    $notes[] = $row;

}



/*
    Return notes
*/

echo json_encode([
    "success" => true,
    "notes" => $notes
]);



/*
    Close connection
*/

$stmt->close();

$conn->close();

?>