<?php
// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Basic test response
header('Content-Type: application/json');

try {
    // Load config
    $config = require_once __DIR__ . '/../config/config.php';
    
    // Test file system permissions
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }

    // Test database connection
    $db = new mysqli(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['database']
    );

    if ($db->connect_error) {
        throw new Exception("Database connection failed: " . $db->connect_error);
    }

    // Test creating table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS telegram_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        chat_id VARCHAR(50),
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$db->query($sql)) {
        throw new Exception("Table creation failed: " . $db->error);
    }

    // Test response
    $response = [
        'status' => 'success',
        'message' => 'All tests successful',
        'details' => [
            'php_version' => PHP_VERSION,
            'time' => date('Y-m-d H:i:s'),
            'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
            'database' => 'Connected successfully',
            'table' => 'telegram_logs table ready'
        ]
    ];
    
    echo json_encode($response);
    $db->close();

} catch (Throwable $e) {
    error_log("Test error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 