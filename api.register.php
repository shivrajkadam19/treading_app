<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

include './config.php';
include './JWT.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $username = trim($data['username']);
    $email = trim($data['email']);
    $mobile_no = trim($data['mobile_no']);
    $password = trim($data['password']);
    $password_confirm = trim($data['password_confirm']);

    if (empty($username) || empty($email) || empty($mobile_no) || empty($password) || empty($password_confirm)) {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid email format';
        echo json_encode($response);
        exit;
    }

    if (!is_numeric($mobile_no) || strlen($mobile_no) < 10 || strlen($mobile_no) > 15) {
        $response['status'] = 'error';
        $response['message'] = 'Invalid mobile number';
        echo json_encode($response);
        exit;
    }

    if (strlen($password) < 8) {
        $response['status'] = 'error';
        $response['message'] = 'Password must be at least 8 characters long';
        echo json_encode($response);
        exit;
    }

    if ($password !== $password_confirm) {
        $response['status'] = 'error';
        $response['message'] = 'Passwords do not match';
        echo json_encode($response);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM `users` WHERE `email` = ? OR `username` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Email or username already exists';
    } else {
        $insertQuery = "INSERT INTO `users` (`username`, `email`, `mobile_no`, `password`) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssss", $username, $email, $mobile_no, $hashed_password);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = true;

            $token = generateJWT($username, $email);
            $response['status'] = 'success';
            $response['message'] = 'User registered successfully';
            $response['token'] = $token;
            $response['session'] = [
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'logged_in' => $_SESSION['logged_in']
            ];
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Registration failed';
        }
    }

    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
$conn->close();
