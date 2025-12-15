<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/TypeCategory.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$typeCategory = new TypeCategory();
$category = new Category();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$typeCat = $id ? $typeCategory->getById($id) : null;
$categories = $category->getAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'category_id' => intval($_POST['category_id'] ?? 0)
    ];
    
    if (empty($data['name'])) {
        $error = 'Le nom est obligatoire.';
    } elseif ($data['category_id'] <= 0) {
        $error = 'Veuillez sélectionner une catégorie.';
    } else {
        if ($id && $typeCat) {
            if ($typeCategory->update($id, $data)) {
                redirect(BASE_URL . 'admin/types-categories.php?success=updated');
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        } else {
            if ($typeCategory->create($data)) {
                redirect(BASE_URL . 'admin/types-categories.php?success=created');
            } else {
                $error = 'Erreur lors de la création.';
            }
        }
    }
}

$pageTitle = $id ? "Modifier le type de catégorie" : "Nouveau type de catégorie";
?>

<div class="admin-page-header">
    <h2><?php echo $id ? "Modifier le type de catégorie" : "Nouveau type de catégorie"; ?></h2>
    <a href="types-categories.php" class="btn btn-outline">← Retour</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo escape($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo escape($success); ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label for="category_id">Catégorie *</label>
        <select id="category_id" name="category_id" required>
            <option value="">Sélectionner une catégorie</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>" 
                        <?php echo ($typeCat && $typeCat['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo escape($cat['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="name">Nom du type *</label>
        <input type="text" id="name" name="name" required 
               value="<?php echo $typeCat ? escape($typeCat['name']) : ''; ?>"
               placeholder="Ex: iPhone 15 Series, Galaxy S Series...">
        <small>Le nom du type de catégorie (ex: iPhone 15 Series, Galaxy S Series, etc.)</small>
    </div>
    
    <?php if ($typeCat): ?>
        <div class="form-group">
            <label>Informations</label>
            <div style="padding: 1rem; background: var(--light-color); border-radius: 0.5rem;">
                <p><strong>Date de création:</strong> <?php echo date('d/m/Y à H:i', strtotime($typeCat['created_at'])); ?></p>
                <?php if ($typeCat['updated_at'] != $typeCat['created_at']): ?>
                    <p><strong>Dernière modification:</strong> <?php echo date('d/m/Y à H:i', strtotime($typeCat['updated_at'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="types-categories.php" class="btn btn-outline">Annuler</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>

