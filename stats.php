<?php
require_once 'db.php';
require_once 'auth.php';

// 1. STATISTIQUES DES BILLETS (Achetés vs Annulés)
$queryBillets = $pdo->query("SELECT 
    COUNT(b.Id_Billet) as total_billets,
    SUM(CASE WHEN c.Annule = 0 THEN 1 ELSE 0 END) as billets_valides,
    SUM(CASE WHEN c.Annule = 1 THEN 1 ELSE 0 END) as billets_annules
    FROM Billet b
    JOIN Commande c ON b.Id_Commande = c.Id_Commande");
$statsBillets = $queryBillets->fetch();

// 2. REVENUS (Total et Moyen par billet)
$queryRevenu = $pdo->query("SELECT 
    SUM(Prix_Final) as revenu_total,
    AVG(Prix_Final) as revenu_moyen
    FROM Billet b
    JOIN Commande c ON b.Id_Commande = c.Id_Commande
    WHERE c.Annule = 0");
$statsRevenu = $queryRevenu->fetch();

// 3. ARTISTES : LES PLUS VENDUS (Top 3)
$topArtists = $pdo->query("SELECT a.Nom_Artist, COUNT(b.Id_Billet) as nb_ventes, SUM(b.Prix_Final) as total_artiste
    FROM Billet b
    JOIN Spectacle s ON b.Id_Spectacle = s.Id_Spectacle
    JOIN Artist a ON s.Id_Artist = a.Id_Artist
    JOIN Commande c ON b.Id_Commande = c.Id_Commande
    WHERE c.Annule = 0
    GROUP BY a.Id_Artist
    ORDER BY nb_ventes DESC LIMIT 3")->fetchAll();

// 4. ARTISTE MOYEN (Performance moyenne des artistes)
$queryMoyenneArtiste = $pdo->query("SELECT AVG(total_ventes) FROM (
    SELECT COUNT(b.Id_Billet) as total_ventes 
    FROM Billet b 
    JOIN Commande c ON b.Id_Commande = c.Id_Commande 
    WHERE c.Annule = 0 GROUP BY b.Id_Spectacle) as sub");
$moyenneVentes = round($queryMoyenneArtiste->fetchColumn(), 1);

// 5. GENRES (PARTIS) LES PLUS ACHETÉS
$statsGenres = $pdo->query("SELECT g.Nom_Genre, COUNT(b.Id_Billet) as ventes_genre
    FROM Billet b
    JOIN Spectacle s ON b.Id_Spectacle = s.Id_Spectacle
    JOIN Artist a ON s.Id_Artist = a.Id_Artist
    JOIN Genre g ON a.Id_Genre = g.Id_Genre
    JOIN Commande c ON b.Id_Commande = c.Id_Commande
    WHERE c.Annule = 0
    GROUP BY g.Id_Genre
    ORDER BY ventes_genre DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques Détaillées - Bureau Vente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .stat-card { border-radius: 15px; padding: 20px; color: white; margin-bottom: 20px; }
        .bg-income { background: #27ae60; }
        .bg-tickets { background: #2980b9; }
        .bg-cancel { background: #e74c3c; }
        .bg-average { background: #f39c12; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">📊 Analyse des Ventes et Performances</h2>

        <div class="row">
            <div class="col-md-3">
                <div class="stat-card bg-income shadow">
                    <h5>Revenu Total</h5>
                    <h3><?php echo number_format($statsRevenu['revenu_total'], 2); ?> €</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-tickets shadow">
                    <h5>Billets Achetés</h5>
                    <h3><?php echo $statsBillets['billets_valides']; ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-cancel shadow">
                    <h5>Billets Annulés</h5>
                    <h3><?php echo $statsBillets['billets_annules']; ?></h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card bg-average shadow">
                    <h5>Prix Moyen Billet</h5>
                    <h3><?php echo round($statsRevenu['revenu_moyen'], 2); ?> €</h3>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">⭐ Top Artistes (Plus vendus)</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr><th>Artiste</th><th>Billets</th><th>Total</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach($topArtists as $art): ?>
                                <tr>
                                    <td><?php echo $art['Nom_Artist']; ?></td>
                                    <td><span class="badge bg-primary"><?php echo $art['nb_ventes']; ?></span></td>
                                    <td><?php echo number_format($art['total_artiste'], 2); ?> €</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="text-muted small">Moyenne de ventes par spectacle : <strong><?php echo $moyenneVentes; ?> billets</strong></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">🎸 Performance par Genre (Partis)</div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <?php foreach($statsGenres as $genre): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $genre['Nom_Genre']; ?>
                                <span class="badge bg-success rounded-pill"><?php echo $genre['ventes_genre']; ?> billets</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>