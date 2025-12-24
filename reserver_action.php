<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id_client = filter_input(INPUT_POST, 'id_client', FILTER_VALIDATE_INT);
    $id_spec   = filter_input(INPUT_POST, 'id_spectacle', FILTER_VALIDATE_INT);
    $place     = htmlspecialchars($_POST['place']); 
    // On récupère le prix dynamique envoyé par le formulaire
    $prix      = filter_input(INPUT_POST, 'prix_base', FILTER_VALIDATE_FLOAT);
    $date_cmd  = date('Y-m-d H:i:s');

    if (!$id_client || !$id_spec || empty($place) || !$prix) {
        die("Erreur : Données du formulaire invalides.");
    }

    try {
        $pdo->beginTransaction();

        // Insertion dans Commande
        $sqlCmd = "INSERT INTO Commande (Date_Commande, Annule, Id_Client) VALUES (:date_cmd, 0, :id_client)";
        $stmtCmd = $pdo->prepare($sqlCmd);
        $stmtCmd->execute([
            ':date_cmd' => $date_cmd,
            ':id_client' => $id_client
        ]);

        $id_nouvelle_commande = $pdo->lastInsertId();

        // Insertion dans Billet (CORRECTION : Prix_Final au lieu de Prix)
        $sqlBillet = "INSERT INTO Billet (Prix_Final, Place, Id_Commande, Id_Spectacle) 
                      VALUES (:prix, :place, :id_cmd, :id_spec)";
        $stmtBillet = $pdo->prepare($sqlBillet);
        $stmtBillet->execute([
            ':prix'  => $prix,
            ':place' => $place,
            ':id_cmd' => $id_nouvelle_commande,
            ':id_spec' => $id_spec
        ]);

        $id_nouveau_billet = $pdo->lastInsertId();
        $pdo->commit();

        header("Location: imprimer_ticket.php?id=" . $id_nouveau_billet);
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        if ($e->getCode() == 23000) {
            die("Erreur : Cette place est déjà réservée. <a href='index.php'>Retour</a>");
        } else {
            die("Une erreur critique est survenue : " . $e->getMessage());
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>