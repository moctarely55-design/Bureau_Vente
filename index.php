<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Billetterie - Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-5 fw-bold">Spectacles à l'affiche</h1>
                <p class="text-muted">Réservez vos places pour les meilleurs événements.</p>
            </div>
        </div>

        <div class="row">
            <?php
            $query = $pdo->query("SELECT s.*, a.Nom_Artist, l.Nom_Lieu 
                                 FROM Spectacle s 
                                 JOIN Artist a ON s.Id_Artist = a.Id_Artist
                                 JOIN Lieu l ON s.Id_Lieu = l.Id_Lieu");
            while ($row = $query->fetch()): ?>
                <div class='col-md-4 mb-4'>
                    <div class='card h-100 border-0 shadow-sm'>
                        <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 150px;">
                            <i class="bi bi-music-note-beamed display-1"></i>
                        </div>
                        <div class='card-body'>
                            <h5 class='card-title fw-bold text-uppercase'><?= $row['Titre'] ?></h5>
                            <p class='card-text mb-1'><i class="bi bi-person-fill"></i> <?= $row['Nom_Artist'] ?></p>
                            <p class='card-text small text-muted'><i class="bi bi-geo-alt-fill"></i> <?= $row['Nom_Lieu'] ?></p>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill bg-dark"><?= date('d M Y', strtotime($row['Date_Spectacle'])) ?></span>
                                <a href='details.php?id=<?= $row['Id_Spectacle'] ?>' class='btn btn-primary btn-sm'>Réserver</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>