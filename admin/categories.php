<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$category = new Category();

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $category->delete($id);
    redirect(BASE_URL . 'admin/categories.php');
}

$categories = $category->getAll();
$pageTitle = "Gestion des catégories";
?>

<div class="admin-page-header">
    <h2>Toutes les catégories</h2>
    <a href="category-edit.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter une catégorie
    </a>
</div>

<?php if (!empty($categories)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo escape($cat['name']); ?></td>
                        <td><?php echo escape($cat['slug']); ?></td>
                        <td><?php echo escape($cat['description']); ?></td>
                        <td>
                            <a href="category-edit.php?id=<?php echo $cat['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $cat['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p>Aucune catégorie pour le moment.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

