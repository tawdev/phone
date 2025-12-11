<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Cart.php';
require_once __DIR__ . '/../classes/Product.php';

header('Content-Type: application/json');

Cart::init();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    $product_id = intval($input['product_id'] ?? 0);
    $quantity = intval($input['quantity'] ?? 1);
    
    if ($action === 'add' && $product_id > 0) {
        $product = new Product();
        $prod = $product->getById($product_id);
        
        if ($prod && $prod['stock'] >= $quantity) {
            Cart::add($product_id, $quantity);
            echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Stock insuffisant']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Action invalide']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] === 'get_count') {
        echo json_encode(['count' => Cart::getTotalItems()]);
    } else {
        echo json_encode(['count' => 0]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
}

