<?php
require_once __DIR__ . '/../../config/config.php';

if (!isAdminLoggedIn()) {
    redirect(BASE_URL . 'admin/login.php');
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - ' : ''; ?>Administration - PhoneStore</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <i class="fas fa-mobile-alt"></i>
                <span>PhoneStore Admin</span>
            </div>
            <nav class="sidebar-nav">
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i> Tableau de bord
                </a>
                <a href="<?php echo BASE_URL; ?>admin/products.php" class="<?php echo $currentPage === 'products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box"></i> Produits
                </a>
                <a href="<?php echo BASE_URL; ?>admin/categories.php" class="<?php echo $currentPage === 'categories.php' ? 'active' : ''; ?>">
                    <i class="fas fa-tags"></i> Catégories
                </a>
                <a href="<?php echo BASE_URL; ?>admin/orders.php" class="<?php echo $currentPage === 'orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart"></i> Commandes
                </a>
                <a href="<?php echo BASE_URL; ?>admin/messages.php" class="<?php echo $currentPage === 'messages.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i> Messages
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="<?php echo BASE_URL; ?>" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Voir le site
                </a>
                <a href="<?php echo BASE_URL; ?>admin/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Déconnexion
                </a>
            </div>
        </aside>
        
        <main class="admin-main">
            <header class="admin-header">
                <h1><?php echo isset($pageTitle) ? escape($pageTitle) : 'Administration'; ?></h1>
                <div class="admin-user">
                    <i class="fas fa-user-circle"></i>
                    <span><?php 
                        $adminUser = getAdminUser();
                        echo $adminUser ? escape($adminUser['username']) : 'Admin';
                    ?></span>
                </div>
            </header>
            <div class="admin-content">

