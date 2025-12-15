<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/Product.php';
require_once __DIR__ . '/../classes/Category.php';
require_once __DIR__ . '/../classes/TypeCategory.php';
require_once __DIR__ . '/includes/header.php';

$product = new Product();
$category = new Category();
$typeCategory = new TypeCategory();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$prod = $id ? $product->getById($id) : null;
$categories = $category->getAll();
$typeCategories = [];
if ($prod && $prod['category_id']) {
    $typeCategories = $typeCategory->getByCategoryId($prod['category_id']);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'slug' => generateSlug($_POST['slug'] ?? $_POST['name']),
        'description' => trim($_POST['description'] ?? ''),
        'price' => floatval($_POST['price'] ?? 0),
        'old_price' => !empty($_POST['old_price']) ? floatval($_POST['old_price']) : null,
        'category_id' => intval($_POST['category_id'] ?? 0),
        'type_category_id' => !empty($_POST['type_category_id']) ? intval($_POST['type_category_id']) : null,
        'brand' => trim($_POST['brand'] ?? ''),
        'stock' => intval($_POST['stock'] ?? 0),
        'featured' => isset($_POST['featured']) ? 1 : 0
    ];
    
    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = ROOT_PATH . 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            if ($prod && $prod['image']) {
                $oldImage = $uploadDir . $prod['image'];
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            $data['image'] = $filename;
        }
    } elseif ($prod) {
        $data['image'] = $prod['image'];
    }
    
    if (empty($data['name']) || $data['price'] <= 0 || $data['category_id'] <= 0) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } else {
        if ($id && $prod) {
            if ($product->update($id, $data)) {
                redirect(BASE_URL . 'admin/products.php?success=updated');
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        } else {
            if ($product->create($data)) {
                redirect(BASE_URL . 'admin/products.php?success=created');
            } else {
                $error = 'Erreur lors de la création.';
            }
        }
    }
}

$pageTitle = $id ? "Modifier le produit" : "Nouveau produit";
?>

<div class="admin-page-header">
    <h2><?php echo $id ? "Modifier le produit" : "Nouveau produit"; ?></h2>
    <a href="products.php" class="btn btn-outline">← Retour</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-error"><?php echo escape($error); ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo escape($success); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-row">
        <div class="form-group">
            <label for="name">Nom du produit *</label>
            <input type="text" id="name" name="name" required 
                   value="<?php echo $prod ? escape($prod['name']) : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="slug">Slug (URL)</label>
            <input type="text" id="slug" name="slug" 
                   value="<?php echo $prod ? escape($prod['slug']) : ''; ?>">
            <small>Généré automatiquement si vide</small>
        </div>
    </div>
    
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="5"><?php echo $prod ? escape($prod['description']) : ''; ?></textarea>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="category_id">Catégorie *</label>
            <select id="category_id" name="category_id" required>
                <option value="">Sélectionner une catégorie</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" 
                            <?php echo ($prod && $prod['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo escape($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="type_category_id">Type de catégorie</label>
            <select id="type_category_id" name="type_category_id">
                <option value="">Sélectionner un type</option>
                <?php foreach ($typeCategories as $typeCat): ?>
                    <option value="<?php echo $typeCat['id']; ?>" 
                            <?php echo ($prod && $prod['type_category_id'] == $typeCat['id']) ? 'selected' : ''; ?>>
                        <?php echo escape($typeCat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small>Les types seront chargés après la sélection de la catégorie</small>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="brand">Marque</label>
            <input type="text" id="brand" name="brand" 
                   value="<?php echo $prod ? escape($prod['brand']) : ''; ?>">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="price">Prix *</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required 
                   value="<?php echo $prod ? $prod['price'] : ''; ?>">
        </div>
        
        <div class="form-group">
            <label for="old_price">Ancien prix (promo)</label>
            <input type="number" id="old_price" name="old_price" step="0.01" min="0" 
                   value="<?php echo $prod ? $prod['old_price'] : ''; ?>">
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-group">
            <label for="stock">Stock *</label>
            <input type="number" id="stock" name="stock" min="0" required 
                   value="<?php echo $prod ? $prod['stock'] : 0; ?>">
        </div>
        
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*">
            <?php if ($prod && $prod['image']): ?>
                <div class="current-image">
                    <img src="<?php echo BASE_URL . 'uploads/' . $prod['image']; ?>" alt="Image actuelle">
                    <p>Image actuelle</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="form-group">
        <label class="checkbox-label">
            <input type="checkbox" name="featured" value="1" 
                   <?php echo ($prod && $prod['featured']) ? 'checked' : ''; ?>>
            <span>Produit en vedette</span>
        </label>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
        <a href="products.php" class="btn btn-outline">Annuler</a>
    </div>
</form>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const typeCategorySelect = document.getElementById('type_category_id');
    const currentTypeCategoryId = <?php echo ($prod && $prod['type_category_id']) ? $prod['type_category_id'] : 'null'; ?>;
    
    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        
        // Réinitialiser le select des types
        typeCategorySelect.innerHTML = '<option value="">Sélectionner un type</option>';
        
        if (categoryId) {
            // Charger les types de catégorie via AJAX
            fetch('<?php echo BASE_URL; ?>admin/api/get-type-categories.php?category_id=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(function(type) {
                            const option = document.createElement('option');
                            option.value = type.id;
                            option.textContent = type.name;
                            if (currentTypeCategoryId && currentTypeCategoryId == type.id) {
                                option.selected = true;
                            }
                            typeCategorySelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Aucun type disponible';
                        typeCategorySelect.appendChild(option);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Erreur de chargement';
                    typeCategorySelect.appendChild(option);
                });
        }
    });
    
    // Déclencher le changement si une catégorie est déjà sélectionnée
    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>
