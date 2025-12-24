<?php
include 'db.php';
$id_billet = $_GET['id'] ?? die("ID manquant");

$sql = "SELECT b.*, s.Titre, s.Date_Spectacle, c.Nom_Client, a.Nom_Artist, l.Nom_Lieu 
        FROM Billet b
        JOIN Spectacle s ON b.Id_Spectacle = s.Id_Spectacle
        JOIN Artist a ON s.Id_Artist = a.Id_Artist
        JOIN Lieu l ON s.Id_Lieu = l.Id_Lieu
        JOIN Commande co ON b.Id_Commande = co.Id_Commande
        JOIN Client c ON co.Id_Client = c.Id_Client
        WHERE b.Id_Billet = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_billet]);
$billet = $stmt->fetch();

$qrData = "BILLET_ID:" . $billet['Id_Billet'];
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export PDF Billet #<?= $id_billet ?></title>
    <style>
        /* Style pour l'affichage écran */
        body { font-family: 'Arial', sans-serif; background: #555; display: flex; flex-direction: column; align-items: center; padding: 50px; }
        
        .ticket { 
            background: white; width: 180mm; height: 80mm; 
            display: flex; border-radius: 10px; overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.5); position: relative;
        }

        .left { flex: 2; padding: 30px; border-right: 2px dashed #ccc; }
        .right { flex: 1; background: #f9f9f9; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        
        h2 { margin: 0; color: #333; text-transform: uppercase; }
        .artist { color: #e63946; font-size: 1.2em; font-weight: bold; }
        .details { margin-top: 15px; font-size: 14px; }
        
        .btn-pdf { 
            margin-bottom: 20px; padding: 12px 25px; background: #27ae60; 
            color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; 
        }

        /* CONFIGURATION SPÉCIALE POUR LE PDF */
        @media print {
            body { background: white; padding: 0; margin: 0; }
            .btn-pdf { display: none; } /* Cache le bouton sur le PDF */
            .ticket { 
                box-shadow: none; border: 1px solid #000; 
                margin: 0; position: absolute; top: 0; left: 0;
            }
            @page { size: landscape; margin: 0; }
        }
    </style>
</head>
<body>

    <button class="btn-pdf" onclick="window.print()">📥 Télécharger / Imprimer en PDF</button>

    <div class="ticket">
        <div class="left">
            <h2><?= htmlspecialchars($billet['Titre']) ?></h2>
            <div class="artist"><?= htmlspecialchars($billet['Nom_Artist']) ?></div>
            <div class="details">
                <p>📍 <strong>Lieu :</strong> <?= htmlspecialchars($billet['Nom_Lieu']) ?></p>
                <p>📅 <strong>Date :</strong> <?= date('d/m/Y', strtotime($billet['Date_Spectacle'])) ?></p>
                <p>👤 <strong>Client :</strong> <?= htmlspecialchars($billet['Nom_Client']) ?></p>
                <p>💺 <strong>Place :</strong> <?= htmlspecialchars($billet['Place']) ?></p>
            </div>
        </div>
        <div class="right">
            <img src="<?= $qrCodeUrl ?>" alt="QR Code">
            <p style="font-size: 10px; color: #888;">ID: #<?= str_pad($billet['Id_Billet'], 6, "0", STR_PAD_LEFT) ?></p>
        </div>
    </div>

</body>
</html>