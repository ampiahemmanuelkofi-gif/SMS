<?php
/**
 * Base ApiController
 * Handles JSON responses, API authentication, and rate limiting
 */

class ApiControllerBase extends Controller {
    
    protected $client = null;
    protected $startTime;

    public function __construct() {
        $this->startTime = microtime(true);
        $this->authenticate();
        $this->checkRateLimit();
    }

    /**
     * Authenticate Request using API Key & Secret
     */
    protected function authenticate() {
        $apiKey = $_SERVER['HTTP_X_API_KEY'] ?? null;
        $apiSecret = $_SERVER['HTTP_X_API_SECRET'] ?? null;

        if (!$apiKey || !$apiSecret) {
            $this->jsonResponse(['error' => 'API Key and Secret required'], 401);
        }

        $db = getDbConnection();
        $stmt = $db->prepare("SELECT * FROM api_keys WHERE api_key = ? AND api_secret = ? AND is_active = 1");
        $stmt->execute([$apiKey, $apiSecret]);
        $this->client = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$this->client) {
            $this->jsonResponse(['error' => 'Invalid or inactive API Key'], 403);
        }

        // Update last used
        $db->prepare("UPDATE api_keys SET last_used_at = NOW() WHERE id = ?")->execute([$this->client['id']]);
    }

    /**
     * Simple Rate Limiting Logic
     */
    protected function checkRateLimit() {
        $db = getDbConnection();
        $limit = 100; // Requests per minute
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM api_logs WHERE api_key_id = ? AND requested_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)");
        $stmt->execute([$this->client['id']]);
        $requestCount = $stmt->fetchColumn();

        if ($requestCount >= $limit) {
            $this->jsonResponse(['error' => 'Rate limit exceeded (100 req/min)'], 429);
        }
    }

    /**
     * Send JSON Response
     */
    protected function jsonResponse($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        
        // Log request before exit
        $this->logRequest($status);
        
        echo json_encode($data);
        exit;
    }

    /**
     * Log API usage
     */
    protected function logRequest($status) {
        $duration = round((microtime(true) - $this->startTime) * 1000);
        $db = getDbConnection();
        $stmt = $db->prepare("INSERT INTO api_logs (api_key_id, endpoint, method, ip_address, response_code, duration_ms) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $this->client['id'],
            $_SERVER['REQUEST_URI'] ?? 'unknown',
            $_SERVER['REQUEST_METHOD'] ?? 'GET',
            $_SERVER['REMOTE_ADDR'] ?? null,
            $status,
            $duration
        ]);
    }
}
