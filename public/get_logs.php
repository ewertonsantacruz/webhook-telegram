<?php
header('Content-Type: application/json');
$config = require_once __DIR__ . '/../config/config.php';

try {
    $db = new mysqli(
        $config['database']['host'],
        $config['database']['username'],
        $config['database']['password'],
        $config['database']['database']
    );

    if ($db->connect_error) {
        throw new Exception("Database connection failed");
    }

    // Get the most recent 100 messages
    $query = "SELECT * FROM telegram_logs ORDER BY created_at DESC LIMIT 100";
    $result = $db->query($query);
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = [
            'id' => $row['id'],
            'chat_id' => htmlspecialchars($row['chat_id']),
            'message' => htmlspecialchars($row['message']),
            'created_at' => date('M j, Y g:i:s A', strtotime($row['created_at']))
        ];
    }

    echo json_encode($logs);
    $db->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 