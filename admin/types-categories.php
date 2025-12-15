<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/TypeCategory.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$typeCategory = new TypeCategory();
$category = new Category();

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $typeCategory->delete($id);
    redirect(BASE_URL . 'admin/types-categories.php?success=deleted');
}

// Filtrer par catégorie
$categoryFilter = isset($_GET['category_id']) && !empty($_GET['category_id']) ? intval($_GET['category_id']) : null;
$typeCategories = $typeCategory->getAll($categoryFilter);
$categories = $category->getAll();

$pageTitle = "Gestion des types de catégories";

// Messages de succès
$success = '';
if (isset($_GET['success'])) {
    if ($_GET['success'] === 'updated') {
        $success = 'Type de catégorie mis à jour avec succès.';
    } elseif ($_GET['success'] === 'created') {
        $success = 'Type de catégorie créé avec succès.';
    } elseif ($_GET['success'] === 'deleted') {
        $success = 'Type de catégorie supprimé avec succès.';
    }
}
?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo escape($success); ?></div>
<?php endif; ?>

<div class="admin-page-header">
    <h2>Tous les types de catégories</h2>
    <a href="type-category-edit.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un type
    </a>
</div>

<!-- Filtre par catégorie -->
<div style="margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <label for="category_filter" style="font-weight: 600;">Filtrer par catégorie:</label>
        <select id="category_filter" name="category_id" onchange="this.form.submit()" style="padding: 0.5rem 1rem; border: 2px solid var(--border-color); border-radius: 0.5rem; min-width: 200px;">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" 
                        <?php echo ($categoryFilter && $categoryFilter == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo escape($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if ($categoryFilter): ?>
            <a href="types-categories.php" class="btn btn-outline btn-sm">
                <i class="fas fa-times"></i> Réinitialiser
            </a>
        <?php endif; ?>
    </form>
</div>

<?php if (!empty($typeCategories)): ?>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($typeCategories as $typeCat): ?>
                    <tr>
                        <td data-label="Nom"><?php echo escape($typeCat['name']); ?></td>
                        <td data-label="Catégorie"><?php echo escape($typeCat['category_name'] ?? 'N/A'); ?></td>
                        <td data-label="Date de création"><?php echo date('d/m/Y H:i', strtotime($typeCat['created_at'])); ?></td>
                        <td data-label="Actions">
                            <a href="type-category-edit.php?id=<?php echo $typeCat['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $typeCat['id']; ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de catégorie ?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Aucun type de catégorie pour le moment.
        <?php if ($categoryFilter): ?>
            <a href="types-categories.php" style="margin-left: 1rem;">Voir tous les types</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

