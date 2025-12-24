<?php 
require_once 'auth.php';
include 'db.php';
$search = $_GET['search'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Liste des Commandes</h4>
                <form class="d-flex" method="GET">
                    <input class="form-control me-2" type="search" name="search" placeholder="Nom du client..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-primary" type="submit">Filtrer</button>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>État</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT c.*, cl.Nom_Client FROM Commande c 
                                JOIN Client cl ON c.Id_Client = cl.Id_Client 
                                WHERE cl.Nom_Client LIKE ? ORDER BY c.Id_Commande DESC";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(["%$search%"]);
                        
                        while ($row = $stmt->fetch()): 
                            $statusClass = $row['Annule'] ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success';
                            $statusText = $row['Annule'] ? 'ANNULÉE' : 'VALIDE';
                        ?>
                            <tr>
                                <td class="ps-3 fw-bold">#<?= $row['Id_Commande'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['Date_Commande'])) ?></td>
                                <td><?= $row['Nom_Client'] ?></td>
                                <td><span class="badge <?= $statusClass ?>"><?= $statusText ?></span></td>
                                <td class="text-end pe-3">
                                    <?php if(!$row['Annule']): ?>
                                        <a href="annuler_action.php?id=<?= $row['Id_Commande'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Annuler cette commande ?')">Annuler</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>