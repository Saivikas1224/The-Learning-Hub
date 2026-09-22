<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check student login
*/

if (!isset($_SESSION["user_id"])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit;
}


$user_id = intval($_SESSION["user_id"]);


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
    Get profile information
*/

$name = trim(
    $_POST["name"] ?? ""
);

$email = trim(
    $_POST["email"] ?? ""
);


/*
    Validate name
*/

if (empty($name)) {

    echo json_encode([
        "success" => false,
        "message" => "Name is required."
    ]);

    exit;
}


if (strlen($name) > 100) {

    echo json_encode([
        "success" => false,
        "message" => "Name is too long."
    ]);

    exit;
}


/*
    Validate email
*/

if (empty($email)) {

    echo json_encode([
        "success" => false,
        "message" => "Email is required."
    ]);

    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);

    exit;
}


if (strlen($email) > 150) {

    echo json_encode([
        "success" => false,
        "message" => "Email is too long."
    ]);

    exit;
}


/*
    Check whether email is already
    used by another account
*/

$check_sql = "
    SELECT user_id
    FROM users
    WHERE email = ?
    AND user_id != ?
    LIMIT 1
";


$check_stmt =
    $conn->prepare($check_sql);


if (!$check_stmt) {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    $conn->close();

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

    echo json_encode([
        "success" => false,
        "message" =>
            "This email is already registered."
    ]);

    $check_stmt->close();
    $conn->close();

    exit;
}


$check_stmt->close();


/*
    Update profile
*/

$sql = "
    UPDATE users

    SET
        name = ?,
        email = ?

    WHERE user_id = ?
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


/*
    Bind values
*/

$stmt->bind_param(
    "ssi",
    $name,
    $email,
    $user_id
);


/*
    Execute update
*/

if ($stmt->execute()) {

    /*
        Update session name
    */

    $_SESSION["name"] = $name;


    echo json_encode([

        "success" => true,

        "message" =>
            "Profile updated successfully.",

        "profile" => [

            "user_id" =>
                $user_id,

            "name" =>
                $name,

            "email" =>
                $email

        ]

    ]);

} else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" =>
            "Failed to update profile."
    ]);
}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>