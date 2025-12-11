<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/includes/header.php';

$order = new Order();

$id = intval($_GET['id'] ?? 0);
$ord = $order->getById($id);

if (!$ord) {
    redirect(BASE_URL . 'admin/orders.php');
}

$items = $order->getItems($id);
$pageTitle = "Détails de la commande";
?>

<div class="admin-page-header">
    <h2>Commande <?php echo escape($ord['order_number']); ?></h2>
    <a href="orders.php" class="btn btn-outline">← Retour</a>
</div>

<div class="order-detail">
    <div class="order-info-grid">
        <div class="order-info-card">
            <h3>Informations client</h3>
            <p><strong>Nom :</strong> <?php echo escape($ord['customer_name']); ?></p>
            <p><strong>Email :</strong> <?php echo escape($ord['customer_email']); ?></p>
            <p><strong>Téléphone :</strong> <?php echo escape($ord['customer_phone']); ?></p>
            <p><strong>Adresse :</strong><br><?php echo nl2br(escape($ord['customer_address'])); ?></p>
        </div>
        
        <div class="order-info-card">
            <h3>Informations commande</h3>
            <p><strong>N° Commande :</strong> <?php echo escape($ord['order_number']); ?></p>
            <p><strong>Date :</strong> <?php echo date('d/m/Y à H:i', strtotime($ord['created_at'])); ?></p>
            <p><strong>Statut :</strong> 
                <span class="badge badge-<?php echo $ord['status']; ?>">
                    <?php echo ucfirst($ord['status']); ?>
                </span>
            </p>
            <p><strong>Total :</strong> <?php echo formatPrice($ord['total_amount']); ?></p>
        </div>
    </div>
    
    <div class="order-items-card">
        <h3>Articles commandés</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo escape($item['product_name']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td><?php echo formatPrice($item['price']); ?></td>
                        <td><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo formatPrice($ord['total_amount']); ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

