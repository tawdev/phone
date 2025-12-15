<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';
require_once __DIR__ . '/classes/TypeCategory.php';
require_once __DIR__ . '/classes/Cart.php';

$product = new Product();
$category = new Category();
$typeCategory = new TypeCategory();

// Récupérer toutes les catégories avec le nombre de produits et types
$categories = $category->getAll();
$categoriesWithProducts = [];

foreach ($categories as $cat) {
    $products = $product->getAll($cat['id']);
    $typeCategories = $typeCategory->getByCategoryId($cat['id']);
    
    $categoriesWithProducts[] = [
        'category' => $cat,
        'products' => array_slice($products, 0, 5), // Limiter à 5 produits par catégorie
        'total_products' => count($products),
        'type_categories' => $typeCategories
    ];
}

$pageTitle = "Catégories";
$pageStyle = "categories";
include 'includes/header.php';
?>

<section class="categories-page section">
    <div class="container">
        <h1 class="page-title">Nos Catégories</h1>
        <p class="page-subtitle">Découvrez notre sélection organisée par catégories</p>
        
        <div class="categories-grid stagger-animate">
            <?php foreach ($categoriesWithProducts as $item): 
                $cat = $item['category'];
                $products = $item['products'];
                $totalProducts = $item['total_products'];
                $typeCategories = $item['type_categories'];
            ?>
                <div class="category-card hover-lift">
                    <div class="category-header">
                        <div class="category-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div class="category-info">
                            <h2><?php echo escape($cat['name']); ?></h2>
                            <?php if ($cat['description']): ?>
                                <p class="category-description"><?php echo escape($cat['description']); ?></p>
                            <?php endif; ?>
                            <div class="category-meta">
                                <span class="product-count">
                                    <i class="fas fa-box"></i>
                                    <?php echo $totalProducts; ?> produit<?php echo $totalProducts > 1 ? 's' : ''; ?>
                                </span>
                                <?php if (!empty($typeCategories)): ?>
                                    <span class="type-count">
                                        <i class="fas fa-layer-group"></i>
                                        <?php echo count($typeCategories); ?> type<?php echo count($typeCategories) > 1 ? 's' : ''; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($typeCategories)): ?>
                            <button class="category-toggle-btn" data-category-id="<?php echo $cat['id']; ?>" aria-label="Toggle types">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($typeCategories)): ?>
                        <div class="category-types" id="types-<?php echo $cat['id']; ?>" style="display: none;">
                            <div class="types-header-section">
                                <h3 class="types-title">
                                    <i class="fas fa-layer-group"></i>
                                    Types de catégories
                                </h3>
                                <p class="types-subtitle">Sélectionnez un type pour voir les produits</p>
                            </div>
                            <div class="types-list">
                                <?php foreach ($typeCategories as $typeCat): 
                                    // Récupérer tous les produits de la catégorie
                                    $allProducts = $product->getAll($cat['id']);
                                    // Filtrer les produits par type_category_id
                                    $typeProducts = array_filter($allProducts, function($p) use ($typeCat) {
                                        return !empty($p['type_category_id']) && intval($p['type_category_id']) == intval($typeCat['id']);
                                    });
                                    $typeProducts = array_values($typeProducts); // Réindexer le tableau
                                ?>
                                    <div class="type-item" data-type-id="<?php echo $typeCat['id']; ?>">
                                        <div class="type-header" onclick="toggleTypeProducts(<?php echo $typeCat['id']; ?>)">
                                            <div class="type-header-left">
                                                <div class="type-icon">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <div class="type-info">
                                                    <h3 class="type-name"><?php echo escape($typeCat['name']); ?></h3>
                                                    <span class="type-product-count">
                                                        <i class="fas fa-box"></i>
                                                        <?php echo count($typeProducts); ?> produit<?php echo count($typeProducts) > 1 ? 's' : ''; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <button class="type-toggle-btn" data-type-id="<?php echo $typeCat['id']; ?>" aria-label="Toggle products" onclick="event.stopPropagation(); toggleTypeProducts(<?php echo $typeCat['id']; ?>);">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div>
                                        <div class="type-products" id="type-products-<?php echo $typeCat['id']; ?>" style="display: none;">
                                            <?php if (!empty($typeProducts)): ?>
                                                <div class="type-products-grid">
                                                    <?php foreach (array_slice($typeProducts, 0, 5) as $prod): ?>
                                                        <div class="type-product-item">
                                                            <a href="produit.php?slug=<?php echo $prod['slug']; ?>">
                                                                <div class="type-product-image">
                                                                    <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                                                         alt="<?php echo escape($prod['name']); ?>"
                                                                         onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                                                    <?php if ($prod['old_price']): ?>
                                                                        <span class="badge badge-sale">Promo</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="type-product-info">
                                                                    <h4><?php echo escape($prod['name']); ?></h4>
                                                                    <div class="type-product-price">
                                                                        <?php if ($prod['old_price']): ?>
                                                                            <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                                                                        <?php endif; ?>
                                                                        <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php if (count($typeProducts) > 5): ?>
                                                    <div class="type-view-more">
                                                        <a href="produits.php?category=<?php echo $cat['slug']; ?>&type=<?php echo $typeCat['id']; ?>" class="btn btn-outline btn-sm">
                                                            <i class="fas fa-arrow-right"></i> Voir tous (<?php echo count($typeProducts); ?>)
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <div class="type-empty">
                                                    <i class="fas fa-inbox"></i>
                                                    <p>Aucun produit dans ce type pour le moment.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($products)): ?>
                        <div class="category-products">
                            <div class="category-products-grid">
                                <?php foreach ($products as $prod): ?>
                                    <div class="category-product-item">
                                        <a href="produit.php?slug=<?php echo $prod['slug']; ?>">
                                            <div class="category-product-image">
                                                <img src="<?php echo BASE_URL . 'uploads/' . ($prod['image'] ?: 'placeholder.jpg'); ?>" 
                                                     alt="<?php echo escape($prod['name']); ?>"
                                                     onerror="this.src='<?php echo BASE_URL; ?>assets/images/placeholder.jpg'">
                                                <?php if ($prod['old_price']): ?>
                                                    <span class="badge badge-sale">Promo</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="category-product-info">
                                                <h4><?php echo escape($prod['name']); ?></h4>
                                                <div class="category-product-price">
                                                    <?php if ($prod['old_price']): ?>
                                                        <span class="old-price"><?php echo formatPrice($prod['old_price']); ?></span>
                                                    <?php endif; ?>
                                                    <span class="current-price"><?php echo formatPrice($prod['price']); ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <?php if ($totalProducts > 5): ?>
                                <div class="category-view-more">
                                    <a href="produits.php?category=<?php echo $cat['slug']; ?>" class="btn btn-outline">
                                        Voir tous les produits (<?php echo $totalProducts; ?>)
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="category-view-more">
                                    <a href="produits.php?category=<?php echo $cat['slug']; ?>" class="btn btn-outline">
                                        Voir tous les produits
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="category-empty">
                            <i class="fas fa-inbox"></i>
                            <p>Aucun produit dans cette catégorie pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($categories)): ?>
            <div class="no-categories">
                <i class="fas fa-folder-open"></i>
                <h2>Aucune catégorie disponible</h2>
                <p>Les catégories seront bientôt disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
