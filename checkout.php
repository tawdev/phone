<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Cart.php';
require_once __DIR__ . '/classes/Order.php';

Cart::init();
$cartItems = Cart::getItems();

if (empty($cartItems)) {
    redirect('cart.php');
}

$product = new Product();
$products = [];
$total = 0;

foreach ($cartItems as $product_id => $quantity) {
    $prod = $product->getById($product_id);
    if ($prod && $prod['stock'] >= $quantity) {
        $prod['cart_quantity'] = $quantity;
        $prod['subtotal'] = $prod['price'] * $quantity;
        $products[] = $prod;
        $total += $prod['subtotal'];
    }
}

if (empty($products)) {
    redirect('cart.php');
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $customer_address = trim($_POST['customer_address'] ?? '');
    
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($customer_address)) {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        try {
            $order = new Order();
            
            $orderData = [
                'customer_name' => $customer_name,
                'customer_email' => $customer_email,
                'customer_phone' => $customer_phone,
                'customer_address' => $customer_address,
                'total_amount' => $total
            ];
            
            $orderItems = [];
            foreach ($products as $prod) {
                $orderItems[] = [
                    'product_id' => $prod['id'],
                    'product_name' => $prod['name'],
                    'quantity' => $prod['cart_quantity'],
                    'price' => $prod['price']
                ];
            }
            
            $order_id = $order->create($orderData, $orderItems);
            Cart::clear();
            $success = true;
        } catch (Exception $e) {
            $error = 'Une erreur est survenue lors de la création de la commande.';
        }
    }
}

$pageTitle = "Commande";
$pageStyle = "checkout";
include 'includes/header.php';
?>

<?php if ($success): ?>
    <section class="checkout-success section">
        <div class="container">
            <div class="success-message">
                <div class="success-icon-wrapper">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1>Commande confirmée !</h1>
                <p>Votre commande a été enregistrée avec succès. Nous vous contacterons sous peu pour confirmer votre commande.</p>
                <div class="success-actions">
                    <a href="index.php" class="btn btn-primary btn-large">
                        <i class="fas fa-home"></i> Retour à l'accueil
                    </a>
                    <a href="produits.php" class="btn btn-outline btn-large">
                        <i class="fas fa-shopping-bag"></i> Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>
    <section class="checkout-page section">
        <div class="container">
            <h1 class="page-title">Finaliser la commande</h1>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo escape($error); ?></div>
            <?php endif; ?>
            
            <div class="checkout-layout">
                <div class="checkout-form-wrapper">
                    <h2>Informations de livraison</h2>
                    <form method="POST" class="checkout-form">
                        <div class="form-group">
                            <label for="customer_name">Nom complet *</label>
                            <input type="text" id="customer_name" name="customer_name" required 
                                   value="<?php echo isset($_POST['customer_name']) ? escape($_POST['customer_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email *</label>
                            <input type="email" id="customer_email" name="customer_email" required 
                                   value="<?php echo isset($_POST['customer_email']) ? escape($_POST['customer_email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Téléphone *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" required 
                                   value="<?php echo isset($_POST['customer_phone']) ? escape($_POST['customer_phone']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_address">Adresse complète *</label>
                            <textarea id="customer_address" name="customer_address" rows="4" required><?php echo isset($_POST['customer_address']) ? escape($_POST['customer_address']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-large btn-block">
                            <i class="fas fa-check"></i> Confirmer la commande
                        </button>
                    </form>
                </div>
                
                <div class="checkout-summary">
                    <h2>Résumé de la commande</h2>
                    <div class="order-items">
                        <?php foreach ($products as $prod): ?>
                            <div class="order-item">
                                <div class="order-item-info">
                                    <h3><?php echo escape($prod['name']); ?></h3>
                                    <p>Quantité : <?php echo $prod['cart_quantity']; ?></p>
                                </div>
                                <div class="order-item-price">
                                    <?php echo formatPrice($prod['subtotal']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        <span>Total</span>
                        <span><?php echo formatPrice($total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

