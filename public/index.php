<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';
$handler = new \App\Handler\TelegramWebhookHandler($config);
$handler->handleRequest();