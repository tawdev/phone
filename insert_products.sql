-- Script d'insertion de 3 produits pour chaque catégorie
-- Catégories: iPhone (id=1), Samsung (id=2), Xiaomi (id=3), Accessoires (id=4)

USE phone_store;

-- Produits iPhone (catégorie id = 1)
INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES
('iPhone 15 Pro', 'iphone-15-pro', 'iPhone 15 Pro avec écran Super Retina XDR de 6,1 pouces, puce A17 Pro, système de caméra professionnel avec zoom optique 3x, et design en titane premium.', 1199.00, 1299.00, 1, 'Apple', 'iphone-15-pro.jpg', 25, 1),
('iPhone 14', 'iphone-14', 'iPhone 14 avec écran Super Retina XDR de 6,1 pouces, puce A15 Bionic, double caméra 12MP, et autonomie toute la journée.', 899.00, 999.00, 1, 'Apple', 'iphone-14.jpg', 30, 1),
('iPhone SE (3ème génération)', 'iphone-se-3', 'iPhone SE compact avec écran Retina HD de 4,7 pouces, puce A15 Bionic, caméra 12MP, et Touch ID.', 499.00, NULL, 1, 'Apple', 'iphone-se-3.jpg', 40, 0);

-- Produits Samsung (catégorie id = 2)
INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES
('Samsung Galaxy S24', 'samsung-galaxy-s24', 'Samsung Galaxy S24 avec écran Dynamic AMOLED 2X de 6,2 pouces, processeur Snapdragon 8 Gen 3, triple caméra 50MP, et charge rapide 25W.', 899.00, 999.00, 2, 'Samsung', 'samsung-galaxy-s24.jpg', 28, 1),
('Samsung Galaxy A54', 'samsung-galaxy-a54', 'Samsung Galaxy A54 avec écran Super AMOLED de 6,4 pouces, processeur Exynos 1380, triple caméra 50MP, et batterie 5000mAh.', 449.00, 499.00, 2, 'Samsung', 'samsung-galaxy-a54.jpg', 35, 0),
('Samsung Galaxy Z Flip 5', 'samsung-galaxy-z-flip-5', 'Samsung Galaxy Z Flip 5 pliable avec écran AMOLED de 6,7 pouces, processeur Snapdragon 8 Gen 2, double caméra 12MP, et design compact pliable.', 1099.00, NULL, 2, 'Samsung', 'samsung-galaxy-z-flip-5.jpg', 15, 1);

-- Produits Xiaomi (catégorie id = 3)
INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES
('Xiaomi 14', 'xiaomi-14', 'Xiaomi 14 avec écran AMOLED de 6,36 pouces, processeur Snapdragon 8 Gen 3, triple caméra Leica 50MP, et charge rapide 90W.', 799.00, 899.00, 3, 'Xiaomi', 'xiaomi-14.jpg', 22, 1),
('Xiaomi Redmi Note 13 Pro', 'xiaomi-redmi-note-13-pro', 'Xiaomi Redmi Note 13 Pro avec écran AMOLED de 6,67 pouces, processeur Snapdragon 7s Gen 2, caméra 200MP, et batterie 5100mAh.', 349.00, 399.00, 3, 'Xiaomi', 'xiaomi-redmi-note-13-pro.jpg', 45, 0),
('Xiaomi 13T', 'xiaomi-13t', 'Xiaomi 13T avec écran AMOLED de 6,67 pouces, processeur MediaTek Dimensity 8200 Ultra, triple caméra Leica 50MP, et charge rapide 67W.', 599.00, NULL, 3, 'Xiaomi', 'xiaomi-13t.jpg', 32, 1);

-- Produits Accessoires (catégorie id = 4)
INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES
('Écouteurs AirPods Pro 2', 'airpods-pro-2', 'Écouteurs sans fil AirPods Pro 2 avec réduction de bruit active, audio spatial, et autonomie jusqu\'à 30h avec l\'étui.', 279.00, 299.00, 4, 'Apple', 'airpods-pro-2.jpg', 60, 1),
('Chargeur MagSafe', 'chargeur-magsafe', 'Chargeur MagSafe compatible iPhone avec support magnétique, charge rapide 15W, et design compact.', 49.99, 59.99, 4, 'Apple', 'chargeur-magsafe.jpg', 80, 0),
('Coque de protection transparente', 'coque-protection-transparente', 'Coque de protection transparente en polycarbonate pour iPhone/Samsung, résistante aux chocs et aux rayures.', 19.99, NULL, 4, 'Generic', 'coque-transparente.jpg', 150, 0);

