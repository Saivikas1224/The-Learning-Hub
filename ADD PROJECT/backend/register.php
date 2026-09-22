<?php

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

$full_name = trim($_POST["full_name"] ?? "");

$email = trim($_POST["email"] ?? "");

$password = $_POST["password"] ?? "";

$confirm_password = $_POST["confirm_password"] ?? "";

$phone = trim($_POST["phone"] ?? "");

$course = trim($_POST["course"] ?? "");



/*
    Check required fields
*/

if (
    empty($full_name) ||
    empty($email) ||
    empty($password)
) {

    die("Please fill in all required fields.");

}



/*
    Validate email
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    die("Please enter a valid email address.");

}



/*
    Check password confirmation
*/

if ($password !== $confirm_password) {

    die("Passwords do not match.");

}



/*
    Check password length
*/

if (strlen($password) < 6) {

    die(
        "Password must contain at least 6 characters."
    );

}



/*
    Check whether email already exists
*/

$check_sql = "
    SELECT user_id
    FROM users
    WHERE email = ?
";

$check_stmt = $conn->prepare($check_sql);

$check_stmt->bind_param(
    "s",
    $email
);

$check_stmt->execute();

$check_stmt->store_result();


if ($check_stmt->num_rows > 0) {

    $check_stmt->close();

    die(
        "An account with this email already exists."
    );

}


$check_stmt->close();



/*
    Securely hash password
*/

$hashed_password =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );



/*
    Insert new user
*/

$sql = "
    INSERT INTO users
    (
        full_name,
        email,
        password,
        phone,
        course,
        created_at
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        NOW()
    )
";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    die(
        "Database error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "sssss",
    $full_name,
    $email,
    $hashed_password,
    $phone,
    $course
);



/*
    Save user
*/

if ($stmt->execute()) {

    $stmt->close();

    $conn->close();


    /*
        Registration successful

        Change this location later if
        you want to redirect to another page.
    */

    header(
        "Location: ../login.html?registered=success"
    );

    exit;

}


else {

    echo "Registration failed.";

}


$stmt->close();

$conn->close();

?>