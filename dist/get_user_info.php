<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_info = [
    'username' => $_SESSION['username'] ?? 'Unknown User',
    'email' => $_SESSION['email'] ?? 'No email',
    'gender' => $_SESSION['gender'] ?? 'Unknown',
    'role' => $_SESSION['role'] ?? 'user'
];

echo json_encode($user_info);
?> 