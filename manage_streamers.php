<?php
// manage_streamers.php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'add') {
        $streamer_id = $_POST['streamer_id'];
        $streamer_name = $_POST['streamer_name'];
        
        $stmt = $pdo->prepare("INSERT INTO streamers (streamer_id, streamer_name) VALUES (?, ?)");
        $stmt->execute([$streamer_id, $streamer_name]);
        
        echo "Streamer ajouté avec succès.";
    } elseif ($_POST['action'] === 'delete') {
        $id = $_POST['id'];
        
        $stmt = $pdo->prepare("DELETE FROM streamers WHERE id = ?");
        $stmt->execute([$id]);
        
        echo "Streamer supprimé avec succès.";
    }
}
?>
