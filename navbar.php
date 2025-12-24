<?php
// On démarre la session pour vérifier si l'admin est connecté
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <span class="text-primary">🎫</span> BUREAU VENTE
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-house-door"></i> Accueil
                    </a>
                </li>

                <?php if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_commandes.php">
                            <i class="bi bi-speedometer2"></i> Gestion Commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stats.php">
                            <i class="bi bi-bar-chart"></i> Statistiques
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="scanner_billet.php">
                            <i class="bi bi-qr-code-scan"></i> Scanner Billet
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true): ?>
                    <span class="badge bg-primary me-3">Mode Admin</span>
                    <a href="logout.php" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-lock"></i> Connexion
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">