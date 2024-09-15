<?php
session_start();
include 'db.php'; // Connexion à la base de données

// Paramètres Twitch
$client_id = 'ohr7okfm5kgta9ohyxxylo15ryemh3';
$redirect_uri = 'http://localhost/callback'; // L'URL définie dans votre application Twitch

// Récupérer tous les clips depuis la base de données
$stmt = $pdo->query("SELECT * FROM clips ORDER BY created_at DESC");
$clips = $stmt->fetchAll();

// Message de succès après la suppression des clips plus vieux que 24 heures
if (isset($_GET['message']) && $_GET['message'] == 'old_deleted') {
    echo "<p style='color: green;'>Tous les clips plus vieux que 24 heures ont été supprimés avec succès !</p>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Derniers Clips Twitch</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


    <!-- Barre de navigation -->
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="dashboard.php">Tableau de Bord</a></li>
                <li><a href="calendar.php">Calendrier</a></li>
                <li><?php
                    if (!isset($_SESSION['twitch_username'])) {
                        echo '<a href="https://id.twitch.tv/oauth2/authorize?client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&response_type=code&scope=user:read:email">
                            <img src="https://static.twitchcdn.net/assets/signin-light.png" alt="Se connecter avec Twitch">
                        </a>';
                    } else {
                        // Si l'utilisateur est connecté, afficher un message de bienvenue
                        echo '<p>Bienvenue, ' . $_SESSION['twitch_username'] . ' !</p>';
                        echo '<a href="logout.php">Se déconnecter</a>';
                    }
                    ?>
                </li>
            </ul>
        </nav>
    </header>
    
    <h1>Derniers Clips Twitch</h1>

    <div class="clips-container">

        <?php foreach ($clips as $clip): ?>
            <div class="clip">
                <img src="<?php echo $clip['thumbnail_url']; ?>" alt="<?php echo $clip['title']; ?>">
                <h3><?php echo $clip['title']; ?></h3>
                <p>Streamer : <?php echo $clip['streamer_name']; ?></p>
                <a href="<?php echo $clip['url']; ?>" target="_blank">Voir le clip</a>
            </div>
        <?php endforeach; ?>

    </div>

    <!-- Ajout du bouton "Supprimer tous les clips" -->
    <form action="delete_old_clips.php" method="POST" onsubmit="return confirmDelete();">
        <button type="submit">Supprimer les clips de plus de 24 heures</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Fonction de confirmation avant suppression
    function confirmDelete() {
        return confirm("Êtes-vous sûr de vouloir supprimer tous les clips ?");
    }

    // Fonction pour charger les clips via AJAX
    function loadClips() {
        $.ajax({
            url: 'fetch_clips_ajax.php',
            method: 'GET',
            success: function(data) {
                var clipsContainer = document.getElementById('clips-container');
                clipsContainer.innerHTML = ''; // Vider le conteneur avant d'ajouter de nouveaux clips

                data.forEach(function(clip) {
                    var clipDiv = document.createElement('div');
                    clipDiv.classList.add('clip');

                    clipDiv.innerHTML = `
                        <img src="${clip.thumbnail_url}" alt="${clip.title}">
                        <h3>${clip.title}</h3>
                        <p>Streamer : ${clip.streamer_name}</p>
                        <a href="${clip.url}" target="_blank">Voir le clip</a>
                    `;

                    clipsContainer.appendChild(clipDiv);
                });
            }
        });
    }

    setInterval(function(){
        $.get('http://localhost/fetch_clips.php', function(data) {
            console.log(data);
         });
    }, 120000);

    // Charger les clips immédiatement et toutes les 2 minutes
    loadClips();
    setInterval(loadClips, 120000); // 120000 ms = 2 minutes
    
    </script>

</body>
</html>