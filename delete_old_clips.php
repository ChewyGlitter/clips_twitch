<?php
include 'db.php';  // Connexion à la base de données

try {
    // Requête SQL pour supprimer les clips plus vieux que 24 heures
    $sql = "DELETE FROM clips WHERE created_at < NOW() - INTERVAL 1 DAY";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Redirection vers la page d'accueil avec un message de succès
    header("Location: index.php?message=old_deleted");
    exit();
} catch (Exception $e) {
    echo "Erreur lors de la suppression des clips : " . $e->getMessage();
}
?>
