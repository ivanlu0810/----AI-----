<?php
// api/get_exercises.php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// 處理 OPTIONS 請求
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // 資料庫連接設定
    $host = '1.tcp.jp.ngrok.io';
    $port = '20959';
    $dbname = 'test';
    $username = 'root'; // 請根據您的設定修改
    $password = ''; // 請根據您的設定修改

    // 建立 PDO 連接
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // 獲取請求的肌群參數
    $muscle = $_GET['muscle'] ?? '';

    if (empty($muscle)) {
        throw new Exception('請指定訓練肌群');
    }

    // 準備 SQL 查詢 - 支援模糊搜尋
    $stmt = $pdo->prepare("SELECT * FROM exercises WHERE target_muscle LIKE ? OR target_muscle LIKE ? OR target_muscle = ? ORDER BY name ASC");
    $stmt->execute(["%{$muscle}%", "{$muscle}%", $muscle]);
    $exercises = $stmt->fetchAll();

    if (empty($exercises)) {
        echo json_encode([
            'success' => true,
            'data' => [],
            'message' => "沒有找到針對 {$muscle} 的訓練資料"
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'success' => true,
            'data' => $exercises,
            'count' => count($exercises),
            'message' => "成功獲取 {$muscle} 的訓練資料"
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    // 資料庫連接錯誤
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => '資料庫連接錯誤',
        'details' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // 其他錯誤
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?> 