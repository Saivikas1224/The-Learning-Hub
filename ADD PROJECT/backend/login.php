<?php

session_start();

require_once "db.php";


/*
    Allow only POST requests
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    die("Invalid request.");

}


/*
    Get form data
*/

$email = trim($_POST["email"] ?? "");

$password = $_POST["password"] ?? "";



/*
    Validate input
*/

if (empty($email) || empty($password)) {

    die("Please enter your email and password.");

}


/*
    Validate email
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    die("Please enter a valid email address.");

}



/*
    Find user
*/

$sql = "
    SELECT
        user_id,
        full_name,
        email,
        password,
        course
    FROM users
    WHERE email = ?
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Database error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "s",
    $email
);


$stmt->execute();


$result = $stmt->get_result();



/*
    Check whether user exists
*/

if ($result->num_rows !== 1) {

    $stmt->close();

    $conn->close();

    die(
        "Invalid email or password."
    );

}


$user = $result->fetch_assoc();



/*
    Verify password
*/

if (
    !password_verify(
        $password,
        $user["password"]
    )
) {

    $stmt->close();

    $conn->close();

    die(
        "Invalid email or password."
    );

}



/*
    Create session
*/

session_regenerate_id(true);


$_SESSION["user_id"] =
    $user["user_id"];


$_SESSION["full_name"] =
    $user["full_name"];


$_SESSION["email"] =
    $user["email"];


$_SESSION["course"] =
    $user["course"];



/*
    Close database
*/

$stmt->close();

$conn->close();



/*
    Login successful
*/

header(
    "Location: ../student/dashboard.html"
);

exit;

?>