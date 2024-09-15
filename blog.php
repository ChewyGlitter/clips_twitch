<?php
include 'db.php';

$stmt = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $stmt->fetchAll();
?>

<h1>Blog</h1>
<div class="articles">
    <?php foreach ($articles as $article): ?>
        <div class="article">
            <h2><?php echo $article['title']; ?></h2>
            <p><?php echo substr($article['content'], 0, 200); ?>...</p>
            <a href="article.php?id=<?php echo $article['id']; ?>">Lire la suite</a>
        </div>
    <?php endforeach; ?>
</div>
