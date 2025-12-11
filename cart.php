<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
Cart::init();

// Gérer les actions du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($action === 'update' && isset($_POST['quantity'])) {
        $quantity = intval($_POST['quantity']);
        Cart::update($product_id, $quantity);
    } elseif ($action === 'remove') {
        Cart::remove($product_id);
    } elseif ($action === 'clear') {
        Cart::clear();
    }
    
    redirect('cart.php');
}

// Récupérer les produits du panier
$cartItems = Cart::getItems();
$products = [];
$total = 0;

foreach ($cartItems as $product_id => $quantity) {
    $prod = $product->getById($product_id);
    if ($prod) {
        $prod['cart_quantity'] = $quantity;
        $prod['subtotal'] = $prod['price'] * $quantity;
        $products[] = $prod;
        $total += $prod['subtotal'];
    }
}

$pageTitle = "Panier";
$pageStyle = "cart";
include 'includes/header.php';
?>

<section class="cart-page section">
    <div class="container">
        <h1 class="page-title">Mon Panier</h1>
        
        <?php if (empty($products)): ?>
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos produits et ajoutez-les à votre panier.</p>
                <a href="produits.php" class="btn btn-primary">Voir les produits</a>
            </div>
        <?php else: ?>
            <div class="cart-layout">
                <div class="cart-items">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $prod): ?>
                                <tr>
                                    <td data-label="Produit">
                                        <div class="cart-product">
                                            <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                                 alt="<?php echo escape($prod['name']); ?>"
                                                 onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                            <div>
                                                <h3><?php echo escape($prod['name']); ?></h3>
                                                <p class="product-category"><?php echo escape($prod['category_name']); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Prix"><?php echo formatPrice($prod['price']); ?></td>
                                    <td data-label="Quantité">
                                        <form method="POST" class="quantity-form">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                            <input type="number" name="quantity" value="<?php echo $prod['cart_quantity']; ?>" 
                                                   min="1" max="<?php echo $prod['stock']; ?>" 
                                                   onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td data-label="Total" class="subtotal"><?php echo formatPrice($prod['subtotal']); ?></td>
                                    <td data-label="Action">
                                        <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer ce produit ?');">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="product_id" value="<?php echo $prod['id']; ?>">
                                            <button type="submit" class="btn-icon" title="Retirer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider le panier ?');">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-outline">Vider le panier</button>
                    </form>
                </div>
                
                <div class="cart-summary">
                    <h2>Résumé de la commande</h2>
                    <div class="summary-row">
                        <span>Sous-total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Livraison</span>
                        <span>Gratuite</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                    <a href="checkout.php" class="btn btn-primary btn-large btn-block">
                        <i class="fas fa-credit-card"></i> Passer la commande
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

