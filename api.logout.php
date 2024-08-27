<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION = array();
    session_destroy();

    $response['status'] = 'success';
    $response['message'] = 'Logout successful';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method. Only POST allowed.';
}

echo json_encode($response);
