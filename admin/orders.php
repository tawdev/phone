<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Order.php';
require_once __DIR__ . '/includes/header.php';

$order = new Order();

// Mise à jour du statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $order->updateStatus($order_id, $status);
    redirect(BASE_URL . 'admin/orders.php');
}

$statusFilter = $_GET['status'] ?? null;
$orders = $order->getAll($statusFilter);
$pageTitle = "Gestion des commandes";
?>

<div class="admin-page-header">
    <h2>Toutes les commandes</h2>
    <div class="filter-tabs">
        <a href="orders.php" class="<?php echo !$statusFilter ? 'active' : ''; ?>">Toutes</a>
        <a href="orders.php?status=pending" class="<?php echo $statusFilter === 'pending' ? 'active' : ''; ?>">En attente</a>
        <a href="orders.php?status=processing" class="<?php echo $statusFilter === 'processing' ? 'active' : ''; ?>">En cours</a>
        <a href="orders.php?status=shipped" class="<?php echo $statusFilter === 'shipped' ? 'active' : ''; ?>">Expédiées</a>
    </div>
</div>

<?php if (!empty($orders)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>N° Commande</th>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $ord): ?>
                    <tr>
                        <td data-label="N° Commande"><a href="order-detail.php?id=<?php echo $ord['id']; ?>"><?php echo escape($ord['order_number']); ?></a></td>
                        <td data-label="Client"><?php echo escape($ord['customer_name']); ?></td>
                        <td data-label="Email"><?php echo escape($ord['customer_email']); ?></td>
                        <td data-label="Montant"><?php echo formatPrice($ord['total_amount']); ?></td>
                        <td data-label="Statut">
                            <form method="POST" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $ord['id']; ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php echo $ord['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                    <option value="processing" <?php echo $ord['status'] === 'processing' ? 'selected' : ''; ?>>En cours</option>
                                    <option value="shipped" <?php echo $ord['status'] === 'shipped' ? 'selected' : ''; ?>>Expédiée</option>
                                    <option value="delivered" <?php echo $ord['status'] === 'delivered' ? 'selected' : ''; ?>>Livrée</option>
                                    <option value="cancelled" <?php echo $ord['status'] === 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td data-label="Date"><?php echo date('d/m/Y H:i', strtotime($ord['created_at'])); ?></td>
                        <td data-label="Actions">
                            <a href="order-detail.php?id=<?php echo $ord['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Aucune commande pour le moment.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

