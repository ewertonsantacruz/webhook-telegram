<?php
// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

try {
    // Simulate a Telegram message update
    $testUpdate = [
        'update_id' => 123456789,
        'message' => [
            'message_id' => 1,
            'from' => [
                'id' => 12345,
                'first_name' => 'Test',
                'username' => 'test_user'
            ],
            'chat' => [
                'id' => 12345,
                'first_name' => 'Test',
                'username' => 'test_user',
                'type' => 'private'
            ],
            'date' => time(),
            'text' => 'Test message from webhook simulator'
        ]
    ];

    // Send POST request to webhook handler
    $ch = curl_init('https://ecomtora.lovestoblog.com/webhook-telegram/src/telegram_webhook.php');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($testUpdate),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json']
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo json_encode([
        'status' => 'success',
        'http_code' => $httpCode,
        'response' => json_decode($response, true),
        'curl_error' => $error ?: null
    ], JSON_PRETTY_PRINT);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 