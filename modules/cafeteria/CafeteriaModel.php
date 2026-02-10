<?php
/**
 * Cafeteria Model
 * Handles menu planning, meal attendance, and dietary restrictions
 */

class CafeteriaModel extends Model {
    
    // --- Meal Types & Menus ---
    public function getMealTypes() {
        return $this->select("SELECT * FROM cafeteria_meal_types ORDER BY start_time");
    }

    public function getMenus($startDate, $endDate = null) {
        $sql = "SELECT m.*, t.type_name, t.start_time 
                FROM cafeteria_menus m
                JOIN cafeteria_meal_types t ON m.meal_type_id = t.id
                WHERE m.meal_date >= ?";
        $params = [$startDate];
        if ($endDate) {
            $sql .= " AND m.meal_date <= ?";
            $params[] = $endDate;
        }
        $sql .= " ORDER BY m.meal_date ASC, t.start_time ASC";
        return $this->select($sql, $params);
    }

    public function getTodayMenu() {
        return $this->getMenus(date('Y-m-d'), date('Y-m-d'));
    }

    public function addMenu($data) {
        return $this->insert('cafeteria_menus', $data);
    }

    // --- Dietary Restrictions ---
    public function getDietaryRestrictions($userId = null) {
        $sql = "SELECT d.*, u.full_name 
                FROM cafeteria_dietary_restrictions d
                JOIN users u ON d.user_id = u.id";
        $params = [];
        if ($userId) {
            $sql .= " WHERE d.user_id = ?";
            $params[] = $userId;
        }
        return $this->select($sql, $params);
    }

    public function addRestriction($data) {
        return $this->insert('cafeteria_dietary_restrictions', $data);
    }

    // --- Meal Logs & Attendance ---
    public function getMealLogs($menuId) {
        return $this->select("
            SELECT l.*, u.full_name as student_name, s.full_name as staff_name
            FROM cafeteria_meal_logs l
            JOIN users u ON l.user_id = u.id
            JOIN users s ON l.marked_by = s.id
            WHERE l.menu_id = ?
        ", [$menuId]);
    }

    public function logMeal($data) {
        // Prevent double logging
        $exists = $this->selectOne("SELECT id FROM cafeteria_meal_logs WHERE user_id = ? AND menu_id = ?", [$data['user_id'], $data['menu_id']]);
        if ($exists) return false;
        return $this->insert('cafeteria_meal_logs', $data);
    }

    // --- Analytics ---
    public function getAttendanceStats($menuId) {
        $total = $this->selectOne("SELECT COUNT(*) as count FROM cafeteria_meal_logs WHERE menu_id = ?", [$menuId]);
        return $total['count'] ?? 0;
    }
}
