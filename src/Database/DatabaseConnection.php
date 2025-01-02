<?php
namespace App\Database;

class DatabaseConnection {
    private $connection;
    private $config;

    public function __construct(array $config) {
        $this->config = $config;
    }

    public function connect() {
        try {
            $this->connection = new \mysqli(
                $this->config['host'],
                $this->config['username'],
                $this->config['password'],
                $this->config['database']
            );

            if ($this->connection->connect_error) {
                throw new \Exception("Database connection failed: " . $this->connection->connect_error);
            }

            return $this->connection;
        } catch (\Exception $e) {
            throw new \Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection ?? $this->connect();
    }
}