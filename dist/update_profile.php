<?php
session_start();
header('Content-Type: application/json');

// 檢查用戶是否已登入
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => '未登入']);
    exit;
}

// 檢查是否為POST請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => '方法不允許']);
    exit;
}

// 獲取POST數據
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['error' => '無效的數據格式']);
    exit;
}

// 驗證必要字段
$required_fields = ['name', 'email', 'password', 'gender'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "缺少必要字段: $field"]);
        exit;
    }
}

// 驗證電子郵件格式
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => '請輸入有效的電子郵件地址']);
    exit;
}

// 數據庫連接配置
$host = '1.tcp.jp.ngrok.io';
$dbname = 'test';
$username = 'root';
$password = '';
$port = 20959;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $user_id = $_SESSION['user_id'];
    
    // 檢查電子郵件是否已被其他用戶使用
    $check_email_sql = "SELECT user_id FROM user WHERE email = :email AND user_id != :user_id";
    $check_stmt = $pdo->prepare($check_email_sql);
    $check_stmt->execute([':email' => $data['email'], ':user_id' => $user_id]);
    
    if ($check_stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['error' => '此電子郵件已被其他用戶使用']);
        exit;
    }
    
    // 檢查用戶名是否已被其他用戶使用
    $check_username_sql = "SELECT user_id FROM user WHERE username = :username AND user_id != :user_id";
    $check_username_stmt = $pdo->prepare($check_username_sql);
    $check_username_stmt->execute([':username' => $data['name'], ':user_id' => $user_id]);
    
    if ($check_username_stmt->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['error' => '此用戶名已被其他用戶使用']);
        exit;
    }
    
    // 密碼加密
    $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // 更新用戶資料
    $update_sql = "UPDATE user SET username = :username, email = :email, password_hash = :password_hash, gender = :gender WHERE user_id = :user_id";
    $update_stmt = $pdo->prepare($update_sql);
    
    $result = $update_stmt->execute([
        ':username' => $data['name'],
        ':email' => $data['email'],
        ':password_hash' => $password_hash,
        ':gender' => $data['gender'],
        ':user_id' => $user_id
    ]);
    
    if ($result) {
        // 更新session中的用戶資訊
        $_SESSION['username'] = $data['name'];
        $_SESSION['email'] = $data['email'];
        $_SESSION['gender'] = $data['gender'];
        
        echo json_encode([
            'success' => true, 
            'message' => '資料更新成功！',
            'user_data' => [
                'username' => $data['name'],
                'email' => $data['email'],
                'gender' => $data['gender']
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '資料更新失敗']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤: ' . $e->getMessage()]);
}
?>
