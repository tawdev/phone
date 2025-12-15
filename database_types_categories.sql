-- إنشاء جدول types_categories
CREATE TABLE IF NOT EXISTS types_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- إضافة عمود type_category_id إلى جدول products
ALTER TABLE products 
ADD COLUMN type_category_id INT DEFAULT NULL AFTER category_id,
ADD FOREIGN KEY (type_category_id) REFERENCES types_categories(id) ON DELETE SET NULL,
ADD INDEX idx_type_category (type_category_id);

-- إدراج بعض أنواع الفئات كمثال
INSERT INTO types_categories (name, category_id) VALUES
('iPhone 15 Series', 1),
('iPhone 14 Series', 1),
('iPhone 13 Series', 1),
('Galaxy S Series', 2),
('Galaxy Note Series', 2),
('Galaxy A Series', 2),
('Mi Series', 3),
('Redmi Series', 3),
('POCO Series', 3),
('Coques et Protection', 4),
('Chargeurs', 4),
('Écouteurs', 4);

