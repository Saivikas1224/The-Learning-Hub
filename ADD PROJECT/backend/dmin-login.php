<?php

session_start();

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
    Get login details
*/

$email = trim(
    $_POST["email"] ?? ""
);

$password =
    $_POST["password"] ?? "";


/*
    Validate fields
*/

if (
    empty($email) ||
    empty($password)
) {

    echo json_encode([
        "success" => false,
        "message" => "Email and password are required."
    ]);

    exit;
}


/*
    Find admin account
*/

$sql = "
    SELECT
        user_id,
        full_name,
        email,
        password,
        role

    FROM users

    WHERE email = ?
    AND role = 'admin'

    LIMIT 1
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
    "s",
    $email
);


$stmt->execute();


$result =
    $stmt->get_result();


/*
    Check admin account
*/

if ($result->num_rows !== 1) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid admin credentials."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}


$admin =
    $result->fetch_assoc();


/*
    Verify password
*/

if (
    !password_verify(
        $password,
        $admin["password"]
    )
) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid admin credentials."
    ]);

    $stmt->close();
    $conn->close();

    exit;
}


/*
    Regenerate session ID
*/

session_regenerate_id(true);


/*
    Store admin session
*/

$_SESSION["user_id"] =
    $admin["user_id"];

$_SESSION["full_name"] =
    $admin["full_name"];

$_SESSION["email"] =
    $admin["email"];

$_SESSION["role"] =
    "admin";


/*
    Return success
*/

echo json_encode([

    "success" => true,

    "message" =>
        "Admin login successful.",

    "admin" => [

        "user_id" =>
            $admin["user_id"],

        "full_name" =>
            $admin["full_name"],

        "email" =>
            $admin["email"],

        "role" =>
            "admin"

    ]

]);


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>