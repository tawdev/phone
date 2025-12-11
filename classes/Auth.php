<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Connexion admin
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admins WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION[ADMIN_SESSION_KEY] = true;
            $_SESSION[ADMIN_USER_KEY] = [
                'id' => $admin['id'],
                'username' => $admin['username'],
                'email' => $admin['email']
            ];
            return true;
        }
        
        return false;
    }
    
    // Déconnexion
    public function logout() {
        unset($_SESSION[ADMIN_SESSION_KEY]);
        unset($_SESSION[ADMIN_USER_KEY]);
        session_destroy();
    }
    
    // Vérifier si connecté
    public function isLoggedIn() {
        return isAdminLoggedIn();
    }
}

