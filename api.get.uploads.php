<?php
require './config.php'; // Include the database connection

header("Content-Type: application/json");

// Enable CORS for web requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Check if the request method is GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $sql = "SELECT `id`, `title`, `file`, `description`, `created_at` FROM `uploads` ORDER BY `created_at` DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $uploads = [];
        while($row = $result->fetch_assoc()) {
            $uploads[] = $row;
        }
        echo json_encode([
            'status' => 'success',
            'data' => $uploads
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No files found'
        ]);
    }

    $conn->close();
} else {
    // If not GET, return error
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>
