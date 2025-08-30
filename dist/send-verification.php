<?php
session_start();
$username = $_POST['username'];
$email = $_POST['email'];

// 資料庫連線
$host = '1.tcp.jp.ngrok.io';
$db   = 'test';
$user = 'root';
$pass = '';
$port = '20959';

$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset("utf8mb4");

// 先查詢資料庫有沒有這個人
$stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND email=?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo "<script>alert('尚未註冊'); window.location.href='auth-forgot-password.html';</script>";
    $stmt->close();
    $conn->close();
    exit;
} else {
    // 測試用
    echo "<script>alert('查到的email: $email');</script>";
}

// 有這個人，產生驗證碼
$code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

// ======= 用 PHPMailer 寄信 =======
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'jgayivan@gmail.com'; // 你的 Gmail
    $mail->Password = 'yrge ceeo cqai aqvv'; // 這裡貼上你的應用程式密碼
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('jgayivan@gmail.com', '你的網站名稱');
    $mail->addAddress($email, $username);

    $mail->Subject = '您的驗證碼';
    $mail->Body    = '您的驗證碼是: ' . $code;

    $mail->send();
} catch (Exception $e) {
    echo "<script>alert('驗證碼寄送失敗: {$mail->ErrorInfo}'); window.location.href='auth-forgot-password.html';</script>";
    exit;
}

// 存到 session
$_SESSION['reset_username'] = $username;
$_SESSION['reset_email'] = $email;
$_SESSION['reset_code'] = $code;

// 跳轉到驗證碼輸入頁
header("Location: verify-code.html");
exit;
?>