<?php
header('Content-Type: application/json');

// Include your configuration file for database connection
include '../../config.php';

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Validate inputs
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty(trim($data["username"]))) {
        $errors[] = "Username is required.";
    } else {
        $username = trim($data["username"]);
        if (strlen($username) < 3 || strlen($username) > 20) {
            $errors[] = "Username must be between 3 and 20 characters.";
        } else {
            // Check if the username already exists
            $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "Username is already taken.";
            }
            $stmt->close();
        }
    }

    // Validate email
    if (empty(trim($data["email"]))) {
        $errors[] = "Email is required.";
    } else {
        $email = trim($data["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } else {
            // Check if the email already exists
            $stmt = $conn->prepare("SELECT id FROM admin WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "Email is already registered.";
            }
            $stmt->close();
        }
    }

    // Validate mobile
    if (empty(trim($data["mobile"]))) {
        $errors[] = "Mobile number is required.";
    } else {
        $mobile = trim($data["mobile"]);
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            $errors[] = "Mobile number must be 10 digits.";
        }
    }

    // Validate password
    if (empty(trim($data["password"]))) {
        $errors[] = "Password is required.";
    } else {
        $password = trim($data["password"]);
        if (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        }
    }

    // Validate password confirmation
    if (empty(trim($data["password_confirm"]))) {
        $errors[] = "Password confirmation is required.";
    } else {
        $password_confirm = trim($data["password_confirm"]);
        if ($password !== $password_confirm) {
            $errors[] = "Passwords do not match.";
        }
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $status = 'inactive';

        $stmt = $conn->prepare("INSERT INTO `admin` (`username`, `email`, `mobile`, `password`, `status`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $mobile, $hashed_password, $status);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        // Return validation errors
        echo json_encode(["status" => "error", "message" => $errors]);
    }
}

$conn->close();
