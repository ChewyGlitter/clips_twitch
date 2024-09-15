<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM streamers ORDER BY streamer_name ASC");
$streamers = $stmt->fetchAll();
?>

<h1>Liste des Streamers</h1>
<ul>
    <?php foreach ($streamers as $streamer): ?>
        <li>
            <strong><?php echo $streamer['streamer_name']; ?></strong> (ID: <?php echo $streamer['streamer_id']; ?>)
        </li>
    <?php endforeach; ?>
</ul>
