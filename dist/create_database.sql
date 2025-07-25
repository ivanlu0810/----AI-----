-- 創建數據庫
CREATE DATABASE IF NOT EXISTS health_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 使用數據庫
USE health_tracker;

-- 創建用戶表
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 創建健康記錄表
CREATE TABLE IF NOT EXISTS health_records (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    test_date DATE NOT NULL,
    skeletal_muscle DECIMAL(5,2) NOT NULL COMMENT '骨骼肌重 (kg)',
    body_fat DECIMAL(5,2) NOT NULL COMMENT '體脂肪重 (kg)',
    fat_percentage DECIMAL(5,2) NOT NULL COMMENT '體脂率 (%)',
    basal_metabolism DECIMAL(8,2) NOT NULL COMMENT '基礎代謝量 (kcal)',
    bmi DECIMAL(4,2) NULL COMMENT 'BMI (可選)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- 插入測試用戶數據（可選）
INSERT INTO users (username, email, password) VALUES 
('test_user', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi') -- password: password
ON DUPLICATE KEY UPDATE username = username;

-- 插入一些測試健康數據（可選）
INSERT INTO health_records (user_id, test_date, skeletal_muscle, body_fat, fat_percentage, basal_metabolism, bmi) VALUES 
(1, '2024-01-15', 25.5, 15.2, 18.5, 1650.0, 22.3),
(1, '2024-01-22', 26.1, 14.8, 17.9, 1680.0, 22.1),
(1, '2024-01-29', 26.8, 14.5, 17.2, 1700.0, 21.9)
ON DUPLICATE KEY UPDATE test_date = test_date; 