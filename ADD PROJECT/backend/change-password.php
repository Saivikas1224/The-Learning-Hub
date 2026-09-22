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
    Get passwords
*/

$current_password =
    $_POST["current_password"] ?? "";

$new_password =
    $_POST["new_password"] ?? "";

$confirm_password =
    $_POST["confirm_password"] ?? "";


/*
    Validate fields
*/

if (
    empty($current_password) ||
    empty($new_password) ||
    empty($confirm_password)
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill in all password fields."
    ]);

    exit;
}


/*
    Check new password length
*/

if (strlen($new_password) < 6) {

    echo json_encode([
        "success" => false,
        "message" => "New password must contain at least 6 characters."
    ]);

    exit;
}


/*
    Check password confirmation
*/

if ($new_password !== $confirm_password) {

    echo json_encode([
        "success" => false,
        "message" => "New passwords do not match."
    ]);

    exit;
}


/*
    Get current password from database
*/

$sql = "
    SELECT password
    FROM users
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
    "i",
    $user_id
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "User account not found."
    ]);

    exit;
}


$user = $result->fetch_assoc();


$stmt->close();


/*
    Verify current password
*/

if (
    !password_verify(
        $current_password,
        $user["password"]
    )
) {

    echo json_encode([
        "success" => false,
        "message" => "Current password is incorrect."
    ]);

    exit;
}


/*
    Hash new password
*/

$hashed_password =
    password_hash(
        $new_password,
        PASSWORD_DEFAULT
    );


/*
    Update password
*/

$update_sql = "
    UPDATE users

    SET password = ?

    WHERE user_id = ?
";


$update_stmt =
    $conn->prepare($update_sql);


if (!$update_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit;
}


$update_stmt->bind_param(
    "si",
    $hashed_password,
    $user_id
);


/*
    Save new password
*/

if ($update_stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Password changed successfully."
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to change password."
    ]);

}


/*
    Close connection
*/

$update_stmt->close();

$conn->close();

?>