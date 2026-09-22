<?php

session_start();

header("Content-Type: application/json");


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
    Get the student's question
*/

$question = trim(
    $_POST["question"] ?? ""
);


/*
    Validate question
*/

if (empty($question)) {

    echo json_encode([
        "success" => false,
        "message" => "Please enter a question."
    ]);

    exit;
}


/*
    Limit question length
*/

if (strlen($question) > 5000) {

    echo json_encode([
        "success" => false,
        "message" => "Question is too long."
    ]);

    exit;
}


/*
    --------------------------------------------------
    AI API Configuration
    --------------------------------------------------

    IMPORTANT:
    Do NOT put your API key inside HTML or JavaScript.

    Add your API key to your XAMPP/server environment
    later.

    Example:

    $api_key = getenv("OPENAI_API_KEY");
*/


$api_key = getenv("OPENAI_API_KEY");


if (!$api_key) {

    /*
        Temporary response until the AI API
        is connected.
    */

    echo json_encode([
        "success" => false,
        "message" => "AI service is not configured yet."
    ]);

    exit;
}


/*
    --------------------------------------------------
    Prepare AI request
    --------------------------------------------------
*/

$data = [

    "model" => "gpt-4o-mini",

    "messages" => [

        [
            "role" => "system",

            "content" =>
                "You are The Learning Hub AI Assistant. "
                . "Help students understand academic topics "
                . "in a simple, clear and friendly way. "
                . "Give step-by-step explanations when useful."
        ],

        [
            "role" => "user",

            "content" => $question
        ]

    ],

    "temperature" => 0.7

];


$json_data =
    json_encode($data);


/*
    --------------------------------------------------
    Send request to AI API
    --------------------------------------------------
*/

$ch = curl_init(
    "https://api.openai.com/v1/chat/completions"
);


curl_setopt(
    $ch,
    CURLOPT_POST,
    true
);


curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    $json_data
);


curl_setopt(
    $ch,
    CURLOPT_RETURNTRANSFER,
    true
);


curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    [

        "Content-Type: application/json",

        "Authorization: Bearer " . $api_key

    ]
);


/*
    Execute request
*/

$response =
    curl_exec($ch);


/*
    Check cURL error
*/

if ($response === false) {

    curl_close($ch);

    http_response_code(500);

    echo json_encode([
        "success" => false,
        "message" => "Unable to connect to AI service."
    ]);

    exit;
}


$http_code =
    curl_getinfo(
        $ch,
        CURLINFO_HTTP_CODE
    );


curl_close($ch);


/*
    Convert API response to array
*/

$result =
    json_decode(
        $response,
        true
    );


/*
    Check API response
*/

if (
    $http_code < 200 ||
    $http_code >= 300
) {

    echo json_encode([
        "success" => false,
        "message" => "AI service returned an error."
    ]);

    exit;
}


/*
    Extract AI answer
*/

$answer =
    $result["choices"][0]["message"]["content"]
    ?? "";


/*
    Check answer
*/

if (empty($answer)) {

    echo json_encode([
        "success" => false,
        "message" => "No answer was received from the AI."
    ]);

    exit;
}


/*
    Return answer to frontend
*/

echo json_encode([

    "success" => true,

    "answer" => $answer

]);

?>