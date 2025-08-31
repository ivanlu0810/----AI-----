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
    
    $user_id = $_SESSION['userid'] ?? $_SESSION['user_id'] ?? 1;
    
    // 調試信息：記錄會話和用戶ID
    error_log("Session data: " . print_r($_SESSION, true));
    error_log("User ID being used: " . $user_id);
    
    // 獲取時間範圍參數
    $start_date = $_GET['start_date'] ?? null;
    $end_date = $_GET['end_date'] ?? null;
    
    // 調試信息：記錄時間範圍參數
    error_log("Date range parameters - start_date: " . ($start_date ?? 'null') . ", end_date: " . ($end_date ?? 'null'));
    
    // 構建 SQL 查詢
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
            WHERE user_id = :user_id";
    
    $params = [':user_id' => $user_id];
    
    // 如果有時間範圍，添加時間條件
    if ($start_date && $end_date) {
        $sql .= " AND Date >= :start_date AND Date <= :end_date";
        $params[':start_date'] = $start_date;
        $params[':end_date'] = $end_date;
        error_log("SQL with date filter: " . $sql);
        error_log("Date parameters: start_date=" . $start_date . ", end_date=" . $end_date);
    } else {
        error_log("No date range specified, showing all records");
    }
    
    $sql .= " ORDER BY Date ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 調試信息：記錄查詢到的數據數量和日期範圍
    error_log("Chart data query result: " . count($records) . " records for user_id: " . $user_id);
    if (count($records) > 0) {
        $first_date = $records[0]['Date'];
        $last_date = $records[count($records) - 1]['Date'];
        error_log("Date range in results: " . $first_date . " to " . $last_date);
    }
    
    // 格式化數據為圖表格式
    $chartData = [
        'dates' => [],
        'weight' => [],
        'height' => [],
        'skeletal_muscle' => [],
        'body_fat' => [],
        'fat_percentage' => [],
        'basal_metabolism' => [],
        'bmi' => [],
        'total_records' => count($records), // 添加總記錄數
        'date_filter_applied' => ($start_date && $end_date), // 標記是否應用了日期篩選
        'filter_start_date' => $start_date, // 記錄篩選的開始日期
        'filter_end_date' => $end_date // 記錄篩選的結束日期
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
    
    // 調試信息：記錄處理後的數據
    error_log("Chart data processed: " . count($chartData['dates']) . " dates, " . count($chartData['weight']) . " weight values");
    
    echo json_encode($chartData);
    
} catch (PDOException $e) {
    error_log("Chart data error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => '數據庫錯誤: ' . $e->getMessage()]);
}
?> 