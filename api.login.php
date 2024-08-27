<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include './config.php';
include './JWT.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // Extract data from the JSON object
    $identifier = trim($data['identifier']); // Can be either username or email
    $password = trim($data['password']);

    // Validate empty fields
    if (empty($identifier) || empty($password)) {
        $response['status'] = 'error';
        $response['message'] = 'Username/Email and password are required';
        echo json_encode($response);
        exit;
    }

    // Check if identifier is an email or username and build the query accordingly
    if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM `users` WHERE `email` = ?";
    } else {
        $sql = "SELECT * FROM `users` WHERE `username` = ?";
    }

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid username/email or password';
        echo json_encode($response);
        exit;
    } else {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Start the session and store user data
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['logged_in'] = true;

            // Generate JWT for the user
            $token = generateJWT($user['username'], $user['email']);

            // Include session data and token in the response
            $response['status'] = 'success';
            $response['message'] = 'Login successful';
            $response['token'] = $token;
            $response['session'] = [
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'logged_in' => $_SESSION['logged_in']
            ];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Invalid username/email or password';
        }
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
$conn->close();
