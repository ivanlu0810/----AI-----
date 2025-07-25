# 健康數據管理功能

## 功能說明

這個功能允許用戶在基本資料頁面添加健康數據，包括：
- 骨骼肌重 (kg)
- 體脂肪重 (kg)
- 體脂率 (%)
- 基礎代謝量 (kcal)
- BMI (可選)

## 設置步驟

### 1. 數據庫設置

系統會自動在現有的 `test` 數據庫中創建 `health_records` 表。如果表不存在，系統會在第一次使用時自動創建。

表結構如下：

```sql
CREATE TABLE health_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    test_date DATE NOT NULL,
    skeletal_muscle DECIMAL(5,2) NOT NULL COMMENT '骨骼肌重 (kg)',
    body_fat DECIMAL(5,2) NOT NULL COMMENT '體脂肪重 (kg)',
    fat_percentage DECIMAL(5,2) NOT NULL COMMENT '體脂率 (%)',
    basal_metabolism DECIMAL(8,2) NOT NULL COMMENT '基礎代謝量 (kcal)',
    bmi DECIMAL(4,2) NULL COMMENT 'BMI (可選)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 2. 數據庫連接配置

系統使用現有的數據庫連接設置。如果需要修改，請編輯 `save_health_data.php` 文件中的以下部分：

```php
$host = 'localhost';
$dbname = 'test'; // 使用現有的數據庫
$username = 'root';
$password = '';
$port = 3307; // 使用現有的端口
```

### 3. 文件說明

- `index.php` - 主頁面，包含新增數據按鈕和彈出視窗
- `save_health_data.php` - 處理數據保存的後端腳本
- `create_database.sql` - 數據庫和表結構創建腳本

## 使用方法

1. 登入系統後，進入基本資料頁面
2. 點擊右上角的「新增數據」按鈕
3. 在彈出的視窗中填寫健康數據：
   - 測量日期（必填）
   - 骨骼肌重 (kg)（必填）
   - 體脂肪重 (kg)（必填）
   - 體脂率 (%)（必填）
   - 基礎代謝量 (kcal)（必填）
   - BMI（可選）
4. 點擊「保存數據」按鈕
5. 系統會顯示保存成功或錯誤消息

## 功能特點

- 響應式設計，適配不同屏幕尺寸
- 表單驗證，確保必填字段不為空
- 實時錯誤處理和用戶反饋
- 自動設置今天的日期為默認值
- 數據保存後自動重置表單

## 注意事項

- 確保 PHP 環境已啟用 PDO 擴展
- 確保數據庫用戶有適當的權限
- 建議在生產環境中使用更安全的數據庫連接方式
- 可以根據需要調整表單字段和驗證規則 