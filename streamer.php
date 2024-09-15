<?php
include 'db.php';

// Variables pour transmettre les messages d'ajout/suppression
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $streamer_id = $_POST['streamer_id'];
        $streamer_name = $_POST['streamer_name'];
        
        $stmt = $pdo->prepare("INSERT INTO streamers (streamer_id, streamer_name) VALUES (?, ?)");
        if ($stmt->execute([$streamer_id, $streamer_name])) {
            $success_message = "Streamer ajouté avec succès.";
        } else {
            $error_message = "Erreur lors de l'ajout du streamer.";
        }
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        
        $stmt = $pdo->prepare("DELETE FROM streamers WHERE id = ?");
        if ($stmt->execute([$id])) {
            $success_message = "Streamer supprimé avec succès.";
        } else {
            $error_message = "Erreur lors de la suppression du streamer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les streamers</title>
    <script>
        // Afficher un message d'alerte si l'ajout ou la suppression a été effectué
        window.onload = function() {
            <?php if ($success_message): ?>
                alert("<?php echo $success_message; ?>");
            <?php elseif ($error_message): ?>
                alert("<?php echo $error_message; ?>");
            <?php endif; ?>
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h1>Ajouter un nouveau streamer</h1>
    <form method="POST" action="streamers.php">
        <label for="streamer_id">Streamer ID (Twitch):</label>
        <input type="text" name="streamer_id" required>
        <label for="streamer_name">Nom du Streamer:</label>
        <input type="text" name="streamer_name" required>
        <button type="submit" name="action" value="add">Ajouter Streamer</button>
    </form>

    <h2>Liste des streamers</h2>
    <ul>
        <?php
        $stmt = $pdo->query("SELECT id, streamer_name FROM streamers");
        while ($row = $stmt->fetch()) {
            echo "<li>{$row['streamer_name']} 
                  <form method='POST' style='display:inline;' action='streamers.php'>
                  <input type='hidden' name='id' value='{$row['id']}'>
                  <button type='submit' name='action' value='delete'>Supprimer</button>
                  </form></li>";
        }
        ?>
    </ul>
</body>
</html>
