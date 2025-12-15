<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحديث قاعدة البيانات - Types Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #ffa307;
            margin-bottom: 20px;
        }
        .success {
            background: #d1fae5;
            color: #065f46;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .info {
            background: #dbeafe;
            color: #ffa307;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>تحديث قاعدة البيانات - Types Categories</h1>
        
        <?php
        try {
            $db = Database::getInstance()->getConnection();
            
            // التحقق من وجود الجدول
            $checkTable = $db->query("SHOW TABLES LIKE 'types_categories'");
            $tableExists = $checkTable->rowCount() > 0;
            
            if (!$tableExists) {
                echo '<div class="info">جارٍ إنشاء جدول types_categories...</div>';
                
                // إنشاء جدول types_categories
                $db->exec("CREATE TABLE IF NOT EXISTS types_categories (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    category_id INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                    INDEX idx_category (category_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                
                echo '<div class="success">✓ تم إنشاء جدول types_categories بنجاح</div>';
            } else {
                echo '<div class="info">جدول types_categories موجود بالفعل</div>';
            }
            
            // التحقق من وجود عمود type_category_id في جدول products
            $checkColumn = $db->query("SHOW COLUMNS FROM products LIKE 'type_category_id'");
            $columnExists = $checkColumn->rowCount() > 0;
            
            if (!$columnExists) {
                echo '<div class="info">جارٍ إضافة عمود type_category_id إلى جدول products...</div>';
                
                // إضافة عمود type_category_id
                $db->exec("ALTER TABLE products 
                    ADD COLUMN type_category_id INT DEFAULT NULL AFTER category_id,
                    ADD FOREIGN KEY (type_category_id) REFERENCES types_categories(id) ON DELETE SET NULL,
                    ADD INDEX idx_type_category (type_category_id)");
                
                echo '<div class="success">✓ تم إضافة عمود type_category_id بنجاح</div>';
            } else {
                echo '<div class="info">عمود type_category_id موجود بالفعل</div>';
            }
            
            // إدراج أنواع الفئات إذا لم تكن موجودة
            $checkData = $db->query("SELECT COUNT(*) as count FROM types_categories");
            $dataCount = $checkData->fetch()['count'];
            
            if ($dataCount == 0) {
                echo '<div class="info">جارٍ إدراج أنواع الفئات الافتراضية...</div>';
                
                $types = [
                    ['iPhone 15 Series', 1],
                    ['iPhone 14 Series', 1],
                    ['iPhone 13 Series', 1],
                    ['Galaxy S Series', 2],
                    ['Galaxy Note Series', 2],
                    ['Galaxy A Series', 2],
                    ['Mi Series', 3],
                    ['Redmi Series', 3],
                    ['POCO Series', 3],
                    ['Coques et Protection', 4],
                    ['Chargeurs', 4],
                    ['Écouteurs', 4]
                ];
                
                $stmt = $db->prepare("INSERT INTO types_categories (name, category_id) VALUES (?, ?)");
                foreach ($types as $type) {
                    $stmt->execute($type);
                }
                
                echo '<div class="success">✓ تم إدراج ' . count($types) . ' نوع فئة بنجاح</div>';
            } else {
                echo '<div class="info">أنواع الفئات موجودة بالفعل (' . $dataCount . ' نوع)</div>';
            }
            
            echo '<div class="success"><strong>✓ تم تحديث قاعدة البيانات بنجاح!</strong></div>';
            echo '<div class="info">يمكنك الآن استخدام صفحة إضافة/تعديل المنتجات مع أنواع الفئات.</div>';
            
        } catch (Exception $e) {
            echo '<div class="error"><strong>خطأ:</strong> ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
        
        <div style="margin-top: 30px;">
            <a href="products.php" style="display: inline-block; padding: 10px 20px; background: #ffa307; color: white; text-decoration: none; border-radius: 5px;">
                العودة إلى المنتجات
            </a>
        </div>
    </div>
</body>
</html>

