<?php
/**
 * Library Model
 * Handles cataloging, inventory, circulation, and fines
 */

class LibraryModel extends Model {
    
    // --- Categories ---
    public function getCategories() {
        return $this->select("SELECT * FROM library_categories ORDER BY name");
    }

    // --- Catalog (Books) ---
    public function getBooks($search = '', $category = null) {
        $sql = "SELECT b.*, c.name as category_name, 
                (SELECT COUNT(*) FROM library_inventory WHERE book_id = b.id) as total_copies,
                (SELECT COUNT(*) FROM library_inventory WHERE book_id = b.id AND status = 'available') as available_copies
                FROM library_books b
                LEFT JOIN library_categories c ON b.category_id = c.id
                WHERE 1=1";
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($category) {
            $sql .= " AND b.category_id = ?";
            $params[] = $category;
        }
        
        $sql .= " ORDER BY b.title";
        return $this->select($sql, $params);
    }

    public function getBookById($id) {
        return $this->selectOne("SELECT b.*, c.name as category_name FROM library_books b LEFT JOIN library_categories c ON b.category_id = c.id WHERE b.id = ?", [$id]);
    }

    public function addBook($data) {
        return $this->insert('library_books', $data);
    }

    // --- Inventory (Physical Copies) ---
    public function getInventoryByBookId($bookId) {
        return $this->select("SELECT * FROM library_inventory WHERE book_id = ? ORDER BY barcode", [$bookId]);
    }

    public function addInventoryItem($data) {
        return $this->insert('library_inventory', $data);
    }

    public function updateInventoryStatus($id, $status) {
        return $this->update('library_inventory', ['status' => $status], "id = :id", [':id' => $id]);
    }

    public function getInventoryByBarcode($barcode) {
        return $this->selectOne("SELECT i.*, b.title, b.author FROM library_inventory i JOIN library_books b ON i.book_id = b.id WHERE i.barcode = ?", [$barcode]);
    }

    // --- Circulation ---
    public function issueBook($inventoryId, $userId, $issuedBy, $dueDate) {
        $this->db->beginTransaction();
        try {
            // 1. Create circulation record
            $this->insert('library_circulation', [
                'inventory_id' => $inventoryId,
                'user_id' => $userId,
                'issued_by' => $issuedBy,
                'due_date' => $dueDate,
                'status' => 'issued'
            ]);
            
            // 2. Update inventory status
            $this->update('library_inventory', ['status' => 'issued'], "id = :id", [':id' => $inventoryId]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function returnBook($circulationId, $returnedTo, $returnedAt) {
        $circulation = $this->selectOne("SELECT * FROM library_circulation WHERE id = ?", [$circulationId]);
        if (!$circulation) return false;

        $this->db->beginTransaction();
        try {
            // 1. Update circulation record
            $this->update('library_circulation', [
                'return_date' => $returnedAt,
                'returned_to' => $returnedTo,
                'status' => 'returned'
            ], "id = :id", [':id' => $circulationId]);
            
            // 2. Update inventory status
            $this->update('library_inventory', ['status' => 'available'], "id = :id", [':id' => $circulation['inventory_id']]);
            
            // 3. Check for fines
            $dueDate = strtotime($circulation['due_date']);
            $returnDate = strtotime($returnedAt);
            if ($returnDate > $dueDate) {
                $daysOverdue = ceil(($returnDate - $dueDate) / (60 * 60 * 24));
                $fineAmount = $daysOverdue * 1.00; // Example: 1 GHS per day
                $this->insert('library_fines', [
                    'circulation_id' => $circulationId,
                    'amount' => $fineAmount,
                    'status' => 'unpaid'
                ]);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getActiveLoans($userId = null) {
        $sql = "SELECT c.*, i.barcode, b.title, u.full_name as borrower_name 
                FROM library_circulation c
                JOIN library_inventory i ON c.inventory_id = i.id
                JOIN library_books b ON i.book_id = b.id
                JOIN users u ON c.user_id = u.id
                WHERE c.status = 'issued'";
        $params = [];
        if ($userId) {
            $sql .= " AND c.user_id = ?";
            $params[] = $userId;
        }
        return $this->select($sql, $params);
    }

    // --- Fines ---
    public function getFines($status = 'unpaid') {
        return $this->select("
            SELECT f.*, b.title, u.full_name as borrower_name, c.due_date, c.return_date
            FROM library_fines f
            JOIN library_circulation c ON f.circulation_id = c.id
            JOIN library_inventory i ON c.inventory_id = i.id
            JOIN library_books b ON i.book_id = b.id
            JOIN users u ON c.user_id = u.id
            WHERE f.status = ?
        ", [$status]);
    }
}
