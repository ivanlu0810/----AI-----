<?php
session_start();

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
$required_fields = ['age', 'height_cm', 'weight_kg'];
foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "缺少必要字段: $field"]);
        exit;
    }
}

// 數據庫連接配置 - 適配現有的數據庫結構
$host = '1.tcp.jp.ngrok.io';
$dbname = 'test'; // 使用現有的數據庫
$username = 'root';
$password = '';
$port = '20959'; // 使用現有的端口

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 生成唯一的record_id (24位字符)
    $record_id = uniqid('rec_', true) . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);
    
    // 準備SQL語句 - 使用inbody_records表，每次都是新增記錄
    $sql = "INSERT INTO inbody_records (record_id, user_id, age, `height-cm`, `weight-kg`, skeletal_muscle, body_fat, fat_percentage, basal_metabolism, bmi, Date) 
            VALUES (:record_id, :user_id, :age, :height_cm, :weight_kg, :skeletal_muscle, :body_fat, :fat_percentage, :basal_metabolism, :bmi, :test_date)";
    
    $stmt = $pdo->prepare($sql);
    
    // 處理日期：如果用戶沒有輸入日期，使用今天的日期
    $test_date = isset($data['test_date']) && !empty($data['test_date']) ? $data['test_date'] : date('Y-m-d');
    
                // 自動計算BMI（如果沒有提供）
            $bmi = null;
            if (isset($data['bmi']) && !empty($data['bmi'])) {
                $bmi = floatval($data['bmi']);
            } else if (isset($data['height_cm']) && !empty($data['height_cm']) && isset($data['weight_kg']) && !empty($data['weight_kg'])) {
                // BMI = 體重(kg) / 身高(m)²
                $height_m = floatval($data['height_cm']) / 100;
                $weight_kg = floatval($data['weight_kg']);
                $bmi = $weight_kg / ($height_m * $height_m);
            }
            
            // 執行插入
            $result = $stmt->execute([
                ':record_id' => $record_id,
                ':user_id' => $_SESSION['user_id'] ?? 1, // 使用session中的user_id
                ':age' => intval($data['age']),
                ':height_cm' => floatval($data['height_cm']),
                ':weight_kg' => floatval($data['weight_kg']),
                ':skeletal_muscle' => isset($data['skeletal_muscle']) && !empty($data['skeletal_muscle']) ? floatval($data['skeletal_muscle']) : null,
                ':body_fat' => isset($data['body_fat']) && !empty($data['body_fat']) ? floatval($data['body_fat']) : null,
                ':fat_percentage' => isset($data['fat_percentage']) && !empty($data['fat_percentage']) ? floatval($data['fat_percentage']) : null,
                ':basal_metabolism' => isset($data['basal_metabolism']) && !empty($data['basal_metabolism']) ? floatval($data['basal_metabolism']) : null,
                ':bmi' => $bmi,
                ':test_date' => $test_date
            ]);
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => '數據保存成功']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '數據保存失敗']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => '數據庫錯誤: ' . $e->getMessage()]);
}
?> 