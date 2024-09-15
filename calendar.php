<?php
include 'db.php'; // Connexion à la base de données
include 'twitch_auth.php'; // Récupération du token d'accès Twitch

// Récupérer tous les streamers depuis la base de données
$stmt = $pdo->query("SELECT streamer_id, streamer_name FROM streamers");
$streamers = $stmt->fetchAll();

// Récupérer les IDs des streamers pour les requêtes API
$streamer_id = array_map(function($streamer) {
    return $streamer['streamer_id'];
}, $streamers);

// Fonction pour obtenir les événements en direct depuis l'API Twitch
function getLiveStreams($streamer_id, $access_token) {
    $url = "https://api.twitch.tv/helix/streams?user_id=" . implode('&user_id=', $streamer_id);
    $headers = [
        "Authorization: Bearer $access_token",
        "Client-ID: ohr7okfm5kgta9ohyxxylo15ryemh3"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Récupérer les diffusions en direct pour les streamers
$live_streams = getLiveStreams($streamer_id, $access_token);

// Extraire les événements à afficher dans le calendrier
$events = [];
if (isset($live_streams['data'])) {
    foreach ($live_streams['data'] as $stream) {
        $events[] = [
            'title' => $stream['user_name'] . ' est en live',
            'start' => date('Y-m-d\TH:i:s', strtotime($stream['started_at'])),
            'url' => 'https://www.twitch.tv/' . $stream['user_name']
        ];
    }
}

// Récupérer les événements manuels dans la table 'events'
$manual_events = $pdo->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);

// Ajouter les événements manuels à l'array des événements
foreach ($manual_events as $event) {
    $events[] = [
        'title' => $event['title'],
        'start' => $event['start'],
        'end' => $event['end'],
        'url' => $event['url']
    ];
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier des événements Twitch</title>
    <link rel="stylesheet" href="calendar.css"> <!-- Lien vers le fichier CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
</head>
<body>

<h1>Calendrier des événements Twitch</h1>

<div id='calendar'></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'fr', // Spécifie la langue française
            initialView: 'dayGridMonth',
            events: <?php echo json_encode($events); ?>, // Les événements Twitch
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // Empêche l'ouverture automatique du lien
                if (info.event.url) {
                    window.open(info.event.url); // Ouvre le lien dans une nouvelle fenêtre
                }
            }
        });
        calendar.render();
    });
</script>


</body>
</html>
