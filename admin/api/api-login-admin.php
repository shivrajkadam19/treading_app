<?php
session_start();  // Start the session
header('Content-Type: application/json');

// Include your configuration file for database connection
include '../../config.php';

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate inputs
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username or email
    if (empty(trim($data["username_email"]))) {
        $errors[] = "Username or Email is required.";
    } else {
        $username_email = trim($data["username_email"]);
    }

    // Validate password
    if (empty(trim($data["password"]))) {
        $errors[] = "Password is required.";
    } else {
        $password = trim($data["password"]);
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        // Check if the input is an email or username
        if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
            // It's an email
            $stmt = $conn->prepare("SELECT `id`, `username`, `password`, `status` FROM `admin` WHERE `email` = ?");
        } else {
            // It's a username
            $stmt = $conn->prepare("SELECT `id`, `username`, `password`, `status` FROM `admin` WHERE `username` = ?");
        }

        $stmt->bind_param("s", $username_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $hashed_password, $status);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                if ($status === 'inactive') {
                    echo json_encode(["status" => "error", "message" => "Account is inactive. Please contact the administrator."]);
                } else {
                    // Login successful
                    // Set session variables
                    $_SESSION['user_id'] = $id;
                    $_SESSION['username'] = $username;
                    $_SESSION["loggedin"] = TRUE;
                    
                    echo json_encode(["status" => "success", "message" => "Login successful!","session"=>$_SESSION]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Incorrect password."]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "No account found with that username or email."]);
        }

        $stmt->close();
    } else {
        // Return validation errors
        echo json_encode(["status" => "error", "message" => $errors]);
    }
}

$conn->close();
?>
