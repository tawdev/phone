<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$db = Database::getInstance()->getConnection();

// Marquer comme lu
if (isset($_GET['read'])) {
    $id = intval($_GET['read']);
    $stmt = $db->prepare("UPDATE contact_messages SET read_status = 1 WHERE id = ?");
    $stmt->execute([$id]);
    redirect(BASE_URL . 'admin/messages.php');
}

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    redirect(BASE_URL . 'admin/messages.php');
}

$stmt = $db->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

$pageTitle = "Messages de contact";
?>

<div class="admin-page-header">
    <h2>Messages de contact</h2>
</div>

<?php if (!empty($messages)): ?>
    <div class="messages-list">
        <?php foreach ($messages as $msg): ?>
            <div class="message-card <?php echo $msg['read_status'] ? '' : 'unread'; ?>">
                <div class="message-header">
                    <div>
                        <h3><?php echo escape($msg['subject']); ?></h3>
                        <p class="message-meta">
                            <strong><?php echo escape($msg['name']); ?></strong> 
                            &lt;<?php echo escape($msg['email']); echo '&gt;'; ?>
                            <?php if (!empty($msg['phone'])): ?>
                                <span class="message-phone">
                                    <i class="fas fa-phone"></i> <?php echo escape($msg['phone']); ?>
                                </span>
                            <?php endif; ?>
                            <span class="message-date"><?php echo date('d/m/Y à H:i', strtotime($msg['created_at'])); ?></span>
                        </p>
                    </div>
                    <div class="message-actions">
                        <?php if (!$msg['read_status']): ?>
                            <a href="?read=<?php echo $msg['id']; ?>" class="btn btn-sm btn-primary" title="Marquer comme lu">
                                <i class="fas fa-check"></i>
                            </a>
                        <?php endif; ?>
                        <a href="?delete=<?php echo $msg['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');"
                           title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </div>
                <div class="message-body">
                    <p><?php echo nl2br(escape($msg['message'])); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucun message pour le moment.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

