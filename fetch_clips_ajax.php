<?php
include 'db.php';  // Connexion à la base de données

// Récupérer les clips depuis la base de données
$stmt = $pdo->query("SELECT * FROM clips ORDER BY created_at DESC");
$clips = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner les clips au format JSON
header('Content-Type: application/json');
echo json_encode($clips);
?>
