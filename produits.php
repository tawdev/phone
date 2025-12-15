<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/TypeCategory.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$category = new Category();
$typeCategory = new TypeCategory();

// Récupérer la catégorie sélectionnée
$selectedCategory = null;
$categorySlug = $_GET['category'] ?? null;
if ($categorySlug) {
    $selectedCategory = $category->getBySlug($categorySlug);
}

// Récupérer le type de catégorie sélectionné
$selectedTypeCategory = null;
$typeCategoryId = isset($_GET['type']) ? intval($_GET['type']) : null;
if ($typeCategoryId) {
    $selectedTypeCategory = $typeCategory->getById($typeCategoryId);
}

// Récupérer les produits
$products = [];
if ($selectedTypeCategory && $selectedTypeCategory['category_id']) {
    // Filtrer par type de catégorie
    $products = $product->getAll($selectedTypeCategory['category_id'], null, $typeCategoryId);
} elseif ($selectedCategory) {
    // Filtrer par catégorie
    $products = $product->getAll($selectedCategory['id']);
} else {
    // Tous les produits
    $products = $product->getAll();
}

// Recherche
$searchQuery = $_GET['search'] ?? '';
if ($searchQuery) {
    $products = $product->search($searchQuery);
}

// Récupérer toutes les catégories avec leurs types
$categories = $category->getAll();
$categoriesWithTypes = [];

foreach ($categories as $cat) {
    $types = $typeCategory->getByCategoryId($cat['id']);
    $categoriesWithTypes[] = [
        'category' => $cat,
        'types' => $types
    ];
}

$pageTitle = "Produits";
$pageStyle = "products";
include 'includes/header.php';
?>

<section class="products-page section">
    <div class="container">
        <h1 class="page-title">Nos Produits</h1>
        
        <div class="products-layout">
            <aside class="products-sidebar">
                <div class="sidebar-section">
                    <h3><i class="fas fa-tags"></i> Catégories</h3>
                    <ul class="category-list">
                        <li>
                            <a href="produits.php" class="<?php echo !$selectedCategory ? 'active' : ''; ?>">
                                <i class="fas fa-chevron-right"></i> Tous les produits
                            </a>
                        </li>
                        <?php foreach ($categoriesWithTypes as $item): 
                            $cat = $item['category'];
                            $types = $item['types'];
                            $isCategorySelected = ($selectedCategory && $selectedCategory['id'] == $cat['id']);
                            $hasTypes = !empty($types);
                        ?>
                            <li class="category-item-wrapper">
                                <div class="category-item-header">
                                    <a href="produits.php?category=<?php echo $cat['slug']; ?>" 
                                       class="category-link <?php echo $isCategorySelected ? 'active' : ''; ?>">
                                        <i class="fas fa-chevron-right"></i> 
                                        <span><?php echo escape($cat['name']); ?></span>
                                        <?php if ($hasTypes): ?>
                                            <span class="category-colon">:</span>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ($hasTypes): ?>
                                        <button class="category-types-toggle" 
                                                data-category-id="<?php echo $cat['id']; ?>"
                                                aria-label="Toggle types">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <?php if ($hasTypes): ?>
                                    <ul class="type-list category-types-list" 
                                        id="types-<?php echo $cat['id']; ?>"
                                        style="display: <?php echo $isCategorySelected ? 'block' : 'none'; ?>;">
                                        <li>
                                            <a href="produits.php?category=<?php echo $cat['slug']; ?>" 
                                               class="type-link <?php echo ($isCategorySelected && !$selectedTypeCategory) ? 'active' : ''; ?>">
                                                <i class="fas fa-chevron-right"></i> Tous les types
                                            </a>
                                        </li>
                                        <?php foreach ($types as $typeCat): ?>
                                            <li>
                                                <a href="produits.php?category=<?php echo $cat['slug']; ?>&type=<?php echo $typeCat['id']; ?>" 
                                                   class="type-link <?php echo ($selectedTypeCategory && $selectedTypeCategory['id'] == $typeCat['id']) ? 'active' : ''; ?>">
                                                    <i class="fas fa-chevron-right"></i> <?php echo escape($typeCat['name']); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </aside>
            
            <div class="products-main">
                <div class="products-toolbar">
                    <?php if ($selectedCategory): ?>
                        <div class="breadcrumb">
                            <a href="produits.php">Produits</a>
                            <i class="fas fa-chevron-right"></i>
                            <span><?php echo escape($selectedCategory['name']); ?></span>
                            <?php if ($selectedTypeCategory): ?>
                                <i class="fas fa-chevron-right"></i>
                                <span><?php echo escape($selectedTypeCategory['name']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="GET" class="search-form">
                        <?php if ($categorySlug): ?>
                            <input type="hidden" name="category" value="<?php echo escape($categorySlug); ?>">
                        <?php endif; ?>
                        <?php if ($typeCategoryId): ?>
                            <input type="hidden" name="type" value="<?php echo $typeCategoryId; ?>">
                        <?php endif; ?>
                        <input type="text" name="search" placeholder="Rechercher un produit..." 
                               value="<?php echo escape($searchQuery); ?>">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                
                <div class="products-grid stagger-animate">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $prod): ?>
                            <div class="product-card hover-lift">
                                <?php if ($prod['old_price']): ?>
                                    <span class="badge badge-sale">Promo</span>
                                <?php endif; ?>
                                <div class="product-image">
                                    <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                         alt="<?php echo escape($prod['name']); ?>"
                                         class="product-image-zoom"
                                         onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                </div>
                                <div class="product-info">
                                    <h3><?php echo escape($prod['name']); ?></h3>
                                    <p class="product-category"><?php echo escape($prod['category_name']); ?></p>
                                    <div class="product-price">
                                        <?php if ($prod['old_price']): ?>
                                            <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                                        <?php endif; ?>
                                        <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                                    </div>
                                    <div class="product-actions">
                                        <a href="produit.php?slug=<?php echo $prod['slug']; ?>" class="btn btn-outline">Voir détails</a>
                                        <button class="btn btn-primary add-to-cart" data-product-id="<?php echo $prod['id']; ?>">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-results">Aucun produit trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle pour les types de catégories
    const categoryTypesToggles = document.querySelectorAll('.category-types-toggle');
    
    categoryTypesToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const categoryId = this.getAttribute('data-category-id');
            const typesList = document.getElementById('types-' + categoryId);
            
            if (typesList) {
                const isVisible = typesList.style.display !== 'none';
                typesList.style.display = isVisible ? 'none' : 'block';
                this.classList.toggle('active');
            }
        });
    });
    
    // Ouvrir automatiquement les types de la catégorie sélectionnée
    <?php if ($selectedCategory): ?>
        const selectedCategoryId = <?php echo $selectedCategory['id']; ?>;
        const selectedTypesList = document.getElementById('types-' + selectedCategoryId);
        const selectedToggle = document.querySelector('.category-types-toggle[data-category-id="' + selectedCategoryId + '"]');
        
        if (selectedTypesList) {
            selectedTypesList.style.display = 'block';
        }
        if (selectedToggle) {
            selectedToggle.classList.add('active');
        }
    <?php endif; ?>
});
</script>

