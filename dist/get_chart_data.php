<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => '未登入']);
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
    
    $user_id = $_SESSION['user_id'] ?? 1;
    
    // 獲取用戶的所有健康記錄，按日期排序
    $sql = "SELECT 
                Date,
                `weight-kg`,
                `height-cm`,
                skeletal_muscle,
                body_fat,
                fat_percentage,
                basal_metabolism,
                bmi
            FROM inbody_records 
            WHERE user_id = :user_id 
            ORDER BY Date ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 格式化數據為圖表格式
    $chartData = [
        'dates' => [],
        'weight' => [],
        'height' => [],
        'skeletal_muscle' => [],
        'body_fat' => [],
        'fat_percentage' => [],
        'basal_metabolism' => [],
        'bmi' => []
    ];
    
    foreach ($records as $record) {
        $chartData['dates'][] = $record['Date'];
        $chartData['weight'][] = $record['weight-kg'] ? floatval($record['weight-kg']) : null;
        $chartData['height'][] = $record['height-cm'] ? floatval($record['height-cm']) : null;
        $chartData['skeletal_muscle'][] = $record['skeletal_muscle'] ? floatval($record['skeletal_muscle']) : null;
        $chartData['body_fat'][] = $record['body_fat'] ? floatval($record['body_fat']) : null;
        $chartData['fat_percentage'][] = $record['fat_percentage'] ? floatval($record['fat_percentage']) : null;
        $chartData['basal_metabolism'][] = $record['basal_metabolism'] ? floatval($record['basal_metabolism']) : null;
        $chartData['bmi'][] = $record['bmi'] ? floatval($record['bmi']) : null;
    }
    
    echo json_encode($chartData);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '數據庫錯誤: ' . $e->getMessage()]);
}
?> 