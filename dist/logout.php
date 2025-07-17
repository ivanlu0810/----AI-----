<?php
session_start();

// 清除所有 session 變數
$_SESSION = array();

// 如果使用 session cookie，也要清除
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 銷毀 session
session_destroy();

// 跳轉到登入頁面
header('Location: auth-login.html');
exit;
?> 