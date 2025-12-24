<?php 
require_once 'auth.php'; 
require_once 'db.php'; 

// Initialisation des variables pour éviter les erreurs "Undefined variable"
$message = "";
$status = "info"; 
$billet_info = null;

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_billet = $_GET['id'];

    try {
        // Requête pour vérifier l'existence et l'état d'annulation
        $sql = "SELECT b.*, s.Titre, c.Nom_Client, co.Annule 
                FROM Billet b
                JOIN Spectacle s ON b.Id_Spectacle = s.Id_Spectacle
                JOIN Commande co ON b.Id_Commande = co.Id_Commande
                JOIN Client c ON co.Id_Client = c.Id_Client
                WHERE b.Id_Billet = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_billet]);
        $billet_info = $stmt->fetch();

        if (!$billet_info) {
            $message = "❌ ERREUR : Billet invalide ou introuvable.";
            $status = "danger";
        } elseif ($billet_info['Annule'] == 1) {
            $message = "⚠️ ATTENTION : Ce billet appartient à une commande ANNULÉE.";
            $status = "warning";
        } else {
            $message = "✅ ACCÈS AUTORISÉ : Billet valide.";
            $status = "success";
        }
    } catch (PDOException $e) {
        $message = "Erreur SQL : " . $e->getMessage();
        $status = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Scanner de Billets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container mt-5 text-center">
        <div class="card shadow-lg mx-auto" style="max-width: 500px;">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Contrôle d'accès</h4>
            </div>
            <div class="card-body py-5">
                
                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $status ?> mb-4 py-4">
                        <h4><?= $message ?></h4>
                    </div>
                    
                    <?php if ($billet_info && $status === 'success'): ?>
                        <div class="text-start p-3 bg-light border rounded mb-3">
                            <p><strong>Spectacle :</strong> <?= htmlspecialchars($billet_info['Titre']) ?></p>
                            <p><strong>Client :</strong> <?= htmlspecialchars($billet_info['Nom_Client']) ?></p>
                            <p><strong>Place :</strong> <?= htmlspecialchars($billet_info['Place']) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <hr>
                    <a href="scanner_billet.php" class="btn btn-primary">Scanner un autre billet</a>
                
                <?php else: ?>
                    <p class="text-muted">Entrez l'ID du billet pour simuler un scan :</p>
                    <form method="GET" class="d-flex gap-2 justify-content-center">
                        <input type="number" name="id" class="form-control w-50" placeholder="Ex: 1" required autofocus>
                        <button type="submit" class="btn btn-dark">Vérifier</button>
                    </form>
                <?php endif; ?>

            </div>
        </div>
    </div>
</body>
</html>