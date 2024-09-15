<?php
include 'db.php'; // Connexion à la base de données

// Récupérer les informations du streamer à modifier
if (isset($_GET['id'])) {
    $streamer_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM streamers WHERE id = ?");
    $stmt->execute([$streamer_id]);
    $streamer = $stmt->fetch();

    if (!$streamer) {
        echo "Streamer introuvable.";
        exit;
    }
} else {
    echo "Aucun streamer spécifié.";
    exit;
}
?>

<h2>Modifier le Streamer</h2>
<form action="add_edit_streamer.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $streamer['id']; ?>">

    <label for="streamer_id">ID Twitch :</label>
    <input type="text" id="streamer_id" name="streamer_id" value="<?php echo $streamer['streamer_id']; ?>" required>

    <label for="streamer_name">Nom du streamer :</label>
    <input type="text" id="streamer_name" name="streamer_name" value="<?php echo $streamer['streamer_name']; ?>" required>

    <label for="bio">Biographie :</label>
    <textarea id="bio" name="bio"><?php echo $streamer['bio']; ?></textarea>

    <label for="profile_image">Image de profil actuelle :</label>
    <img src="<?php echo $streamer['profile_image']; ?>" alt="Image de profil" style="width:100px;"><br>

    <label for="profile_image">Changer l'image de profil :</label>
    <input type="file" id="profile_image" name="profile_image">

    <button type="submit">Modifier</button>
</form>
