<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$category = new Category();

// Récupérer toutes les catégories avec le nombre de produits
$categories = $category->getAll();
$categoriesWithProducts = [];

foreach ($categories as $cat) {
    $products = $product->getAll($cat['id']);
    $categoriesWithProducts[] = [
        'category' => $cat,
        'products' => array_slice($products, 0, 4), // Limiter à 4 produits par catégorie
        'total_products' => count($products)
    ];
}

$pageTitle = "Catégories";
$pageStyle = "categories";
include 'includes/header.php';
?>

<section class="categories-page section">
    <div class="container">
        <h1 class="page-title">Nos Catégories</h1>
        <p class="page-subtitle">Découvrez notre sélection organisée par catégories</p>
        
        <div class="categories-grid stagger-animate">
            <?php foreach ($categoriesWithProducts as $item): 
                $cat = $item['category'];
                $products = $item['products'];
                $totalProducts = $item['total_products'];
            ?>
                <div class="category-card hover-lift">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="category-info">
                            <h2><?php echo escape($cat['name']); ?></h2>
                            <?php if ($cat['description']): ?>
                                <p class="category-description"><?php echo escape($cat['description']); ?></p>
                            <?php endif; ?>
                            <div class="category-meta">
                                <span class="product-count">
                                    <i class="fas fa-box"></i>
                                    <?php echo $totalProducts; ?> produit<?php echo $totalProducts > 1 ? 's' : ''; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($products)): ?>
                        <div class="category-products">
                            <div class="category-products-grid">
                                <?php foreach ($products as $prod): ?>
                                    <div class="category-product-item">
                                        <a href="produit.php?slug=<?php echo $prod['slug']; ?>">
                                            <div class="category-product-image">
                                                <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                                     alt="<?php echo escape($prod['name']); ?>"
                                                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                                <?php if ($prod['old_price']): ?>
                                                    <span class="badge badge-sale">Promo</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="category-product-info">
                                                <h4><?php echo escape($prod['name']); ?></h4>
                                                <div class="category-product-price">
                                                    <?php if ($prod['old_price']): ?>
                                                        <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                                                    <?php endif; ?>
                                                    <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if ($totalProducts > 4): ?>
                                <div class="category-view-more">
                                    <a href="produits.php?category=<?php echo $cat['slug']; ?>" class="btn btn-outline">
                                        Voir tous les produits (<?php echo $totalProducts; ?>)
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="category-view-more">
                                    <a href="produits.php?category=<?php echo $cat['slug']; ?>" class="btn btn-outline">
                                        Voir tous les produits
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="category-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Aucun produit dans cette catégorie pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($categories)): ?>
            <div class="no-categories">
                <i class="fas fa-folder-open"></i>
                <h2>Aucune catégorie disponible</h2>
                <p>Les catégories seront bientôt disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

