<?php
require_once __DIR__ . '/../config/database.php';

class TypeCategory {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les types de catégories
    public function getAll($category_id = null) {
        $sql = "SELECT tc.*, c.name as category_name 
                FROM types_categories tc 
                JOIN categories c ON tc.category_id = c.id 
                WHERE 1=1";
        $params = [];
        
        if ($category_id !== null) {
            $sql .= " AND tc.category_id = ?";
            $params[] = $category_id;
        }
        
        $sql .= " ORDER BY tc.name";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Récupérer un type de catégorie par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT tc.*, c.name as category_name 
                                    FROM types_categories tc 
                                    JOIN categories c ON tc.category_id = c.id 
                                    WHERE tc.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Récupérer les types d'une catégorie spécifique
    public function getByCategoryId($category_id) {
        $stmt = $this->db->prepare("SELECT * FROM types_categories WHERE category_id = ? ORDER BY name");
        $stmt->execute([$category_id]);
        return $stmt->fetchAll();
    }
    
    // Créer un type de catégorie
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO types_categories (name, category_id) VALUES (?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['category_id']
        ]);
    }
    
    // Mettre à jour un type de catégorie
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE types_categories SET name = ?, category_id = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['category_id'],
            $id
        ]);
    }
    
    // Supprimer un type de catégorie
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM types_categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

