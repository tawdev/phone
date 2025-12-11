<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$category = new Category();

// Récupérer la catégorie sélectionnée
$selectedCategory = null;
$categorySlug = $_GET['category'] ?? null;
if ($categorySlug) {
    $selectedCategory = $category->getBySlug($categorySlug);
}

// Récupérer les produits
$products = $selectedCategory 
    ? $product->getAll($selectedCategory['id'])
    : $product->getAll();

// Recherche
$searchQuery = $_GET['search'] ?? '';
if ($searchQuery) {
    $products = $product->search($searchQuery);
}

// Récupérer toutes les catégories
$categories = $category->getAll();

$pageTitle = "Produits";
$pageStyle = "products";
include 'includes/header.php';
?>

<section class="products-page section">
    <div class="container">
        <h1 class="page-title">Nos Produits</h1>
        
        <div class="products-layout">
            <aside class="products-sidebar">
                <div class="sidebar-section">
                    <h3>Catégories</h3>
                    <ul class="category-list">
                        <li><a href="produits.php" class="<?php echo !$selectedCategory ? 'active' : ''; ?>">Tous les produits</a></li>
                        <?php foreach ($categories as $cat): ?>
                            <li>
                                <a href="produits.php?category=<?php echo $cat['slug']; ?>" 
                                   class="<?php echo ($selectedCategory && $selectedCategory['id'] == $cat['id']) ? 'active' : ''; ?>">
                                    <?php echo escape($cat['name']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            
            <div class="products-main">
                <div class="products-toolbar">
                    <form method="GET" class="search-form">
                        <?php if ($categorySlug): ?>
                            <input type="hidden" name="category" value="<?php echo escape($categorySlug); ?>">
                        <?php endif; ?>
                        <input type="text" name="search" placeholder="Rechercher un produit..." 
                               value="<?php echo escape($searchQuery); ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="products-grid stagger-animate">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $prod): ?>
                            <div class="product-card hover-lift">
                                <?php if ($prod['old_price']): ?>
                                    <span class="badge badge-sale">Promo</span>
                                <?php endif; ?>
                                <div class="product-image">
                                    <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                         alt="<?php echo escape($prod['name']); ?>"
                                         class="product-image-zoom"
                                         onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo escape($prod['name']); ?></h3>
                                    <p class="product-category"><?php echo escape($prod['category_name']); ?></p>
                                    <div class="product-price">
                                        <?php if ($prod['old_price']): ?>
                                            <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                                        <?php endif; ?>
                                        <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                                    </div>
                                    <div class="product-actions">
                                        <a href="produit.php?slug=<?php echo $prod['slug']; ?>" class="btn btn-outline">Voir détails</a>
                                        <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $prod['id']; ?>">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Aucun produit trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

