<?php
include 'db.php';

// On vérifie si l'ID est présent dans l'URL
if (isset($_GET['id'])) {
    $id_commande = $_GET['id'];

    try {
        // Requête de mise à jour : on passe 'Annule' à 1 (vrai)
        $sql = "UPDATE Commande SET Annule = 1 WHERE Id_Commande = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_commande]);

        // Redirection avec un message de succès (optionnel)
        header("Location: admin_commandes.php?msg=canceled");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'annulation : " . $e->getMessage());
    }
} else {
    header("Location: admin_commandes.php");
    exit();
}
?>