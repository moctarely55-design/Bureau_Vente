<?php 
include 'db.php'; 
// On vérifie que l'ID est présent
if(!isset($_GET['id'])) { header("Location: index.php"); exit(); }

$id = $_GET['id'];

// 1. Récupérer les infos du spectacle ET son prix de base
$stmt = $pdo->prepare("SELECT s.*, a.Nom_Artist, l.Nom_Lieu, l.Adresse 
                       FROM Spectacle s 
                       JOIN Artist a ON s.Id_Artist = a.Id_Artist
                       JOIN Lieu l ON s.Id_Lieu = l.Id_Lieu
                       WHERE s.Id_Spectacle = ?");
$stmt->execute([$id]);
$spec = $stmt->fetch();

// 2. Récupérer les artistes invités
$stmtSup = $pdo->prepare("SELECT a.Nom_Artist 
                          FROM est_supplementaire es
                          JOIN Artist a ON es.Id_Artist = a.Id_Artist
                          WHERE es.Id_Spectacle = ?");
$stmtSup->execute([$id]);
$extras = $stmtSup->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $spec['Titre']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h2><?php echo $spec['Titre']; ?></h2>
                <p class="lead">Artiste principal : <strong><?php echo $spec['Nom_Artist']; ?></strong></p>
                
                <?php if($extras): ?>
                    <h5>Artistes invités :</h5>
                    <ul>
                        <?php foreach($extras as $ex) echo "<li>".$ex['Nom_Artist']."</li>"; ?>
                    </ul>
                <?php endif; ?>

                <hr>
                <p>📍 Lieu : <?php echo $spec['Nom_Lieu']; ?> (<?php echo $spec['Adresse']; ?>)</p>
                <p>📅 Date : <?php echo $spec['Date_Spectacle']; ?></p>
                <p>💰 Prix : <strong><?php echo number_format($spec['Prix_Base'], 2); ?> €</strong></p>
            </div>
            
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h4>Réserver</h4>
                    <form action="reserver_action.php" method="POST">
                        <input type="hidden" name="id_spectacle" value="<?php echo $id; ?>">
                        <input type="hidden" name="prix_base" value="<?php echo $spec['Prix_Base']; ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Votre Client ID :</label>
                            <input type="number" name="id_client" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Choix de la place :</label>
                            <input type="text" name="place" class="form-control" placeholder="Ex: A12" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Confirmer l'achat</button>
                    </form>
                </div>
            </div>
        </div>
        <a href="index.php" class="btn btn-link mt-3 text-secondary">← Retour</a>
    </div>
</body>
</html>