<?php
include 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $streamer_name = $_POST['streamer_name'];
    $streamer_id = $_POST['streamer_id'];

    // Insertion du streamer dans la base de données
    $stmt = $pdo->prepare("INSERT INTO streamers (streamer_id, streamer_name) VALUES (?, ?)");
    $stmt->execute([$streamer_id, $streamer_name]);

    // Redirection vers le tableau de bord
    header("Location: dashboard.php");
}
?>
