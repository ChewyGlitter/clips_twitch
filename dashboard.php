<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Récupérer le nombre total de streamers
$stmt = $pdo->query("SELECT COUNT(*) as total_streamers FROM streamers");
$total_streamers = $stmt->fetch()['total_streamers'];

// Récupérer le nombre total de clips
$stmt = $pdo->query("SELECT COUNT(*) as total_clips FROM clips");
$total_clips = $stmt->fetch()['total_clips'];

// Récupérer le nombre de clips des dernières 24 heures
$stmt = $pdo->query("SELECT COUNT(*) as recent_clips FROM clips WHERE created_at >= NOW() - INTERVAL 1 DAY");
$recent_clips = $stmt->fetch()['recent_clips'];

// Récupérer les derniers clips
$stmt = $pdo->query("SELECT * FROM clips ORDER BY created_at DESC LIMIT 10");
$recent_clips_list = $stmt->fetchAll();

// Récupérer tous les utilisateurs
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Récupérer les clips ajoutés dans les dernières 24 heures
$stmt = $pdo->query("SELECT COUNT(*) as recent_clips FROM clips WHERE created_at >= NOW() - INTERVAL 1 DAY");
$recent_clips = $stmt->fetch()['recent_clips'];

if ($recent_clips > 0) {
    echo "<div class='alert'>Nouveaux clips ajoutés dans les dernières 24 heures: $recent_clips</div>";
}

// Récupérer les streamers ayant généré le plus de clips
$stmt = $pdo->query("SELECT streamer_name, COUNT(*) as clip_count FROM clips GROUP BY streamer_name ORDER BY clip_count DESC LIMIT 10");
$top_streamers = $stmt->fetchAll();

// Récupérer tous les streamers
$stmt = $pdo->query("SELECT * FROM streamers ORDER BY created_at DESC");
$streamers = $stmt->fetchAll(); // Récupérer les résultats dans un tableau


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="dashboard.css"> <!-- Lien vers le fichier CSS -->
</head>
<body>

    <style>
        body{
            background: url('assets/bg-DiuBldD_.jpg') no-repeat center center fixed;
        }
    </style>

    <h1>Tableau de Bord</h1>
    
    <div class="stats">
        <div class="stat">
            <h3>Total des streamers</h3>
            <p><?php echo $total_streamers; ?></p>
        </div>
        <div class="stat">
            <h3>Total des clips</h3>
            <p><?php echo $total_clips; ?></p>
        </div>
        <div class="stat">
            <h3>Clips ajoutés dans les dernières 24 heures</h3>
            <p><?php echo $recent_clips; ?></p>
        </div>
    </div>

    <h2>Clips récents</h2>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Streamer</th>
                <th>Créé le</th>
                <th>Voir le clip</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recent_clips_list as $clip): ?>
            <tr>
                <td><?php echo $clip['title']; ?></td>
                <td><?php echo $clip['streamer_name']; ?></td>
                <td><?php echo $clip['created_at']; ?></td>
                <td><a href="<?php echo $clip['url']; ?>" target="_blank">Voir</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Gestion des streamers</h2>

<!-- Table pour lister les streamers existants -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($streamers as $streamer): ?>
        <tr>
            <td><?php echo $streamer['streamer_id']; ?></td>
            <td><?php echo $streamer['streamer_name']; ?></td>
            <td>
                <a href="edit_streamer.php?id=<?php echo $streamer['id']; ?>">Modifier</a>
                <a href="delete_streamer.php?id=<?php echo $streamer['id']; ?>">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Formulaire pour ajouter ou modifier un streamer -->
<h2>Ajouter/Modifier un Streamer</h2>
<form action="add_edit_streamer.php" method="POST" enctype="multipart/form-data">
    <label for="streamer_id">ID Twitch :</label>
    <input type="text" id="streamer_id" name="streamer_id" required>

    <label for="streamer_name">Nom du streamer :</label>
    <input type="text" id="streamer_name" name="streamer_name" required>

    <label for="bio">Biographie :</label>
    <textarea id="bio" name="bio"></textarea>

    <label for="profile_image">Image de profil (URL ou fichier) :</label>
    <input type="file" id="profile_image" name="profile_image">

    <button type="submit">Enregistrer</button>
</form>

    <h2>Publier un article</h2>
    <form action="add_article.php" method="POST">
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" required>

        <label for="content">Contenu :</label>
        <textarea id="content" name="content" required></textarea>

        <label for="author">Auteur :</label>
        <input type="text" id="author" name="author">

        <button type="submit">Publier</button>
    </form>
    
    <h2>Ajouter un événement</h2>
    <form action="add_event.php" method="POST">
        <label for="title">Titre de l'événement :</label>
        <input type="text" id="title" name="title" required>

        <label for="start">Date de début :</label>
        <input type="datetime-local" id="start" name="start" required>

        <label for="end">Date de fin (optionnel) :</label>
        <input type="datetime-local" id="end" name="end">

        <label for="url">Lien de l'événement (optionnel) :</label>
        <input type="url" id="url" name="url">

        <button type="submit">Ajouter l'événement</button>
    </form>


</body>
</html>
