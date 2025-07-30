<?php
session_start();
$input_code = $_POST['code'];
if ($input_code === $_SESSION['reset_code']) {
    // 驗證成功，跳到重設密碼頁
    header("Location: reset-password.html");
    exit;
} else {
    echo "<script>alert('驗證碼錯誤'); window.location.href='verify-code.html';</script>";
    exit;
}
?>
