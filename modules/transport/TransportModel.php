<?php
/**
 * Transport Model
 * Handles vehicles, routes, assignments and maintenance
 */

class TransportModel extends Model {
    
    // --- Vehicles ---
    public function getVehicles() {
        return $this->select("SELECT * FROM transport_vehicles ORDER BY plate_number");
    }

    public function getVehicleById($id) {
        return $this->selectOne("SELECT * FROM transport_vehicles WHERE id = ?", [$id]);
    }

    public function addVehicle($data) {
        return $this->insert('transport_vehicles', $data);
    }

    public function updateVehicle($id, $data) {
        return $this->update('transport_vehicles', $data, "id = :id", [':id' => $id]);
    }

    // --- Routes ---
    public function getRoutes() {
        return $this->select("
            SELECT r.*, v.plate_number, v.vehicle_model 
            FROM transport_routes r
            LEFT JOIN transport_vehicles v ON r.vehicle_id = v.id
            ORDER BY r.route_name
        ");
    }

    public function getRouteById($id) {
        return $this->selectOne("SELECT * FROM transport_routes WHERE id = ?", [$id]);
    }

    public function addRoute($data) {
        return $this->insert('transport_routes', $data);
    }

    public function updateRoute($id, $data) {
        return $this->update('transport_routes', $data, "id = :id", [':id' => $id]);
    }

    // --- Assignments ---
    public function getAssignments($routeId = null) {
        $sql = "SELECT a.*, u.full_name as user_name, r.route_name, v.plate_number
                FROM transport_assignments a
                JOIN users u ON a.user_id = u.id
                JOIN transport_routes r ON a.route_id = r.id
                LEFT JOIN transport_vehicles v ON r.vehicle_id = v.id
                WHERE 1=1";
        $params = [];
        if ($routeId) {
            $sql .= " AND a.route_id = ?";
            $params[] = $routeId;
        }
        $sql .= " ORDER BY u.full_name";
        return $this->select($sql, $params);
    }

    public function assignTransport($data) {
        return $this->insert('transport_assignments', $data);
    }

    public function updateAssignmentStatus($id, $status) {
        return $this->update('transport_assignments', ['status' => $status], "id = :id", [':id' => $id]);
    }

    // --- Maintenance ---
    public function getMaintenanceLogs($vehicleId = null) {
        $sql = "SELECT m.*, v.plate_number 
                FROM transport_maintenance m
                JOIN transport_vehicles v ON m.vehicle_id = v.id
                WHERE 1=1";
        $params = [];
        if ($vehicleId) {
            $sql .= " AND m.vehicle_id = ?";
            $params[] = $vehicleId;
        }
        $sql .= " ORDER BY m.service_date DESC";
        return $this->select($sql, $params);
    }

    public function addMaintenanceEntry($data) {
        return $this->insert('transport_maintenance', $data);
    }
}
