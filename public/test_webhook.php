<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';

// Simulate a Telegram update
$testUpdate = [
    'update_id' => 123456789,
    'message' => [
        'message_id' => 1,
        'from' => [
            'id' => 123456,
            'first_name' => 'Test',
            'username' => 'test_user'
        ],
        'chat' => [
            'id' => 123456,
            'first_name' => 'Test',
            'username' => 'test_user',
            'type' => 'private'
        ],
        'date' => time(),
        'text' => 'Test message'
    ]
];

// Simulate the webhook request
$_SERVER['REQUEST_METHOD'] = 'POST';
file_put_contents('php://input', json_encode($testUpdate));

$handler = new \App\Handler\TelegramWebhookHandler($config);
$handler->handleRequest(); 