// Fonction globale pour toggle les produits d'un type
function toggleTypeProducts(typeId) {
    const productsContainer = document.getElementById('type-products-' + typeId);
    const toggleBtn = document.querySelector('.type-toggle-btn[data-type-id="' + typeId + '"]');
    
    if (productsContainer && toggleBtn) {
        const isVisible = productsContainer.style.display !== 'none';
        productsContainer.style.display = isVisible ? 'none' : 'block';
        toggleBtn.classList.toggle('active');
        
        // Animation smooth
        if (!isVisible) {
            productsContainer.style.opacity = '0';
            setTimeout(() => {
                productsContainer.style.transition = 'opacity 0.3s ease';
                productsContainer.style.opacity = '1';
            }, 10);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Toggle pour les types de catégories
    const categoryToggleButtons = document.querySelectorAll('.category-toggle-btn');
    
    categoryToggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const categoryId = this.getAttribute('data-category-id');
            const typesContainer = document.getElementById('types-' + categoryId);
            
            if (typesContainer) {
                const isVisible = typesContainer.style.display !== 'none';
                typesContainer.style.display = isVisible ? 'none' : 'block';
                this.classList.toggle('active');
                
                // Animation smooth
                if (!isVisible) {
                    typesContainer.style.opacity = '0';
                    setTimeout(() => {
                        typesContainer.style.transition = 'opacity 0.3s ease';
                        typesContainer.style.opacity = '1';
                    }, 10);
                }
            }
        });
    });
});
</script>

