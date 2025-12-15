<?php
/**
 * Script d'insertion de produits dans chaque catégorie
 * Ajoute 3 produits pour chaque catégorie : iPhone, Samsung, Xiaomi, Accessoires
 * 
 * Accès: http://localhost/Phone/admin/insert-products.php
 * Supprimez ce fichier après utilisation pour des raisons de sécurité
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::getInstance()->getConnection();
$results = [];
$errors = [];

// Produits à insérer
$products = [
    // iPhone (catégorie id = 1)
    [
        'name' => 'iPhone 15 Pro',
        'slug' => 'iphone-15-pro',
        'description' => 'iPhone 15 Pro avec écran Super Retina XDR de 6,1 pouces, puce A17 Pro, système de caméra professionnel avec zoom optique 3x, et design en titane premium.',
        'price' => 1199.00,
        'old_price' => 1299.00,
        'category_id' => 1,
        'brand' => 'Apple',
        'image' => 'iphone-15-pro.jpg',
        'stock' => 25,
        'featured' => 1
    ],
    [
        'name' => 'iPhone 14',
        'slug' => 'iphone-14',
        'description' => 'iPhone 14 avec écran Super Retina XDR de 6,1 pouces, puce A15 Bionic, double caméra 12MP, et autonomie toute la journée.',
        'price' => 899.00,
        'old_price' => 999.00,
        'category_id' => 1,
        'brand' => 'Apple',
        'image' => 'iphone-14.jpg',
        'stock' => 30,
        'featured' => 1
    ],
    [
        'name' => 'iPhone SE (3ème génération)',
        'slug' => 'iphone-se-3',
        'description' => 'iPhone SE compact avec écran Retina HD de 4,7 pouces, puce A15 Bionic, caméra 12MP, et Touch ID.',
        'price' => 499.00,
        'old_price' => null,
        'category_id' => 1,
        'brand' => 'Apple',
        'image' => 'iphone-se-3.jpg',
        'stock' => 40,
        'featured' => 0
    ],
    
    // Samsung (catégorie id = 2)
    [
        'name' => 'Samsung Galaxy S24',
        'slug' => 'samsung-galaxy-s24',
        'description' => 'Samsung Galaxy S24 avec écran Dynamic AMOLED 2X de 6,2 pouces, processeur Snapdragon 8 Gen 3, triple caméra 50MP, et charge rapide 25W.',
        'price' => 899.00,
        'old_price' => 999.00,
        'category_id' => 2,
        'brand' => 'Samsung',
        'image' => 'samsung-galaxy-s24.jpg',
        'stock' => 28,
        'featured' => 1
    ],
    [
        'name' => 'Samsung Galaxy A54',
        'slug' => 'samsung-galaxy-a54',
        'description' => 'Samsung Galaxy A54 avec écran Super AMOLED de 6,4 pouces, processeur Exynos 1380, triple caméra 50MP, et batterie 5000mAh.',
        'price' => 449.00,
        'old_price' => 499.00,
        'category_id' => 2,
        'brand' => 'Samsung',
        'image' => 'samsung-galaxy-a54.jpg',
        'stock' => 35,
        'featured' => 0
    ],
    [
        'name' => 'Samsung Galaxy Z Flip 5',
        'slug' => 'samsung-galaxy-z-flip-5',
        'description' => 'Samsung Galaxy Z Flip 5 pliable avec écran AMOLED de 6,7 pouces, processeur Snapdragon 8 Gen 2, double caméra 12MP, et design compact pliable.',
        'price' => 1099.00,
        'old_price' => null,
        'category_id' => 2,
        'brand' => 'Samsung',
        'image' => 'samsung-galaxy-z-flip-5.jpg',
        'stock' => 15,
        'featured' => 1
    ],
    
    // Xiaomi (catégorie id = 3)
    [
        'name' => 'Xiaomi 14',
        'slug' => 'xiaomi-14',
        'description' => 'Xiaomi 14 avec écran AMOLED de 6,36 pouces, processeur Snapdragon 8 Gen 3, triple caméra Leica 50MP, et charge rapide 90W.',
        'price' => 799.00,
        'old_price' => 899.00,
        'category_id' => 3,
        'brand' => 'Xiaomi',
        'image' => 'xiaomi-14.jpg',
        'stock' => 22,
        'featured' => 1
    ],
    [
        'name' => 'Xiaomi Redmi Note 13 Pro',
        'slug' => 'xiaomi-redmi-note-13-pro',
        'description' => 'Xiaomi Redmi Note 13 Pro avec écran AMOLED de 6,67 pouces, processeur Snapdragon 7s Gen 2, caméra 200MP, et batterie 5100mAh.',
        'price' => 349.00,
        'old_price' => 399.00,
        'category_id' => 3,
        'brand' => 'Xiaomi',
        'image' => 'xiaomi-redmi-note-13-pro.jpg',
        'stock' => 45,
        'featured' => 0
    ],
    [
        'name' => 'Xiaomi 13T',
        'slug' => 'xiaomi-13t',
        'description' => 'Xiaomi 13T avec écran AMOLED de 6,67 pouces, processeur MediaTek Dimensity 8200 Ultra, triple caméra Leica 50MP, et charge rapide 67W.',
        'price' => 599.00,
        'old_price' => null,
        'category_id' => 3,
        'brand' => 'Xiaomi',
        'image' => 'xiaomi-13t.jpg',
        'stock' => 32,
        'featured' => 1
    ],
    
    // Accessoires (catégorie id = 4)
    [
        'name' => 'Écouteurs AirPods Pro 2',
        'slug' => 'airpods-pro-2',
        'description' => 'Écouteurs sans fil AirPods Pro 2 avec réduction de bruit active, audio spatial, et autonomie jusqu\'à 30h avec l\'étui.',
        'price' => 279.00,
        'old_price' => 299.00,
        'category_id' => 4,
        'brand' => 'Apple',
        'image' => 'airpods-pro-2.jpg',
        'stock' => 60,
        'featured' => 1
    ],
    [
        'name' => 'Chargeur MagSafe',
        'slug' => 'chargeur-magsafe',
        'description' => 'Chargeur MagSafe compatible iPhone avec support magnétique, charge rapide 15W, et design compact.',
        'price' => 49.99,
        'old_price' => 59.99,
        'category_id' => 4,
        'brand' => 'Apple',
        'image' => 'chargeur-magsafe.jpg',
        'stock' => 80,
        'featured' => 0
    ],
    [
        'name' => 'Coque de protection transparente',
        'slug' => 'coque-protection-transparente',
        'description' => 'Coque de protection transparente en polycarbonate pour iPhone/Samsung, résistante aux chocs et aux rayures.',
        'price' => 19.99,
        'old_price' => null,
        'category_id' => 4,
        'brand' => 'Generic',
        'image' => 'coque-transparente.jpg',
        'stock' => 150,
        'featured' => 0
    ]
];

// Insérer les produits
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert'])) {
    try {
        $stmt = $db->prepare("INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($products as $product) {
            // Vérifier si le produit existe déjà (par slug)
            $checkStmt = $db->prepare("SELECT id FROM products WHERE slug = ?");
            $checkStmt->execute([$product['slug']]);
            
            if ($checkStmt->rowCount() > 0) {
                $results[] = [
                    'status' => 'skip',
                    'name' => $product['name'],
                    'message' => 'Produit déjà existant (slug: ' . $product['slug'] . ')'
                ];
            } else {
                $stmt->execute([
                    $product['name'],
                    $product['slug'],
                    $product['description'],
                    $product['price'],
                    $product['old_price'],
                    $product['category_id'],
                    $product['brand'],
                    $product['image'],
                    $product['stock'],
                    $product['featured']
                ]);
                $results[] = [
                    'status' => 'success',
                    'name' => $product['name'],
                    'message' => 'Produit inséré avec succès'
                ];
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Erreur : " . $e->getMessage();
    }
}

// Compter les produits par catégorie
$categoryCounts = [];
$stmt = $db->query("SELECT c.name, COUNT(p.id) as count FROM categories c LEFT JOIN products p ON c.id = p.category_id GROUP BY c.id, c.name");
$categoryCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insertion de produits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .info-box {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            border: 1px solid #c3e6cb;
        }
        .skip {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            border: 1px solid #ffeaa7;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .btn {
            background: #ffa307;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #ffa307;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .count {
            font-weight: bold;
            color: #ffa307;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Insertion de produits</h1>
        
        <div class="info-box">
            <strong>Information :</strong> Ce script va insérer 12 produits (3 par catégorie) dans la base de données.
            Les produits déjà existants (même slug) seront ignorés.
        </div>
        
        <h2>Produits par catégorie actuellement :</h2>
        <table>
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Nombre de produits</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categoryCounts as $cat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td class="count"><?php echo $cat['count']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (!empty($results)): ?>
            <h2>Résultats de l'insertion :</h2>
            <?php foreach ($results as $result): ?>
                <div class="<?php echo $result['status']; ?>">
                    <strong><?php echo htmlspecialchars($result['name']); ?></strong><br>
                    <?php echo htmlspecialchars($result['message']); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <form method="POST">
            <button type="submit" name="insert" class="btn">Insérer les produits</button>
        </form>
        
        <div class="info-box" style="margin-top: 30px;">
            <strong>Note de sécurité :</strong><br>
            Supprimez ce fichier (insert-products.php) après utilisation pour des raisons de sécurité.
        </div>
    </div>
</body>
</html>

