<?php
require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/config.php';

try {
    $dbConnection = new \App\Database\DatabaseConnection($config['database']);
    $connection = $dbConnection->getConnection();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} 