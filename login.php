<?php
session_start();

// CONFIGURATION : Changez vos identifiants ici
$admin_user = "admin";
$admin_pass = "12345"; 

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['username'] === $admin_user && $_POST['password'] === $admin_pass) {
        $_SESSION['admin_logged'] = true;
        header("Location: admin_commandes.php");
        exit();
    } else {
        $erreur = "Identifiants incorrects !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Connexion</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow p-4" style="max-width: 350px;">
            <h4 class="text-center mb-4">Admin Login</h4>
            <?php if($erreur): ?> <div class="alert alert-danger p-2 small"><?= $erreur ?></div> <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Utilisateur" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Entrer</button>
            </form>
        </div>
    </div>
</body>
</html>