<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/includes/header.php';

$category = new Category();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cat = $id ? $category->getById($id) : null;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'slug' => generateSlug($_POST['slug'] ?? $_POST['name']),
        'description' => trim($_POST['description'] ?? '')
    ];
    
    if (empty($data['name'])) {
        $error = 'Le nom est obligatoire.';
    } else {
        if ($id && $cat) {
            if ($category->update($id, $data)) {
                $success = 'Catégorie mise à jour avec succès.';
                $cat = $category->getById($id);
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        } else {
            if ($category->create($data)) {
                $success = 'Catégorie créée avec succès.';
                redirect(BASE_URL . 'admin/categories.php');
            } else {
                $error = 'Erreur lors de la création.';
            }
        }
    }
}

$pageTitle = $id ? "Modifier la catégorie" : "Nouvelle catégorie";
?>

<div class="admin-page-header">
    <h2><?php echo $id ? "Modifier la catégorie" : "Nouvelle catégorie"; ?></h2>
    <a href="categories.php" class="btn btn-outline">← Retour</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo escape($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo escape($success); ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-group">
        <label for="name">Nom de la catégorie *</label>
        <input type="text" id="name" name="name" required 
               value="<?php echo $cat ? escape($cat['name']) : ''; ?>">
    </div>
    
    <div class="form-group">
        <label for="slug">Slug (URL)</label>
        <input type="text" id="slug" name="slug" 
               value="<?php echo $cat ? escape($cat['slug']) : ''; ?>">
        <small>Généré automatiquement si vide</small>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4"><?php echo $cat ? escape($cat['description']) : ''; ?></textarea>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="categories.php" class="btn btn-outline">Annuler</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>

