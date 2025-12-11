<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$slug = $_GET['slug'] ?? '';

if (!$slug) {
    redirect('produits.php');
}

$prod = $product->getBySlug($slug);

if (!$prod) {
    redirect('produits.php');
}

$pageTitle = $prod['name'];
$pageStyle = "product-detail";
include 'includes/header.php';
?>

<section class="product-detail section">
    <div class="container">
        <div class="product-detail-layout">
            <div class="product-detail-image">
                <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                     alt="<?php echo escape($prod['name']); ?>"
                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
            </div>
            <div class="product-detail-info">
                <h1><?php echo escape($prod['name']); ?></h1>
                <p class="product-category"><?php echo escape($prod['category_name']); ?></p>
                
                <div class="product-price">
                    <?php if ($prod['old_price']): ?>
                        <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                    <?php endif; ?>
                    <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                </div>
                
                <div class="product-stock">
                    <?php if ($prod['stock'] > 0): ?>
                        <span class="in-stock"><i class="fas fa-check-circle"></i> En stock (<?php echo $prod['stock']; ?> disponibles)</span>
                    <?php else: ?>
                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Rupture de stock</span>
                    <?php endif; ?>
                </div>
                
                <div class="product-description">
                    <h3>Description</h3>
                    <p><?php echo nl2br(escape($prod['description'])); ?></p>
                </div>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <label>Quantité :</label>
                        <input type="number" id="productQuantity" min="1" max="<?php echo $prod['stock']; ?>" value="1">
                    </div>
                    <button class="btn btn-primary btn-large add-to-cart" 
                            data-product-id="<?php echo $prod['id']; ?>"
                            <?php echo $prod['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-cart-plus"></i> Ajouter au panier
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

