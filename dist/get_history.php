<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");

// 開啟錯誤輸出（Debug 用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 暫時模擬登入狀態進行測試
if (!isset($_SESSION['user_id'])) {
    // 測試用：設定一個預設的 user_id
    $_SESSION['user_id'] = 9; // 根據您的資料庫圖片，用戶ID是9
    $_SESSION['logged_in'] = true;
}

$host = "1.tcp.jp.ngrok.io";
$port = 20959;
$user = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($host, $user, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "error" => "資料庫連線失敗: " . $conn->connect_error]);
    exit;
}

// 只顯示當前用戶的資料
$userId = $_SESSION['user_id'];

$sql = "SELECT record_id, user_id, age, `height-cm` AS height_cm, `weight-kg` AS weight_kg,
               skeletal_muscle, body_fat, fat_percentage, basal_metabolism, bmi, `Date`
        FROM inbody_records
        WHERE user_id = ?
        ORDER BY `Date` ASC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "error" => "SQL錯誤: " . $conn->error, "sql" => $sql]);
    exit;
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    echo json_encode(["success" => false, "error" => "查詢失敗: " . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    // 計算 BMI 如果沒有儲存
    if (empty($row['bmi']) && !empty($row['height_cm']) && !empty($row['weight_kg'])) {
        $height_m = $row['height_cm'] / 100;
        $row['bmi'] = round($row['weight_kg'] / ($height_m * $height_m), 2);
    }
    
    // 格式化數據
    $row['height_cm'] = $row['height_cm'] ? number_format($row['height_cm'], 1) : '無資料';
    $row['weight_kg'] = $row['weight_kg'] ? number_format($row['weight_kg'], 1) : '無資料';
    $row['skeletal_muscle'] = $row['skeletal_muscle'] ? number_format($row['skeletal_muscle'], 1) : '無資料';
    $row['body_fat'] = $row['body_fat'] ? number_format($row['body_fat'], 1) : '無資料';
    $row['fat_percentage'] = $row['fat_percentage'] ? number_format($row['fat_percentage'], 1) : '無資料';
    $row['basal_metabolism'] = $row['basal_metabolism'] ? number_format($row['basal_metabolism'], 0) : '無資料';
    $row['bmi'] = $row['bmi'] ? number_format($row['bmi'], 2) : '無資料';
    
    $data[] = $row;
}

// 獲取統計資訊
$totalRecords = count($data);

echo json_encode([
    "success" => true, 
    "data" => $data,
    "stats" => [
        "total_records" => $totalRecords
    ]
], JSON_UNESCAPED_UNICODE);
?>
