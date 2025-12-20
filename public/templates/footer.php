</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Chrysalide</h3>
                <p>Plateforme de lecture et d'écriture d'histoires</p>
            </div>

            <div class="footer-section">
                <h4>Navigation</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_PATH ?>">Découvrir les histoires</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="<?= BASE_PATH ?>dashboard.php">Mon espace</a></li>
                        <?php if ($isAuthor): ?>
                            <li><a href="<?= BASE_PATH ?>my_stories.php">Mes histoires</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="<?= BASE_PATH ?>register.php">Créer un compte</a></li>
                        <li><a href="<?= BASE_PATH ?>login.php">Se connecter</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="footer-section">
                <h4>À propos</h4>
                <ul class="footer-links">
                    <li><a href="#">Qui sommes-nous ?</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                    <li><a href="#">Confidentialité</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Chrysalide. Tous droits réservés.</p>
            <p>Projet réalisé dans le cadre du cours ProgServ2 - HEIG-VD</p>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0 1rem;
        margin-top: auto;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .footer-section h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .footer-section h4 {
        font-size: 1.1rem;
        margin-bottom: 0.8rem;
        font-weight: 600;
    }

    .footer-section p {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: white;
        text-decoration: underline;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 1.5rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.8);
    }

    .footer-bottom p {
        margin: 0.3rem 0;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .footer-content {
            grid-template-columns: 1fr;
            text-align: center;
        }
    }
</style>
</body>

</html>