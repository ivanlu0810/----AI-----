<?php
session_start();

$host = '1.tcp.jp.ngrok.io';
$db   = 'test';
$user = 'root';
$pass = '';
$port = 20959;

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die('連線失敗: ' . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// 查詢用戶信息，將 id 改為 user_id
$stmt = $conn->prepare("SELECT user_id, username, email, gender, role FROM user WHERE username = ?");
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

$stmt->bind_result($user_id, $db_username, $email, $gender, $role);
$stmt->fetch();

// 重新查詢密碼進行驗證
$stmt2= $conn->prepare("SELECT password_hash FROM user WHERE username = ?");
$stmt2->bind_param("s", $username);
$stmt2->execute();
$stmt2->bind_result($password_hash);
$stmt2->fetch();

if (password_verify($password, $password_hash)) {
    // 登入成功，儲存用戶信息到 session
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $db_username;
    $_SESSION['email'] = $email;
    $_SESSION['gender'] = $gender;
    $_SESSION['role'] = $role;
    $_SESSION['logged_in'] = true;
    
    echo "<script>alert('登入成功！'); window.location.href='index.html';</script>";
} else {
    // 密碼錯誤
    echo "<script>alert('密碼錯誤'); window.location.href='auth-login.html';</script>";
}

$stmt->close();
$stmt2->close();
$conn->close();
?>
