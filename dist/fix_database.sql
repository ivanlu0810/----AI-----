-- 修復 inbody_records 表結構，確保支持多筆記錄
USE test;

-- 檢查表是否存在，如果不存在則創建（符合您的實際結構）
CREATE TABLE IF NOT EXISTS inbody_records (
    record_id CHAR(24) NOT NULL PRIMARY KEY,
    user_id CHAR(24) NULL,
    age VARCHAR(100) NOT NULL,
    `height-cm` FLOAT NULL,
    `weight-kg` FLOAT NULL,
    skeletal_muscle FLOAT NULL,
    body_fat FLOAT NULL,
    fat_percentage FLOAT NULL,
    basal_metabolism FLOAT NULL,
    bmi FLOAT NULL,
    Date DATE NOT NULL DEFAULT CURDATE()
);

-- 如果表已存在，確保沒有額外的唯一約束阻止多筆記錄
-- 檢查表結構
SHOW CREATE TABLE inbody_records;

-- 添加索引來提高查詢性能（可選）
-- CREATE INDEX idx_user_date ON inbody_records(user_id, Date); 