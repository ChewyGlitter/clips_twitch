<?php
// Paramètres de connexion à la base de données
// db.php - Fichier de connexion à la base de données
$host = 'localhost';
$dbname = 'twitch_clips';
$user = 'root';  // À remplacer par votre utilisateur MySQL
$pass = '';      // À remplacer par votre mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>
