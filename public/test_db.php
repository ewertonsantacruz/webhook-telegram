<?php
$config = require_once __DIR__ . '/../config/config.php';

try {
    // Try to connect
    $db = new mysqli(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['database']
    );

    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }

    // Try to create table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS telegram_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        chat_id VARCHAR(50),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$db->query($sql)) {
        throw new Exception("Table creation failed: " . $db->error);
    }

    // Try to insert a test record
    $stmt = $db->prepare("INSERT INTO telegram_logs (chat_id, message) VALUES (?, ?)");
    $testChatId = "TEST";
    $testMessage = "Database test at " . date('Y-m-d H:i:s');
    $stmt->bind_param("ss", $testChatId, $testMessage);
    
    if (!$stmt->execute()) {
        throw new Exception("Test insert failed: " . $stmt->error);
    }

    // Try to read records
    $result = $db->query("SELECT * FROM telegram_logs ORDER BY created_at DESC LIMIT 5");
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Database connection and operations successful',
        'recent_logs' => $rows
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_PRETTY_PRINT);
} 