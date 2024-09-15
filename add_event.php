<?php
include 'db.php'; // Connexion à la base de données

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'] ?? null; // Optionnel
    $url = $_POST['url'] ?? null; // Optionnel

    // Insertion de l'événement dans la base de données
    $stmt = $pdo->prepare("INSERT INTO events (title, start, end, url) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $start, $end, $url]);

    // Redirection vers le tableau de bord après l'ajout
    header("Location: dashboard.php");
}
?>
