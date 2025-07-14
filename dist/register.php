<?php
// 資料庫連線參數
$host = 'localhost';
$db   = 'test';
$user = 'root';
$pass = '';
$port = 3307;

// 取得 POST 資料
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$gender = $_POST['gender'] ?? '';
$age = $_POST['age'] ?? '';
$height_cm = $_POST['height_cm'] ?? '';
$weight_kg = $_POST['weight_kg'] ?? '';
$role = $_POST['role'] ?? 'user';

// 密碼加密
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// 建立資料庫連線
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die('連線失敗: ' . $conn->connect_error);
}

// 寫入資料
$stmt = $conn->prepare("INSERT INTO user (username, email, password_hash, gender, age, height_cm, weight_kg, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssidds", $username, $email, $password_hash, $gender, $age, $height_cm, $weight_kg, $role);

if ($stmt->execute()) {
    echo "<script>alert('註冊成功！'); window.location.href='auth-login.html';</script>";
} else {
    echo "<script>alert('註冊失敗：" . addslashes($stmt->error) . "'); window.location.href='auth-register.html';</script>";
}

$stmt->close();
$conn->close();
?>
