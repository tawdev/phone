<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$category = new Category();

// Récupérer les produits en vedette
$featuredProducts = $product->getAll(null, true);

// Récupérer les dernières promotions
$allProducts = $product->getAll();
$promoProducts = array_filter($allProducts, function($p) {
    return !empty($p['old_price']);
});

// Récupérer toutes les catégories avec le nombre de produits
$categories = $category->getAll();
$categoriesWithCount = [];
foreach ($categories as $cat) {
    $products = $product->getAll($cat['id']);
    $categoriesWithCount[] = [
        'category' => $cat,
        'product_count' => count($products)
    ];
}

$pageTitle = "Accueil";
$pageStyle = "home";
include 'includes/header.php';
?>

<section class="hero" style="background-image: url('<?php echo BASE_URL; ?>assets/images/gemini-3-pro-image-preview (nano-banana-pro)_b_suprimer_text.png');">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title-animate">Bienvenue chez PhoneStore</h1>
            <p class="hero-animate-delay-1">Découvrez notre sélection de téléphones et accessoires de qualité</p>
            <a href="produits.php" class="btn btn-primary btn-animate hero-animate-delay-2">Voir les produits</a>
        </div>
    </div>
</section>

<section class="categories-section section">
    <div class="container">
        <h2 class="section-title">Nos Catégories</h2>
        <div class="categories-cards-grid stagger-animate">
            <?php if (!empty($categoriesWithCount)): ?>
                <?php foreach ($categoriesWithCount as $item): 
                    $cat = $item['category'];
                    $productCount = $item['product_count'];
                ?>
                    <div class="category-card-home hover-lift">
                        <a href="produits.php?category=<?php echo $cat['slug']; ?>" class="category-card-link">
                            <div class="category-card-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="category-card-content">
                                <h3><?php echo escape($cat['name']); ?></h3>
                                <?php if ($cat['description']): ?>
                                    <p class="category-card-description"><?php echo escape($cat['description']); ?></p>
                                <?php endif; ?>
                                <div class="category-card-footer">
                                    <span class="category-product-count">
                                        <i class="fas fa-box"></i>
                                        <?php echo $productCount; ?> produit<?php echo $productCount > 1 ? 's' : ''; ?>
                                    </span>
                                    <span class="category-arrow">
                                        <i class="fas fa-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-categories">Aucune catégorie disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="featured-products section">
    <div class="container">
        <h2 class="section-title">Produits en vedette</h2>
        <div class="products-grid stagger-animate">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach (array_slice($featuredProducts, 0, 4) as $prod): ?>
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
                <p>Aucun produit en vedette pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php if (!empty($promoProducts)): ?>
<section class="promotions section">
    <div class="container">
        <h2 class="section-title">Promotions du moment</h2>
        <div class="products-grid stagger-animate">
            <?php foreach (array_slice($promoProducts, 0, 4) as $prod): ?>
                <div class="product-card hover-lift">
                    <span class="badge badge-sale">-<?php echo round((($prod['old_price'] - $prod['price']) / $prod['old_price']) * 100); ?>%</span>
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
                            <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
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
        </div>
    </div>
</section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

