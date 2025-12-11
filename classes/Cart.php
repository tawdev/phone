<?php
require_once __DIR__ . '/../config/config.php';

class Cart {
    private static $cartKey = 'cart';
    
    // Initialiser le panier
    public static function init() {
        if (!isset($_SESSION[self::$cartKey])) {
            $_SESSION[self::$cartKey] = [];
        }
    }
    
    // Ajouter un produit au panier
    public static function add($product_id, $quantity = 1) {
        self::init();
        
        if (isset($_SESSION[self::$cartKey][$product_id])) {
            $_SESSION[self::$cartKey][$product_id] += $quantity;
        } else {
            $_SESSION[self::$cartKey][$product_id] = $quantity;
        }
    }
    
    // Mettre à jour la quantité
    public static function update($product_id, $quantity) {
        self::init();
        
        if ($quantity <= 0) {
            self::remove($product_id);
        } else {
            $_SESSION[self::$cartKey][$product_id] = $quantity;
        }
    }
    
    // Supprimer un produit
    public static function remove($product_id) {
        self::init();
        unset($_SESSION[self::$cartKey][$product_id]);
    }
    
    // Vider le panier
    public static function clear() {
        $_SESSION[self::$cartKey] = [];
    }
    
    // Obtenir le contenu du panier
    public static function getItems() {
        self::init();
        return $_SESSION[self::$cartKey];
    }
    
    // Obtenir le nombre total d'articles
    public static function getTotalItems() {
        self::init();
        return array_sum($_SESSION[self::$cartKey]);
    }
    
    // Obtenir le total du panier
    public static function getTotal($products) {
        $total = 0;
        foreach ($products as $product) {
            if (isset($_SESSION[self::$cartKey][$product['id']])) {
                $total += $product['price'] * $_SESSION[self::$cartKey][$product['id']];
            }
        }
        return $total;
    }
}

