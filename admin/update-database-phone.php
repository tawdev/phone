<?php
/**
 * Script de mise à jour de la base de données
 * Ajoute la colonne 'phone' à la table contact_messages si elle n'existe pas
 * 
 * Accès: http://localhost/Phone/admin/update-database-phone.php
 * Supprimez ce fichier après utilisation pour des raisons de sécurité
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Vérifier si l'utilisateur est admin (optionnel, pour la sécurité)
// Décommentez la ligne suivante si vous voulez protéger ce script
// require_once __DIR__ . '/includes/auth.php';

$db = Database::getInstance()->getConnection();
$success = false;
$error = '';

try {
    // Vérifier si la colonne existe déjà
    $stmt = $db->query("SHOW COLUMNS FROM contact_messages LIKE 'phone'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        // Ajouter la colonne phone
        $db->exec("ALTER TABLE contact_messages ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER email");
        $success = true;
        $message = "La colonne 'phone' a été ajoutée avec succès à la table contact_messages.";
    } else {
        $success = true;
        $message = "La colonne 'phone' existe déjà dans la table contact_messages.";
    }
} catch (PDOException $e) {
    $error = "Erreur : " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mise à jour de la base de données</title>
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
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border: 1px solid #bee5eb;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mise à jour de la base de données</h1>
        
        <?php if ($success): ?>
            <div class="success">
                <strong>✓ Succès !</strong><br>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error">
                <strong>✗ Erreur</strong><br>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <div class="info">
            <strong>Note de sécurité :</strong><br>
            Supprimez ce fichier (update-database-phone.php) après utilisation pour des raisons de sécurité.
        </div>
    </div>
</body>
</html>

