<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $messageText = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($subject) || empty($messageText)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } elseif (!empty($phone) && !preg_match('/^[\d\s\-\+\(\)]+$/', $phone)) {
        $error = 'Format de numéro de téléphone invalide.';
    } else {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone ?: null, $subject, $messageText]);
            $message = 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.';
            
            // Réinitialiser les champs
            $name = $email = $phone = $subject = $messageText = '';
        } catch (Exception $e) {
            $error = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

$pageTitle = "Contact";
$pageStyle = "contact";
include 'includes/header.php';
?>

<section class="contact-page section">
    <div class="container">
        <h1 class="page-title">Contactez-nous</h1>
        
        <div class="contact-layout">
            <div class="contact-info">
                <h2>Informations de contact</h2>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Adresse</h3>
                        <p>123 Rue de la Technologie<br>75001 Paris, France</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Téléphone</h3>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p>contact@phonestore.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Horaires</h3>
                        <p>Lundi - Vendredi : 9h - 18h<br>Samedi : 10h - 16h</p>
                    </div>
                </div>
            </div>
            
            <div class="contact-form-wrapper">
                <h2>Envoyez-nous un message</h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo escape($message); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo escape($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" class="contact-form">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo isset($name) ? escape($name) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($email) ? escape($email) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Numéro de téléphone</label>
                        <input type="tel" id="phone" name="phone" 
                               placeholder="+33 1 23 45 67 89"
                               pattern="[\d\s\-\+\(\)]+"
                               value="<?php echo isset($phone) ? escape($phone) : ''; ?>">
                        <small class="form-hint">Format: +33 1 23 45 67 89 (optionnel)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <input type="text" id="subject" name="subject" required 
                               value="<?php echo isset($subject) ? escape($subject) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required><?php echo isset($messageText) ? escape($messageText) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-large">
                        <i class="fas fa-paper-plane"></i> Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

