<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">waxal alma</a>
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
<?php
session_start();
include 'config.php';
if (!isset($_SESSION['nom'])) {
    header('Location: login.php');
    exit();
}

$nom = $_SESSION['nom'];
$sql_user = "SELECT * FROM utilisateur WHERE nom = '$nom'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $user = $result_user->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['post_create'])) {
        $auteur = $user['nom'];
        $image = $_POST['image'];
        $text = $_POST['text'];
        $date_pub = $_POST['date_pub'];

        $sql_insert_post = "INSERT INTO publications (auteur, image, texte,date_pub) VALUES ('$auteur', '$image', '$texte','$date_pub')";
        $conn->query($sql_insert_post);

        header('Location: profile.php');
        exit();
    } elseif (isset($_POST['post_update'])) {
        $postID = $_POST['post_id'];
        $media = $_POST['post_media'];
        $text = $_POST['post_text'];

        $sql_update_post = "UPDATE publications SET media = '$media', texte = '$text' WHERE id = '$postID'";
        $conn->query($sql_update_post);

        header('Location: profile.php');
        exit();
    }
}

$sql_posts = "SELECT * FROM posts WHERE auteur = '$nom' ORDER BY created_at DESC";
$result_posts = $conn->query($sql_posts);
$posts = ($result_posts->num_rows > 0) ? $result_posts->fetch_all(MYSQLI_ASSOC) : array();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réseau social - Mon profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Mon profil</h2>
        <div>
            <p><strong>Nom d'utilisateur :</strong> <?php echo $user['nom']; ?></p>
            <p><strong>Nom complete :</strong> <?php echo $user['nom_et_prenom']; ?></p>
            <p><strong>Email :</strong> <?php echo $user['email']; ?></p>
        </div>
        <hr>
        <h3>Mes publications</h3>
        <?php if (count($posts) > 0) : ?>
            <?php foreach ($posts as $post) : ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Publication #<?php echo $post['id']; ?></h5>
                        <form method="POST" action="">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <div class="form-group">
                                <label for="image<?php echo $post['id']; ?>">Média :</label>
                                <input type="text" id="image<?php echo $post['id']; ?>" name="image" class="form-control" value="<?php echo $post['media']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="post_text_<?php echo $post['id']; ?>">Texte :</label>
                                <textarea id="text_<?php echo $post['id']; ?>" name="text" class="form-control"><?php echo $post['text']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="post_update">Modifier</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucune publication.</p>
        <?php endif; ?>
        <hr>
        
<h3>Créer une nouvelle publication</h3>
<form method="POST" action="" enctype="multipart/form-data">
    <div class="form-group">
        <label for="post_media">Média (image) :</label>
        <input type="file" id="image" name="image" class="form-control-file">
    </div>
    <div class="form-group">
        <label for="text">Texte :</label>
        <textarea id="text" name="text" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="post_create">Publier</button>
</form>


