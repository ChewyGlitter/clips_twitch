<?php
include 'db.php'; // Connexion à la base de données
include 'twitch_auth.php'; // Récupération du token d'accès Twitch

// Récupérer tous les streamers de la table `streamers`
$stmt = $pdo->query("SELECT streamer_id, streamer_name FROM streamers");
$streamers = $stmt->fetchAll();

// Parcourir chaque streamer pour récupérer ses clips
foreach ($streamers as $streamer) {
    $streamer_id = $streamer['streamer_id'];
    $streamer_name = $streamer['streamer_name'];

    // Récupérer les nouveaux clips du streamer
    $clips = getRecentClips($streamer_id, $access_token);

    // Vérifier si la réponse contient bien des clips
    if (isset($clips['data']) && !empty($clips['data'])) {
        // Sauvegarder les clips dans la base de données
        saveClipsToDatabase($clips, $pdo, $streamer_name);
    } else {
        // Afficher une erreur si aucun clip n'est récupéré
        echo "Aucun clip récupéré pour le streamer : " . $streamer_name;
    }
}

// Fonction pour récupérer les clips récents d'un streamer
function getRecentClips($streamer_id, $access_token) {
    global $client_id;

    $start_time = date('Y-m-d\TH:i:s\Z', strtotime('-24 hours'));

    $url = "https://api.twitch.tv/helix/clips?broadcaster_id=$streamer_id&started_at=$start_time&first=100";
    $headers = [
        "Client-ID: $client_id",
        "Authorization: Bearer $access_token"
    ];

    $options = [
        'http' => [
            'header' => $headers,
            'method' => 'GET'
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    return json_decode($response, true);  // Retourne les clips sous forme de tableau
}

// Fonction pour sauvegarder les clips dans la base de données
function saveClipsToDatabase($clips, $pdo, $streamer_name) {
    $stmt = $pdo->prepare("INSERT INTO clips (clip_id, streamer_name, title, url, thumbnail_url) VALUES (?, ?, ?, ?, ?)");

    foreach ($clips['data'] as $clip) {
        // Vérifier si le clip existe déjà pour éviter les doublons
        $check_stmt = $pdo->prepare("SELECT * FROM clips WHERE clip_id = ?");
        $check_stmt->execute([$clip['id']]);
        if ($check_stmt->rowCount() == 0) {
            // Si le clip n'existe pas, l'insérer dans la base de données
            $stmt->execute([
                $clip['id'],
                $streamer_name,
                $clip['title'],
                $clip['url'],
                $clip['thumbnail_url']
            ]);
        }
    }
}

?>
