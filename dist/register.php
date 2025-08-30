<?php
// 資料庫連線參數
$host = '1.tcp.jp.ngrok.io';
$db   = 'test';
$user = 'root';
$pass = '';
$port = '20959';

// 取得 POST 資料
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$role = $_POST['role'] ?? 'user';
// 密碼加密
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 簡單的資料驗證
if (empty($username) || empty($email) || empty($password) || empty($gender)) {
    echo "<script>alert('請填寫所有必填欄位！'); window.location.href='auth-register.html';</script>";
    exit;
}

// 檢查密碼確認
$confirm_password = $_POST['confirm_password'] ?? '';
if ($password !== $confirm_password) {
    echo "<script>alert('密碼確認不符！'); window.location.href='auth-register.html';</script>";
    exit;
}

// 檢查電子郵件格式
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('請輸入有效的電子郵件地址！'); window.location.href='auth-register.html';</script>";
    exit;
}

// 建立資料庫連線
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 寫入資料 (移除年齡、身高、體重字段)
$stmt = $conn->prepare("INSERT INTO user (username, email, password_hash, gender, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $password_hash, $gender, $role);

if ($stmt->execute()) {
    echo "<script>alert('註冊成功！'); window.location.href='auth-login.html';</script>";
} else {
    echo "<script>alert('註冊失敗：" . addslashes($stmt->error) . "'); window.location.href='auth-register.html';</script>";
}

$stmt->close();
$conn->close();
?>
