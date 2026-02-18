<?php
require_once __DIR__ . '/../common/db.php';

class CategoryQueries {
    private $conn;
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function getAllCategories() {
        $query = "SELECT * FROM mms_categories WHERE status = 1 ORDER BY category_name ASC";
        $result = $this->conn->query($query);
        
        $categories = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        }
        return $categories;
    }
    
    public function getCategoryById($id) {
        $query = "SELECT * FROM mms_categories WHERE category_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    public function addCategory($category_name, $description = '') {
        $query = "INSERT INTO mms_categories (category_name, description, status) VALUES (?, ?, 1)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $category_name, $description);
        
        return $stmt->execute();
    }
}
?>