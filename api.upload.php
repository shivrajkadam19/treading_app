<?php
require './config.php'; // Include the database connection

header("Content-Type: application/json");

// Enable CORS for web requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file types
        $allowedfileExtensions = ['jpg', 'png', 'pdf'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Directory to upload files
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }
            $dest_path = $uploadFileDir . $fileName;

            // Move the file to the destination directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Insert file details into the database
                $title = isset($_POST['title']) ? $_POST['title'] : '';
                $description = isset($_POST['description']) ? $_POST['description'] : '';
                $created_at = isset($_POST['creation_date']) ? $_POST['creation_date'] : date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                // Update SQL to match the parameters
                $sql = "INSERT INTO `uploads` (`title`, `file`, `description`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sssss", $title, $fileName, $description, $created_at, $updated_at);

                    if ($stmt->execute()) {
                        $response = [
                            'status' => 'success',
                            'message' => 'File uploaded and data inserted successfully'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to insert data into database'
                        ];
                    }

                    $stmt->close();
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to prepare the SQL statement'
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'There was an error moving the uploaded file'
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Upload failed. Allowed file types: .jpg, .png, .pdf'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No file uploaded or there was an error during file upload'
        ];
    }

    echo json_encode($response);
    $conn->close();
} else {
    // If not POST, return error
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}
?>
