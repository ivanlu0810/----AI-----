<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// 預檢請求 (CORS 用)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ✅ 載入 dotenv
require __DIR__ . '/vendor/autoload.php';
// 如果 .env 在 AI 層，就用 dirname(__DIR__)
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// 🔑 從 .env 讀取設定
$host     = $_ENV["DB_HOST"];
$port     = $_ENV["DB_PORT"];
$dbname   = $_ENV["DB_NAME"];
$username = $_ENV["DB_USER"];
$password = $_ENV["DB_PASSWORD"];
$apiKey   = $_ENV["OPENAI_API_KEY"];
$projectId = $_ENV["OPENAI_PROJECT_ID"];    

// 1️⃣ 連線 MySQL
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "資料庫連線失敗: " . $e->getMessage()]);
    exit;
}

// 2️⃣ 查詢最新 inbody 紀錄
$userDataText = "（⚠️ 尚未查到身體數據，請先輸入健康數據）";
try {
    $stmt = $pdo->query("SELECT * FROM `inbody_records` ORDER BY `Date` DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $userDataText = "
目前您的最新身體數據如下：
- 年齡：{$row['age']}
- 身高：{$row['height-cm']} cm
- 體重：{$row['weight-kg']} kg
- 骨骼肌量：{$row['skeletal_muscle']} kg
- 體脂肪重量：{$row['body_fat']} kg
- 體脂率：{$row['fat_percentage']} %
- 基礎代謝：{$row['basal_metabolism']} kcal
- BMI：{$row['bmi']}
- 測量日期：{$row['Date']}
";
    }
} catch (Exception $e) {
    echo json_encode(["error" => "查詢資料庫失敗: " . $e->getMessage()]);
    exit;
}

// 3️⃣ 取得前端輸入
$input = json_decode(file_get_contents("php://input"), true);
$userMessage = $input["messages"][0]["content"] ?? "";

// 4️⃣ 呼叫 OpenAI API
$ch = curl_init("https://api.openai.com/v1/chat/completions");

$data = [
    "model" => "gpt-4o-mini",
    "messages" => [
        [
            "role" => "system",
            "content" => "你是一位健身 AI 諮詢助理，第一次要先列出使用者的身體數據，再依據問題給予具體建議，並使用繁體中文回答。",
        ],
        [
            "role" => "user",
            "content" => "以下是使用者的身體數據：\n" . $userDataText . "\n\n接下來是使用者的問題：" . $userMessage
        ]
    ]
];

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey",
    "OpenAI-Project: $projectId"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// 🟢 指定 CA 憑證檔，確保 SSL 驗證成功
curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/../cacert.pem");

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(["error" => "OpenAI API 請求失敗: " . curl_error($ch)]);
    exit;
}
curl_close($ch);

// 5️⃣ 回傳 AI 回覆
echo $response;
