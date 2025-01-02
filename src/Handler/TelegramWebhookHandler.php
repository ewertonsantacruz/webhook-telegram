<?php
namespace App\Handler;

use App\Database\DatabaseConnection;

class TelegramWebhookHandler {
    private $config;
    private $update;
    private $logFile;
    private $db;

    public function __construct($config) {
        $this->config = $config;
        $this->logFile = __DIR__ . '/../../logs/messages.log';
        $this->connectDB();
    }

    private function connectDB() {
        try {
            $dbConnection = new DatabaseConnection($this->config['database']);
            $this->db = $dbConnection->getConnection();
            $this->logError("Database connection successful");
        } catch (\Exception $e) {
            $this->logError("Database connection error: " . $e->getMessage());
        }
    }

    public function handleRequest() {
        try {
            $rawInput = file_get_contents("php://input");
            $this->logError("Raw input received: " . $rawInput);

            $this->update = json_decode($rawInput, true);
            
            if (!$this->update) {
                throw new \Exception("Invalid update received: " . json_last_error_msg());
            }

            $this->logMessage();
            $this->forwardToWebhook();
            
            http_response_code(200);
            echo json_encode(['status' => 'success']);
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    private function logMessage() {
        try {
            // Log to file
            $logEntry = date('Y-m-d H:i:s') . ' - ' . json_encode($this->update) . PHP_EOL;
            file_put_contents($this->logFile, $logEntry, FILE_APPEND);

            // Log to database
            if (isset($this->update['message'])) {
                $chatId = $this->update['message']['chat']['id'];
                $message = $this->update['message']['text'] ?? '';
                
                $stmt = $this->db->prepare("INSERT INTO telegram_logs (chat_id, message) VALUES (?, ?)");
                if (!$stmt) {
                    throw new \Exception("Prepare failed: " . $this->db->error);
                }
                
                $stmt->bind_param("ss", $chatId, $message);
                if (!$stmt->execute()) {
                    throw new \Exception("Execute failed: " . $stmt->error);
                }
                
                $stmt->close();
            }
        } catch (\Exception $e) {
            $this->logError("Error in logMessage: " . $e->getMessage());
        }
    }

    private function forwardToWebhook() {
        if (!isset($this->update['message'])) {
            return;
        }

        $payload = json_encode([
            'chat_id' => $this->update['message']['chat']['id'],
            'message' => $this->update['message']['text'] ?? '',
            'sender' => $this->update['message']['from'] ?? null,
            'timestamp' => $this->update['message']['date'] ?? time()
        ]);

        $ch = curl_init($this->config['webhook_url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("Webhook forward failed: " . $error);
        }
    }

    private function logError($message) {
        $errorLog = date('Y-m-d H:i:s') . ' - ERROR: ' . $message . PHP_EOL;
        file_put_contents($this->logFile, $errorLog, FILE_APPEND);
    }
}