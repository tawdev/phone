-- Base de données pour le site e-commerce de téléphones
CREATE DATABASE IF NOT EXISTS phone_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE phone_store;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    old_price DECIMAL(10, 2) DEFAULT NULL,
    category_id INT NOT NULL,
    brand VARCHAR(100),
    image VARCHAR(255),
    stock INT DEFAULT 0,
    featured BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_featured (featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des commandes
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(20) NOT NULL,
    customer_address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des détails de commande
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    read_status BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion des catégories par défaut
INSERT INTO categories (name, slug, description) VALUES
('iPhone', 'iphone', 'Tous les modèles iPhone d\'Apple'),
('Samsung', 'samsung', 'Smartphones Samsung Galaxy'),
('Xiaomi', 'xiaomi', 'Téléphones Xiaomi'),
('Accessoires', 'accessoires', 'Accessoires pour téléphones : coques, chargeurs, écouteurs...');

-- Insertion d'un administrateur par défaut (username: admin, password: admin123)
-- Le mot de passe est hashé avec password_hash() - à changer en production
-- Si le mot de passe ne fonctionne pas, utilisez admin/setup-admin.php pour le réinitialiser
INSERT INTO admins (username, email, password) VALUES
('admin', 'admin@phonestore.com', '$2y$10$Gffd0rgsc1eP2Jimsmg7n.d1/LoDaeDB3d6a/tkxbEYVoXaVtQzDm');

-- Insertion de quelques produits d'exemple
INSERT INTO products (name, slug, description, price, old_price, category_id, brand, image, stock, featured) VALUES
('iPhone 15 Pro Max', 'iphone-15-pro-max', 'Le dernier iPhone avec écran Super Retina XDR de 6,7 pouces, puce A17 Pro, et système de caméra professionnel.', 1299.00, 1399.00, 1, 'Apple', 'iphone-15-pro-max.jpg', 15, 1),
('Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Smartphone premium avec écran AMOLED 6.8 pouces, processeur Snapdragon 8 Gen 3, et caméra 200MP.', 1199.00, NULL, 2, 'Samsung', 'samsung-s24-ultra.jpg', 20, 1),
('Xiaomi 14 Pro', 'xiaomi-14-pro', 'Téléphone haut de gamme avec écran AMOLED 6.73 pouces, processeur Snapdragon 8 Gen 3, et charge rapide 120W.', 899.00, 999.00, 3, 'Xiaomi', 'xiaomi-14-pro.jpg', 12, 1),
('Coque iPhone 15 Pro Max', 'coque-iphone-15-pro-max', 'Coque de protection en silicone premium pour iPhone 15 Pro Max.', 29.99, NULL, 4, 'Generic', 'coque-iphone.jpg', 50, 0),
('Chargeur Sans Fil', 'chargeur-sans-fil', 'Chargeur sans fil compatible Qi pour tous les smartphones.', 39.99, 49.99, 4, 'Generic', 'chargeur-sans-fil.jpg', 30, 0),
('Écouteurs Bluetooth', 'ecouteurs-bluetooth', 'Écouteurs Bluetooth avec réduction de bruit active et autonomie de 30h.', 79.99, NULL, 4, 'Generic', 'ecouteurs-bluetooth.jpg', 25, 0);

