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

// 檢查是否有檔案上傳
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['error' => '檔案上傳失敗']);
    exit;
}

$file = $_FILES['avatar'];

// 驗證檔案類型
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
if (!in_array($file['type'], $allowed_types)) {
    http_response_code(400);
    echo json_encode(['error' => '只允許上傳 JPG、PNG 或 GIF 格式的圖片']);
    exit;
}

// 驗證檔案大小 (限制為 5MB)
$max_size = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $max_size) {
    http_response_code(400);
    echo json_encode(['error' => '檔案大小不能超過 5MB']);
    exit;
}

// 創建上傳目錄
$upload_dir = 'uploads/avatars/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// 生成唯一的檔案名
$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'avatar_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_extension;
$filepath = $upload_dir . $filename;

// 移動上傳的檔案
if (!move_uploaded_file($file['tmp_name'], $filepath)) {
    http_response_code(500);
    echo json_encode(['error' => '檔案儲存失敗']);
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
    
    // 更新用戶的大頭貼路徑
    $update_sql = "UPDATE user SET avatar = :avatar WHERE user_id = :user_id";
    $update_stmt = $pdo->prepare($update_sql);
    
    $result = $update_stmt->execute([
        ':avatar' => $filepath,
        ':user_id' => $user_id
    ]);
    
    if ($result) {
        // 更新session中的用戶資訊
        $_SESSION['avatar'] = $filepath;
        
        echo json_encode([
            'success' => true, 
            'message' => '大頭貼上傳成功！',
            'avatar_path' => $filepath
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '資料庫更新失敗']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '資料庫錯誤: ' . $e->getMessage()]);
}
?>
