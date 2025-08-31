<?php
session_start();

echo "<h2>圖表數據測試</h2>";

// 檢查會話
echo "<h3>會話信息：</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// 數據庫連接配置
$host = '1.tcp.jp.ngrok.io';
$dbname = 'test';
$username = 'root';
$password = '';
$port = 20959;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h3>數據庫連接成功</h3>";
    
    $user_id = $_SESSION['userid'] ?? $_SESSION['user_id'] ?? 1;
    echo "<h3>使用的用戶ID: " . $user_id . "</h3>";
    
    // 檢查所有記錄
    $sql = "SELECT Date, `weight-kg`, skeletal_muscle, body_fat FROM inbody_records WHERE user_id = :user_id ORDER BY Date ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $all_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>所有記錄 (" . count($all_records) . " 筆)：</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>日期</th><th>體重</th><th>骨骼肌</th><th>體脂肪</th></tr>";
    foreach ($all_records as $record) {
        echo "<tr>";
        echo "<td>" . $record['Date'] . "</td>";
        echo "<td>" . $record['weight-kg'] . "</td>";
        echo "<td>" . $record['skeletal_muscle'] . "</td>";
        echo "<td>" . $record['body_fat'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 測試時間範圍篩選
    echo "<h3>測試時間範圍篩選：</h3>";
    
    // 測試1：8/1 到 8/31
    $start_date = '2025-08-01';
    $end_date = '2025-08-31';
    
    $sql_filtered = "SELECT Date, `weight-kg`, skeletal_muscle, body_fat FROM inbody_records 
                     WHERE user_id = :user_id AND Date >= :start_date AND Date <= :end_date 
                     ORDER BY Date ASC";
    $stmt_filtered = $pdo->prepare($sql_filtered);
    $stmt_filtered->execute([
        ':user_id' => $user_id,
        ':start_date' => $start_date,
        ':end_date' => $end_date
    ]);
    $filtered_records = $stmt_filtered->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h4>篩選條件：{$start_date} 到 {$end_date}</h4>";
    echo "<p>篩選後記錄數：" . count($filtered_records) . "</p>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>日期</th><th>體重</th><th>骨骼肌</th><th>體脂肪</th></tr>";
    foreach ($filtered_records as $record) {
        echo "<tr>";
        echo "<td>" . $record['Date'] . "</td>";
        echo "<td>" . $record['weight-kg'] . "</td>";
        echo "<td>" . $record['skeletal_muscle'] . "</td>";
        echo "<td>" . $record['body_fat'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 檢查是否有7月的資料
    $sql_july = "SELECT COUNT(*) as july_count FROM inbody_records 
                 WHERE user_id = :user_id AND Date < '2025-08-01'";
    $stmt_july = $pdo->prepare($sql_july);
    $stmt_july->execute([':user_id' => $user_id]);
    $july_count = $stmt_july->fetch(PDO::FETCH_ASSOC)['july_count'];
    
    echo "<h4>7月資料檢查：</h4>";
    echo "<p>7月記錄數：" . $july_count . "</p>";
    
    if ($july_count > 0) {
        echo "<p style='color: red;'>⚠️ 發現7月資料！這表示時間篩選可能有問題。</p>";
        
        $sql_july_details = "SELECT Date, `weight-kg` FROM inbody_records 
                            WHERE user_id = :user_id AND Date < '2025-08-01' 
                            ORDER BY Date ASC";
        $stmt_july_details = $pdo->prepare($sql_july_details);
        $stmt_july_details->execute([':user_id' => $user_id]);
        $july_records = $stmt_july_details->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>7月詳細資料：</p>";
        echo "<ul>";
        foreach ($july_records as $record) {
            echo "<li>" . $record['Date'] . " - 體重: " . $record['weight-kg'] . " kg</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: green;'>✅ 沒有7月資料，時間篩選正常。</p>";
    }
    
} catch (PDOException $e) {
    echo "<h3>數據庫錯誤：</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
