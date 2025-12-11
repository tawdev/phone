<?php
require_once __DIR__ . '/../config/database.php';

class Order {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Créer une commande
    public function create($data, $items) {
        $this->db->beginTransaction();
        try {
            // Générer un numéro de commande unique
            $order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            // Créer la commande
            $stmt = $this->db->prepare("INSERT INTO orders (order_number, customer_name, customer_email, customer_phone, customer_address, total_amount, status) 
                                        VALUES (?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([
                $order_number,
                $data['customer_name'],
                $data['customer_email'],
                $data['customer_phone'],
                $data['customer_address'],
                $data['total_amount']
            ]);
            
            $order_id = $this->db->lastInsertId();
            
            // Ajouter les articles de la commande
            $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) 
                                        VALUES (?, ?, ?, ?, ?)");
            
            foreach ($items as $item) {
                $stmt->execute([
                    $order_id,
                    $item['product_id'],
                    $item['product_name'],
                    $item['quantity'],
                    $item['price']
                ]);
                
                // Mettre à jour le stock
                $updateStock = $this->db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStock->execute([$item['quantity'], $item['product_id']]);
            }
            
            $this->db->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    // Récupérer toutes les commandes
    public function getAll($status = null) {
        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];
        
        if ($status !== null) {
            $sql .= " AND status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // Récupérer une commande par ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Récupérer les articles d'une commande
    public function getItems($order_id) {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$order_id]);
        return $stmt->fetchAll();
    }
    
    // Mettre à jour le statut d'une commande
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE orders SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
    
    // Obtenir les statistiques
    public function getStats() {
        $stats = [];
        
        // Total des commandes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM orders");
        $stats['total_orders'] = $stmt->fetch()['total'];
        
        // Chiffre d'affaires total
        $stmt = $this->db->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
        $stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;
        
        // Commandes en attente
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM orders WHERE status = 'pending'");
        $stats['pending_orders'] = $stmt->fetch()['total'];
        
        // Commandes du mois
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
        $stats['month_orders'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}

