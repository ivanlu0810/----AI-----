<?php
$host = 'localhost';
$db   = 'test';
$user = 'root';
$pass = '';
$port = 3307;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die('連線失敗: ' . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 查詢 username 是否存在
$stmt = $conn->prepare("SELECT password_hash FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    // 沒有這個使用者
    echo "<script>alert('尚未註冊'); window.location.href='auth-login.html';</script>";
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->bind_result($password_hash);
$stmt->fetch();

if (password_verify($password, $password_hash)) {
    // 登入成功
    echo "<script>alert('登入成功！'); window.location.href='index.html';</script>";
} else {
    // 密碼錯誤
    echo "<script>alert('密碼錯誤'); window.location.href='auth-login.html';</script>";
}

$stmt->close();
$conn->close();
?>
