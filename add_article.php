<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_POST['author'];

    $stmt = $pdo->prepare("INSERT INTO articles (title, content, author) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $author]);

    header("Location: blog.php"); // Redirection vers la page du blog
}
?>
