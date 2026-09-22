<?php

session_start();

require_once "admin-auth.php";
require_once "db.php";

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
    Get student ID
*/

$student_id = intval(
    $_POST["student_id"] ?? 0
);


/*
    Validate student ID
*/

if ($student_id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid student ID."
    ]);

    exit;
}


/*
    Prevent admin from deleting
    an admin account
*/

$check_sql = "
    SELECT role
    FROM users
    WHERE user_id = ?
";

$check_stmt =
    $conn->prepare($check_sql);

$check_stmt->bind_param(
    "i",
    $student_id
);

$check_stmt->execute();

$result =
    $check_stmt->get_result();


if ($result->num_rows !== 1) {

    echo json_encode([
        "success" => false,
        "message" => "Student not found."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}


$user = $result->fetch_assoc();

$check_stmt->close();


/*
    Make sure selected account
    is actually a student
*/

if ($user["role"] !== "student") {

    echo json_encode([
        "success" => false,
        "message" => "Only student accounts can be deleted."
    ]);

    $conn->close();

    exit;
}


/*
    Delete student
*/

$sql = "
    DELETE FROM users
    WHERE user_id = ?
    AND role = 'student'
";

$stmt = $conn->prepare($sql);


if (!$stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

    exit;
}


$stmt->bind_param(
    "i",
    $student_id
);


/*
    Execute deletion
*/

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "Student deleted successfully."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Student could not be deleted."
        ]);

    }

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to delete student."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>