<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$order = new Order();
$product = new Product();
$category = new Category();

$stats = $order->getStats();
$recentOrders = array_slice($order->getAll(), 0, 5);
$lowStockProducts = $product->getAll();
$lowStockProducts = array_filter($lowStockProducts, function($p) {
    return $p['stock'] < 10;
});

$pageTitle = "Tableau de bord";
?>

<div class="dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #4CAF50;">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['total_orders']; ?></h3>
                <p>Total commandes</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #2196F3;">
                <i class="fas fa-euro-sign"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo formatPrice($stats['total_revenue']); ?></h3>
                <p>Chiffre d'affaires</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #FF9800;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['pending_orders']; ?></h3>
                <p>Commandes en attente</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #9C27B0;">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['month_orders']; ?></h3>
                <p>Commandes ce mois</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h2>Dernières commandes</h2>
            <?php if (!empty($recentOrders)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>N° Commande</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $ord): ?>
                            <tr>
                                <td><a href="order-detail.php?id=<?php echo $ord['id']; ?>"><?php echo escape($ord['order_number']); ?></a></td>
                                <td><?php echo escape($ord['customer_name']); ?></td>
                                <td><?php echo formatPrice($ord['total_amount']); ?></td>
                                <td><span class="badge badge-<?php echo $ord['status']; ?>"><?php echo ucfirst($ord['status']); ?></span></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($ord['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="orders.php" class="btn btn-outline">Voir toutes les commandes</a>
            <?php else: ?>
                <p>Aucune commande pour le moment.</p>
            <?php endif; ?>
        </div>
        
        <div class="dashboard-card">
            <h2>Stock faible</h2>
            <?php if (!empty($lowStockProducts)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($lowStockProducts, 0, 5) as $prod): ?>
                            <tr>
                                <td><?php echo escape($prod['name']); ?></td>
                                <td><span class="badge badge-warning"><?php echo $prod['stock']; ?></span></td>
                                <td><a href="product-edit.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm">Modifier</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="products.php" class="btn btn-outline">Gérer les produits</a>
            <?php else: ?>
                <p>Tous les produits ont un stock suffisant.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

