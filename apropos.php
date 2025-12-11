<?php
$pageTitle = "À propos";
$pageStyle = "about";
include 'includes/header.php';
?>

<section class="about-page section">
    <div class="container">
        <h1 class="page-title">À propos de PhoneStore</h1>
        
        <div class="about-content">
            <div class="about-section">
                <h2>Notre Histoire</h2>
                <p>
                    PhoneStore est née de la passion pour la technologie mobile. Depuis notre création, 
                    nous nous engageons à offrir à nos clients les meilleurs téléphones et accessoires 
                    au meilleur prix.
                </p>
                <p>
                    Notre équipe de passionnés sélectionne rigoureusement chaque produit pour garantir 
                    qualité, performance et satisfaction client.
                </p>
            </div>
            
            <div class="about-section">
                <h2>Notre Mission</h2>
                <p>
                    Fournir à nos clients une expérience d'achat exceptionnelle avec :
                </p>
                <ul class="mission-list">
                    <li><i class="fas fa-check"></i> Des produits de qualité supérieure</li>
                    <li><i class="fas fa-check"></i> Des prix compétitifs</li>
                    <li><i class="fas fa-check"></i> Un service client réactif</li>
                    <li><i class="fas fa-check"></i> Une livraison rapide et sécurisée</li>
                </ul>
            </div>
            
            <div class="about-section">
                <h2>Pourquoi nous choisir ?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Garantie Qualité</h3>
                        <p>Tous nos produits sont garantis et testés</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-shipping-fast"></i>
                        <h3>Livraison Rapide</h3>
                        <p>Expédition sous 24-48h</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-headset"></i>
                        <h3>Support Client</h3>
                        <p>Assistance disponible 7j/7</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <h3>Meilleur Prix</h3>
                        <p>Garantie du meilleur prix</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

