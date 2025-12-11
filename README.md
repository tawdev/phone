# PhoneStore - Site E-commerce de Téléphones

Site e-commerce professionnel spécialisé dans la vente de téléphones et d'accessoires, développé en PHP, JavaScript, CSS et MySQL.

## 🚀 Fonctionnalités

### Front-End
- ✅ Page d'accueil avec produits en vedette et promotions
- ✅ Page produits avec filtrage par catégories
- ✅ Page détails produit avec images et descriptions
- ✅ Page À propos
- ✅ Page Contact avec formulaire fonctionnel
- ✅ Système de panier (ajout, modification, suppression)
- ✅ Page de commande (checkout)
- ✅ Design moderne, responsive et élégant
- ✅ Recherche de produits

### Back-End / Administration
- ✅ Système de connexion admin sécurisé
- ✅ Gestion complète des produits (CRUD)
- ✅ Gestion des catégories (CRUD)
- ✅ Gestion des commandes (visualisation, mise à jour du statut)
- ✅ Gestion des messages de contact
- ✅ Tableau de bord avec statistiques
- ✅ Upload d'images pour les produits

## 📋 Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé
- Extension PDO activée

## 🔧 Installation

### 1. Cloner ou télécharger le projet

Placez les fichiers dans le répertoire de votre serveur web (ex: `htdocs/Phone` pour XAMPP).

### 2. Configuration de la base de données

1. Créez une base de données MySQL nommée `phone_store`
2. Importez le fichier `database.sql` dans votre base de données :
   ```sql
   mysql -u root -p phone_store < database.sql
   ```
   Ou utilisez phpMyAdmin pour importer le fichier.

### 3. Configuration

Modifiez le fichier `config/database.php` si nécessaire pour adapter les paramètres de connexion :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'phone_store');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configuration de l'URL de base

Modifiez le fichier `config/config.php` pour adapter l'URL de base si nécessaire :
```php
define('BASE_URL', 'http://localhost/Phone/');
```

### 5. Créer le dossier uploads

Créez le dossier `uploads/` à la racine du projet avec les permissions d'écriture :
```bash
mkdir uploads
chmod 755 uploads
```

### 6. Compte administrateur par défaut

- **Username:** admin
- **Password:** admin123

⚠️ **IMPORTANT:** Changez le mot de passe en production !

## 📁 Structure du projet

```
Phone/
├── admin/              # Panneau d'administration
│   ├── includes/       # Header et footer admin
│   ├── index.php       # Tableau de bord
│   ├── products.php    # Gestion produits
│   ├── categories.php  # Gestion catégories
│   ├── orders.php      # Gestion commandes
│   └── messages.php    # Messages de contact
├── api/                # API endpoints
│   └── cart.php        # API panier
├── assets/
│   ├── css/           # Styles CSS
│   ├── js/            # Scripts JavaScript
│   └── images/        # Images statiques
├── classes/           # Classes PHP (Product, Category, Order, etc.)
├── config/            # Configuration (database, config)
├── includes/          # Header et footer front-end
├── uploads/           # Images uploadées (à créer)
├── index.php          # Page d'accueil
├── produits.php       # Liste des produits
├── produit.php        # Détails produit
├── cart.php           # Panier
├── checkout.php        # Commande
├── contact.php        # Contact
├── apropos.php        # À propos
└── database.sql       # Script SQL
```

## 🎨 Utilisation

### Accès au site
- Front-end: `http://localhost/Phone/`
- Administration: `http://localhost/Phone/admin/login.php`

### Gestion des produits
1. Connectez-vous à l'administration
2. Allez dans "Produits"
3. Cliquez sur "Ajouter un produit"
4. Remplissez les informations et uploadez une image
5. Enregistrez

### Gestion des commandes
1. Dans l'administration, allez dans "Commandes"
2. Visualisez les commandes et leur statut
3. Modifiez le statut selon l'avancement (en attente, en cours, expédiée, etc.)

## 🔒 Sécurité

Le site inclut :
- Protection contre SQL Injection (utilisation de PDO avec requêtes préparées)
- Protection contre XSS (fonction `escape()` pour échapper les données)
- Authentification admin sécurisée (hashage des mots de passe)
- Validation des données côté serveur

## 🛠️ Technologies utilisées

- **Backend:** PHP 7.4+
- **Base de données:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Icons:** Font Awesome 6.4.0

## 📝 Notes

- Les images de produits doivent être uploadées via le panneau d'administration
- Le système de panier utilise les sessions PHP
- Les commandes sont stockées en base de données avec un numéro unique
- Le stock est automatiquement déduit lors de la validation d'une commande

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifiez les paramètres dans `config/database.php`
- Assurez-vous que MySQL est démarré
- Vérifiez que la base de données existe

### Images non affichées
- Vérifiez que le dossier `uploads/` existe et a les bonnes permissions
- Vérifiez le chemin `BASE_URL` dans `config/config.php`

### Erreur 404
- Assurez-vous que mod_rewrite est activé dans Apache
- Vérifiez que le fichier `.htaccess` est présent

## 📄 Licence

Ce projet est fourni tel quel pour usage éducatif et commercial.

---

Développé avec ❤️ pour PhoneStore

