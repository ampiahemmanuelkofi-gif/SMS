<?php
/**
 * Base Model Class
 * 
 * Provides PDO wrapper methods for database operations
 */

class Model {
    
    protected $db;
    protected $table;
    
    public function __construct() {
        $this->db = getDbConnection();
    }
    
    /**
     * Select records from database
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for prepared statement
     * @return array Results
     */
    protected function select($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database Select Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Select single record
     * 
     * @param string $sql SQL query
     * @param array $params Parameters for prepared statement
     * @return array|null Single record or null
     */
    protected function selectOne($sql, $params = []) {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ? $result : null;
        } catch (PDOException $e) {
            error_log("Database SelectOne Error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Insert record into database
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @return int|bool Last insert ID or false on failure
     */
    protected function insert($table, $data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            $stmt->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Database Insert Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update records in database
     * 
     * @param string $table Table name
     * @param array $data Associative array of column => value
     * @param string $where WHERE clause
     * @param array $whereParams Parameters for WHERE clause
     * @return bool Success status
     */
    protected function update($table, $data, $where, $whereParams = []) {
        try {
            $setParts = [];
            foreach (array_keys($data) as $key) {
                $setParts[] = "{$key} = :{$key}";
            }
            $setClause = implode(', ', $setParts);
            
            $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
            
            $stmt = $this->db->prepare($sql);
            
            // Bind data values
            foreach ($data as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            
            // Bind where parameters
            foreach ($whereParams as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Database Update Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete records from database
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return bool Success status
     */
    protected function delete($table, $where, $params = []) {
        try {
            $sql = "DELETE FROM {$table} WHERE {$where}";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database Delete Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Count records
     * 
     * @param string $table Table name
     * @param string $where WHERE clause (optional)
     * @param array $params Parameters for WHERE clause
     * @return int Count
     */
    protected function count($table, $where = '', $params = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM {$table}";
            if (!empty($where)) {
                $sql .= " WHERE {$where}";
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            
            return $result ? (int)$result['count'] : 0;
        } catch (PDOException $e) {
            error_log("Database Count Error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Check if record exists
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params Parameters for WHERE clause
     * @return bool
     */
    protected function exists($table, $where, $params = []) {
        return $this->count($table, $where, $params) > 0;
    }
    
    /**
     * Begin transaction
     */
    protected function beginTransaction() {
        $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    protected function commit() {
        $this->db->commit();
    }
    
    /**
     * Rollback transaction
     */
    protected function rollback() {
        $this->db->rollBack();
    }
}
