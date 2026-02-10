<?php
/**
 * Inventory Model
 * Handles fixed assets, consumable stock, and maintenance logs
 */

class InventoryModel extends Model {
    
    // --- Categories ---
    public function getCategories($type = null) {
        $sql = "SELECT * FROM inventory_categories";
        $params = [];
        if ($type) {
            $sql .= " WHERE category_type = ?";
            $params[] = $type;
        }
        $sql .= " ORDER BY category_name";
        return $this->select($sql, $params);
    }

    // --- Fixed Assets ---
    public function getAssets($categoryId = null) {
        $sql = "SELECT a.*, c.category_name, u.full_name as assignee 
                FROM inventory_assets a
                JOIN inventory_categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.assigned_to = u.id
                WHERE 1=1";
        $params = [];
        if ($categoryId) {
            $sql .= " AND a.category_id = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY a.asset_name";
        return $this->select($sql, $params);
    }

    public function addAsset($data) {
        return $this->insert('inventory_assets', $data);
    }

    public function updateAssetStatus($assetId, $status) {
        return $this->update('inventory_assets', ['status' => $status], "id = :id", [':id' => $assetId]);
    }

    // --- Consumables ---
    public function getConsumables() {
        return $this->select("
            SELECT c.*, cat.category_name 
            FROM inventory_consumables c
            JOIN inventory_categories cat ON c.category_id = cat.id
            ORDER BY c.item_name
        ");
    }

    public function addConsumable($data) {
        return $this->insert('inventory_consumables', $data);
    }

    public function updateStock($consumableId, $quantity, $logType, $reason, $userId) {
        $this->db->beginTransaction();
        try {
            // Update stock level
            $op = $logType == 'add' ? '+' : '-';
            $sql = "UPDATE inventory_consumables SET current_stock = current_stock $op ? WHERE id = ?";
            $this->db->prepare($sql)->execute([$quantity, $consumableId]);

            // Add to log
            $this->insert('inventory_stock_logs', [
                'consumable_id' => $consumableId,
                'log_type' => $logType,
                'quantity' => $quantity,
                'reason' => $reason,
                'performed_by' => $userId
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // --- Maintenance ---
    public function getMaintenanceLogs($assetId = null) {
        $sql = "SELECT m.*, a.asset_name 
                FROM inventory_maintenance m
                JOIN inventory_assets a ON m.asset_id = a.id
                WHERE 1=1";
        $params = [];
        if ($assetId) {
            $sql .= " AND m.asset_id = ?";
            $params[] = $assetId;
        }
        $sql .= " ORDER BY m.maintenance_date DESC";
        return $this->select($sql, $params);
    }

    public function addMaintenanceLog($data) {
        return $this->insert('inventory_maintenance', $data);
    }

    // --- Dashboard ---
    public function getLowStockItems() {
        return $this->select("SELECT * FROM inventory_consumables WHERE current_stock <= min_stock_level");
    }

    public function getInventoryValue() {
        $result = $this->selectOne("SELECT SUM(purchase_cost) as total FROM inventory_assets WHERE status = 'active'");
        return $result['total'] ?? 0;
    }
}
