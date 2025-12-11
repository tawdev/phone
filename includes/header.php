<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Cart.php';
Cart::init();
$cartCount = Cart::getTotalItems();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - ' : ''; ?>PhoneStore - Votre boutique de téléphones</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/animations.css">
    <?php if (isset($pageStyle) && !empty($pageStyle)): ?>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/<?php echo escape($pageStyle); ?>.css">
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo BASE_URL; ?>">
                        <i class="fas fa-mobile-alt"></i>
                        <span>PhoneStore</span>
                    </a>
                </div>
                <nav class="nav">
                    <ul class="nav-menu">
                        <li><a href="<?php echo BASE_URL; ?>">Accueil</a></li>
                        <li><a href="<?php echo BASE_URL; ?>categories.php">Catégories</a></li>
                        <li><a href="<?php echo BASE_URL; ?>produits.php">Produits</a></li>
                        <li><a href="<?php echo BASE_URL; ?>apropos.php">À propos</a></li>
                        <li><a href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>
                    </ul>
                </nav>
                <div class="header-actions">
                    <a href="<?php echo BASE_URL; ?>cart.php" class="cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-count"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>
    <main class="main-content">

