<?php
require_once 'config.php';

// Vérifier si l'utilisateur est déjà connecté
if (isset($_SESSION['nom'])) {
    header('Location: index.php');
    exit();
}

// Traitement du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $nom_et_prenom = $_POST['nom_et_prenom'];
    $email = $_POST['email'];

    // Vérifier si l'utilisateur existe déjà
    $sql = "SELECT * FROM utilisateur WHERE nom = '$nom'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = 'Ce nom d\'utilisateur est déjà pris.';
    } else {
        // Hash du mot de passe
        $hashedPassword = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        // Insérer l'utilisateur dans la base de données
        $sql = "INSERT INTO utilisateur (nom, mot_de_passe, nom_et_prenom, email) VALUES ('$nom', '$hashedPassword', '$nom_et_prenom', '$email')";
        if ($conn->query($sql) === TRUE) {
            header('Location: login.php');
            exit();
        } else {
            $error = 'Une erreur est survenue lors de l\'inscription.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inscription</title>
    <link rel="stylesheet "href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Inscription</h2>
        <?php if (isset($error)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nom">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <div class="form-group">
                <label for="nom_et_prenom">Nom complet</label>
                <input type="text" class="form-control" id="nom_et_prenom" name="nom_et_prenom" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p class="mt-3">Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
