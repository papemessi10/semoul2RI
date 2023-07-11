<?php
session_start();
include 'config.php';

if (!isset($_SESSION['nom'])) {
    header('Location: login.php');
    exit();
}

$nom = $_SESSION['nom'];

$posts = array();
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
$publications = array();
$sql = "SELECT * FROM publications ORDER BY date_pub DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $publications[] = $row;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Réseau social</title>
<link rel="stylesheet "href="bootstrap/css/bootstrap.min.css">

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">waxal alma</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Mon compte</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="deconnexion.php">Déconnexion</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>File d'actualité</h2>
        <hr>
        <?php foreach ($posts as $post) : ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $post['auteur']; ?></h5>
                    <img src="images/<?php echo $row['image']; ?>" class="card-img-top" alt="Livre">
                    <?php if (!empty($post['media'])) : ?>
                        <?php if (strpos($post['media'], 'image') !== false) : ?>
                            <img src="<?php echo $post['media']; ?>" class="card-img-top" alt="Media">
                        <?php elseif (strpos($post['media'], 'video') !== false) : ?>
                            <video src="<?php echo $post['media']; ?>" class="card-img-top" controls></video>
                        <?php endif; ?>
                    <?php endif; ?>
                    <p class="card-text"><?php echo $post['text']; ?></p>
                    <button class="btn btn-primary">Aimer</button>
                    <button class="btn btn-secondary">Partager</button>
                    <input type="text" class="form-control mt-2" placeholder="Commentaire">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
