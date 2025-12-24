<?php
// 1. Inclusion de la connexion à la base de données
require_once 'db.php';

// 2. Vérification que le formulaire a bien été soumis en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 3. Récupération et nettoyage des données (sécurité de base)
    $id_client = filter_input(INPUT_POST, 'id_client', FILTER_VALIDATE_INT);
    $id_spec   = filter_input(INPUT_POST, 'id_spectacle', FILTER_VALIDATE_INT);
    $place     = htmlspecialchars($_POST['place']); // Empêche les failles XSS
    $prix      = 75.00; // Prix fixe ou récupéré d'une autre table
    $date_cmd  = date('Y-m-d');

    // Vérification que les champs ne sont pas vides
    if (!$id_client || !$id_spec || empty($place)) {
        die("Erreur : Formulaire invalide ou données manquantes.");
    }

    try {
        // 4. Début de la Transaction
        // On s'assure que TOUT s'exécute ou que RIEN ne s'enregistre
        $pdo->beginTransaction();

        // 5. Insertion dans la table COMMANDE
        $sqlCmd = "INSERT INTO Commande (Date_Commande, Annule, Id_Client) VALUES (:date_cmd, 0, :id_client)";
        $stmtCmd = $pdo->prepare($sqlCmd);
        $stmtCmd->execute([
            ':date_cmd' => $date_cmd,
            ':id_client' => $id_client
        ]);

        // On récupère l'ID de la commande que l'on vient de créer
        $id_nouvelle_commande = $pdo->lastInsertId();

        // 6. Insertion dans la table BILLET
        $sqlBillet = "INSERT INTO Billet (Prix, Place, Id_Commande, Id_Spectacle) 
                      VALUES (:prix, :place, :id_cmd, :id_spec)";
        $stmtBillet = $pdo->prepare($sqlBillet);
        $stmtBillet->execute([
            ':prix'  => $prix,
            ':place' => $place,
            ':id_cmd' => $id_nouvelle_commande,
            ':id_spec' => $id_spec
        ]);

        // 7. Validation finale (Commit)
        $pdo->commit();

        // Redirection vers une page de succès
        header("Location: index.php?statut=success");
        exit();

    } catch (PDOException $e) {
        // 8. En cas d'erreur (ex: place déjà prise), on annule tout
        $pdo->rollBack();
        
        // Message d'erreur spécifique pour la contrainte UNIQUE (Place, Id_Spectacle)
        if ($e->getCode() == 23000) {
            echo "Erreur : Cette place est déjà réservée pour ce spectacle !";
        } else {
            echo "Une erreur est survenue : " . $e->getMessage();
        }
    }
} else {
    // Si quelqu'un tente d'accéder au fichier sans formulaire
    header("Location: index.php");
    exit();
}
?>