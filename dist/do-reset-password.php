<?php
session_start();
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];
if ($new_password !== $confirm_password) {
    echo "<script>alert('兩次密碼不一致'); window.location.href='reset-password.html';</script>";
    exit;
}
$username = $_SESSION['reset_username'];
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

$host = 'localhost';
$db   = 'test';
$user = 'root';
$pass = '';
$port = 3307;

$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset("utf8mb4");
$stmt = $conn->prepare("UPDATE user SET password_hash=? WHERE username=?");
$stmt->bind_param("ss", $password_hash, $username);
$stmt->execute();
$stmt->close();
$conn->close();

session_destroy();
echo "<script>alert('密碼重設成功，請重新登入'); window.location.href='auth-login.html';</script>";
?>
