# نظام Types Categories

## نظرة عامة

تم إضافة نظام جديد لإدارة أنواع الفئات (Types Categories) المرتبطة بالفئات (Categories) بعلاقة One To Many.

## الملفات المضافة/المعدلة

### 1. قاعدة البيانات
- **`database_types_categories.sql`**: ملف SQL لإنشاء جدول `types_categories` وإضافة عمود `type_category_id` إلى جدول `products`
- **`admin/update-database-types.php`**: ملف PHP لتحديث قاعدة البيانات تلقائياً

### 2. Classes
- **`classes/TypeCategory.php`**: Class جديد لإدارة أنواع الفئات

### 3. تعديلات على Classes الموجودة
- **`classes/Product.php`**: تم إضافة دعم لـ `type_category_id` في دوال `create()`, `update()`, و `getById()`

### 4. صفحات Admin
- **`admin/product-edit.php`**: تم إضافة Select للفئات و Select لأنواع الفئات مع JavaScript لتحميل الأنواع تلقائياً
- **`admin/api/get-type-categories.php`**: API endpoint للحصول على أنواع الفئة المحددة

## كيفية الاستخدام

### 1. تحديث قاعدة البيانات

قم بزيارة:
```
http://localhost/Phone/admin/update-database-types.php
```

أو قم بتشغيل ملف SQL:
```sql
source database_types_categories.sql
```

### 2. إضافة/تعديل منتج

1. اذهب إلى صفحة إضافة/تعديل منتج
2. اختر الفئة من القائمة المنسدلة الأولى
3. بعد اختيار الفئة، سيتم تحميل أنواع الفئة تلقائياً في القائمة المنسدلة الثانية
4. اختر نوع الفئة (اختياري)
5. احفظ المنتج

## هيكل قاعدة البيانات

### جدول types_categories
```sql
- id (INT, PRIMARY KEY)
- name (VARCHAR(100))
- category_id (INT, FOREIGN KEY -> categories.id)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### جدول products (تم التعديل)
```sql
- type_category_id (INT, FOREIGN KEY -> types_categories.id, NULL)
```

## العلاقات

- **categories** (1) -> (Many) **types_categories**
- **products** (Many) -> (1) **types_categories** (اختياري)

## API Endpoint

### GET /admin/api/get-type-categories.php
**Parameters:**
- `category_id` (required): معرف الفئة

**Response:**
```json
[
  {
    "id": 1,
    "name": "iPhone 15 Series",
    "category_id": 1,
    "created_at": "2024-01-01 00:00:00",
    "updated_at": "2024-01-01 00:00:00"
  }
]
```

## ملاحظات

- نوع الفئة اختياري عند إضافة/تعديل منتج
- عند حذف فئة، يتم حذف جميع أنواعها تلقائياً (CASCADE)
- عند حذف نوع فئة، يتم تعيين `type_category_id` في المنتجات المرتبطة إلى NULL (SET NULL)

