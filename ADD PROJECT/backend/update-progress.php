                <?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/*
    Check login
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
    Allow only POST
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
    Get logged-in user
*/

$user_id = $_SESSION["user_id"];


/*
    Get progress information
*/

$course_id = intval(
    $_POST["course_id"] ?? 0
);

$completed_topics = intval(
    $_POST["completed_topics"] ?? 0
);

$total_topics = intval(
    $_POST["total_topics"] ?? 0
);


/*
    Validate data
*/

if ($course_id <= 0 || $total_topics <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid progress information."
    ]);

    exit;
}


/*
    Prevent invalid values
*/

if ($completed_topics < 0) {

    $completed_topics = 0;

}

if ($completed_topics > $total_topics) {

    $completed_topics = $total_topics;

}


/*
    Calculate percentage
*/

$percentage =
    ($completed_topics / $total_topics) * 100;


/*
    Check whether progress already exists
*/

$check_sql = "
    SELECT progress_id
    FROM progress
    WHERE user_id = ?
    AND course_id = ?
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
    "ii",
    $user_id,
    $course_id
);


$check_stmt->execute();

$check_stmt->store_result();


/*
    Update existing progress
*/

if ($check_stmt->num_rows > 0) {

    $check_stmt->close();


    $update_sql = "
        UPDATE progress

        SET
            completed_topics = ?,
            total_topics = ?,
            percentage = ?,
            last_updated = NOW()

        WHERE user_id = ?
        AND course_id = ?
    ";


    $stmt =
        $conn->prepare($update_sql);


    if (!$stmt) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Database error."
        ]);

        exit;
    }


    $stmt->bind_param(
        "iidii",
        $completed_topics,
        $total_topics,
        $percentage,
        $user_id,
        $course_id
    );


    $success = $stmt->execute();

}


/*
    Create new progress record
*/

else {

    $check_stmt->close();


    $insert_sql = "
        INSERT INTO progress
        (
            user_id,
            course_id,
            completed_topics,
            total_topics,
            percentage,
            last_updated
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


    $stmt =
        $conn->prepare($insert_sql);


    if (!$stmt) {

        http_response_code(500);

        echo json_encode([
            "success" => false,
            "message" => "Database error."
        ]);

        exit;
    }


    $stmt->bind_param(
        "iiiid",
        $user_id,
        $course_id,
        $completed_topics,
        $total_topics,
        $percentage
    );


    $success = $stmt->execute();

}


/*
    Return result
*/

if ($success) {

    echo json_encode([
        "success" => true,
        "message" => "Progress updated successfully.",
        "percentage" => round($percentage, 2)
    ]);

}

else {

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Failed to update progress."
    ]);

}


/*
    Close connection
*/

$stmt->close();

$conn->close();

?>