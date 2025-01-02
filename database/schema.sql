CREATE TABLE IF NOT EXISTS telegram_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id VARCHAR(255) NOT NULL,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_chat_id (chat_id),
    INDEX idx_created_at (created_at)
); 