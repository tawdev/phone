<?php
require_once __DIR__ . '/../config/database.php';

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Récupérer tous les produits
    public function getAll($category_id = null, $featured = null, $type_category_id = null) {
        $sql = "SELECT p.*, c.name as category_name, tc.name as type_category_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                LEFT JOIN types_categories tc ON p.type_category_id = tc.id 
                WHERE 1=1";
        $params = [];
        
        if ($category_id !== null) {
            $sql .= " AND p.category_id = ?";
            $params[] = $category_id;
        }
        
        if ($type_category_id !== null) {
            $sql .= " AND p.type_category_id = ?";
            $params[] = $type_category_id;
        }
        
        if ($featured !== null) {
            $sql .= " AND p.featured = ?";
            $params[] = $featured ? 1 : 0;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Récupérer un produit par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name, tc.name as type_category_name 
                                    FROM products p 
                                    JOIN categories c ON p.category_id = c.id 
                                    LEFT JOIN types_categories tc ON p.type_category_id = tc.id 
                                    WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Récupérer un produit par slug
    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name 
                                    FROM products p 
                                    JOIN categories c ON p.category_id = c.id 
                                    WHERE p.slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }
    
    // Créer un produit
    public function create($data) {
        $sql = "INSERT INTO products (name, slug, description, price, old_price, category_id, type_category_id, brand, image, stock, featured) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['price'],
            $data['old_price'] ?? null,
            $data['category_id'],
            $data['type_category_id'] ?? null,
            $data['brand'] ?? null,
            $data['image'] ?? null,
            $data['stock'] ?? 0,
            $data['featured'] ?? 0
        ]);
    }
    
    // Mettre à jour un produit
    public function update($id, $data) {
        $sql = "UPDATE products SET name = ?, slug = ?, description = ?, price = ?, old_price = ?, 
                category_id = ?, type_category_id = ?, brand = ?, image = ?, stock = ?, featured = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'],
            $data['price'],
            $data['old_price'] ?? null,
            $data['category_id'],
            $data['type_category_id'] ?? null,
            $data['brand'] ?? null,
            $data['image'] ?? null,
            $data['stock'] ?? 0,
            $data['featured'] ?? 0,
            $id
        ]);
    }
    
    // Supprimer un produit
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Rechercher des produits
    public function search($query) {
        $search = "%{$query}%";
        $stmt = $this->db->prepare("SELECT p.*, c.name as category_name 
                                    FROM products p 
                                    JOIN categories c ON p.category_id = c.id 
                                    WHERE p.name LIKE ? OR p.description LIKE ? OR p.brand LIKE ?
                                    ORDER BY p.created_at DESC");
        $stmt->execute([$search, $search, $search]);
        return $stmt->fetchAll();
    }
}

