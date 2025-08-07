<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => '未登入']);
    exit;
}

// 數據庫連接配置
$host = 'localhost';
$dbname = 'test';
$username = 'root';
$password = '';
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $user_id = $_SESSION['user_id'] ?? 1;
    
    // 獲取用戶的健康數據統計（最新記錄 + 總記錄數）
    $sql = "SELECT 
                COUNT(*) as total_records,
                MAX(Date) as latest_date
            FROM inbody_records 
            WHERE user_id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 獲取最新的健康記錄
    $latest_sql = "SELECT 
                        `height-cm`,
                        `weight-kg`,
                        skeletal_muscle,
                        body_fat,
                        fat_percentage,
                        basal_metabolism,
                        bmi,
                        Date
                    FROM inbody_records 
                    WHERE user_id = :user_id 
                    ORDER BY Date DESC, record_id DESC 
                    LIMIT 1";
    
    $latest_stmt = $pdo->prepare($latest_sql);
    $latest_stmt->execute([':user_id' => $user_id]);
    $latest_record = $latest_stmt->fetch(PDO::FETCH_ASSOC);
    
    // 格式化數據
    $response = [
        'total_records' => intval($stats['total_records']),
        'latest_height' => $latest_record && $latest_record['height-cm'] ? number_format($latest_record['height-cm'], 1) : '0.0',
        'latest_weight' => $latest_record && $latest_record['weight-kg'] ? number_format($latest_record['weight-kg'], 1) : '0.0',
        'latest_skeletal_muscle' => $latest_record && $latest_record['skeletal_muscle'] ? number_format($latest_record['skeletal_muscle'], 1) : '0.0',
        'latest_body_fat' => $latest_record && $latest_record['body_fat'] ? number_format($latest_record['body_fat'], 1) : '0.0',
        'latest_fat_percentage' => $latest_record && $latest_record['fat_percentage'] ? number_format($latest_record['fat_percentage'], 1) : '0.0',
        'latest_basal_metabolism' => $latest_record && $latest_record['basal_metabolism'] ? number_format($latest_record['basal_metabolism'], 0) : '0',
        'latest_bmi' => $latest_record && $latest_record['bmi'] ? number_format($latest_record['bmi'], 1) : '0.0',
        'latest_date' => $stats['latest_date'] ? $stats['latest_date'] : '無記錄'
    ];
    
    echo json_encode($response);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '數據庫錯誤: ' . $e->getMessage()]);
}
?> 