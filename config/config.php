<?php
// Configuration générale du site
session_start();

// Chemins
define('BASE_URL', 'http://localhost/Phone/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('UPLOAD_PATH', ROOT_PATH . 'uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Configuration de sécurité
define('ADMIN_SESSION_KEY', 'admin_logged_in');
define('ADMIN_USER_KEY', 'admin_user');

// Inclure la base de données
require_once __DIR__ . '/database.php';

// Fonction pour échapper les données (protection XSS)
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Fonction pour rediriger
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Fonction pour vérifier si admin est connecté
function isAdminLoggedIn() {
    return isset($_SESSION[ADMIN_SESSION_KEY]) && $_SESSION[ADMIN_SESSION_KEY] === true;
}

// Fonction pour obtenir l'utilisateur admin connecté
function getAdminUser() {
    return $_SESSION[ADMIN_USER_KEY] ?? null;
}

// Fonction pour formater le prix
function formatPrice($price) {
    return number_format($price, 2, ',', ' ') . ' DH';
}

// Fonction pour générer un slug
function generateSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

