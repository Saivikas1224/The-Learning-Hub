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
    Get logged-in user ID
*/

$user_id = $_SESSION["user_id"];


/*
    Get updated information
*/

$full_name = trim(
    $_POST["full_name"] ?? ""
);

$email = trim(
    $_POST["email"] ?? ""
);

$phone = trim(
    $_POST["phone"] ?? ""
);

$course = trim(
    $_POST["course"] ?? ""
);


/*
    Validate required fields
*/

if (
    empty($full_name) ||
    empty($email)
) {

    echo json_encode([
        "success" => false,
        "message" => "Name and email are required."
    ]);

    exit;
}


/*
    Validate email
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);

    exit;
}


/*
    Check whether another account
    is already using this email
*/

$check_sql = "
    SELECT user_id
    FROM users
    WHERE email = ?
    AND user_id != ?
";


$check_stmt =
    $conn->prepare($check_sql);


if (!$check_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


$check_stmt->bind_param(
    "si",
    $email,
    $user_id
);


$check_stmt->execute();


$check_stmt->store_result();


if ($check_stmt->num_rows > 0) {

    $check_stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "This email is already in use."
    ]);

    exit;
}


$check_stmt->close();


/*
    Update profile
*/

$sql = "
    UPDATE users

    SET
        full_name = ?,
        email = ?,
        phone = ?,
        course = ?

    WHERE user_id = ?
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
    "ssssi",
    $full_name,
    $email,
    $phone,
    $course,
    $user_id
);


/*
    Execute update
*/

if ($stmt->execute()) {

    /*
        Update current session data too
    */

    $_SESSION["full_name"] = $full_name;

    $_SESSION["email"] = $email;

    $_SESSION["course"] = $course;


    echo json_encode([
        "success" => true,
        "message" => "Profile updated successfully."
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update profile."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>