<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$product = new Product();
$category = new Category();

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $product->delete($id);
    redirect(BASE_URL . 'admin/products.php?success=deleted');
}

$products = $product->getAll();
$pageTitle = "Gestion des produits";

// Messages de succès
$success = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'updated') {
        $success = 'Produit mis à jour avec succès.';
    } elseif ($_GET['success'] === 'created') {
        $success = 'Produit créé avec succès.';
    } elseif ($_GET['success'] === 'deleted') {
        $success = 'Produit supprimé avec succès.';
    }
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo escape($success); ?></div>
<?php endif; ?>

<div class="admin-page-header">
    <h2>Tous les produits</h2>
    <a href="product-edit.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un produit
    </a>
</div>

<?php if (!empty($products)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Vedette</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td>
                            <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                 alt="<?php echo escape($prod['name']); ?>"
                                 class="table-image"
                                 onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                        </td>
                        <td><?php echo escape($prod['name']); ?></td>
                        <td><?php echo escape($prod['category_name']); ?></td>
                        <td><?php echo formatPrice($prod['price']); ?></td>
                        <td>
                            <span class="badge <?php echo $prod['stock'] < 10 ? 'badge-warning' : 'badge-success'; ?>">
                                <?php echo $prod['stock']; ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($prod['featured']): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="product-edit.php?id=<?php echo $prod['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $prod['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Aucun produit pour le moment.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

