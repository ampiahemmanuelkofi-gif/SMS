<?php
/**
 * API Model
 * Manages API keys, webhooks, and usage logs
 */

class IntegrationsModel extends Model {
    
    // --- API Keys ---
    public function getKeys() {
        return $this->select("SELECT * FROM api_keys ORDER BY created_at DESC");
    }

    public function generateKey($clientName, $permissions = 'all') {
        $key = bin2hex(random_bytes(16)); // 32 chars
        $secret = bin2hex(random_bytes(32)); // 64 chars
        
        $data = [
            'client_name' => $clientName,
            'api_key' => $key,
            'api_secret' => $secret,
            'permissions' => $permissions
        ];
        
        if ($this->insert('api_keys', $data)) {
            return $data;
        }
        return false;
    }

    public function revokeKey($id) {
        return $this->update('api_keys', ['is_active' => 0], 'id = ?', [$id]);
    }

    // --- Webhooks ---
    public function getWebhooks() {
        return $this->select("SELECT * FROM api_webhooks ORDER BY created_at DESC");
    }

    public function addWebhook($data) {
        return $this->insert('api_webhooks', $data);
    }

    // --- Logs ---
    public function getRecentLogs($limit = 100) {
        return $this->select("
            SELECT l.*, k.client_name 
            FROM api_logs l
            JOIN api_keys k ON l.api_key_id = k.id
            ORDER BY l.requested_at DESC
            LIMIT ?
        ", [$limit]);
    }
}
