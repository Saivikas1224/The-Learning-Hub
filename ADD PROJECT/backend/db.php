<?php

/*
    =====================================================
    THE LEARNING HUB
    Database Connection
    =====================================================

    Database:
    learning_hub

    Server:
    localhost

    Username:
    root

    Password:
    empty by default in XAMPP
*/


$host = "localhost";

$username = "root";

$password = "";

$database = "learning_hub";



/*
    Create MySQL connection
*/

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);



/*
    Check connection
*/

if ($conn->connect_error) {

    die(
        "Database connection failed: "
        . $conn->connect_error
    );

}



/*
    Set character encoding
*/

$conn->set_charset("utf8mb4");

?>