<?php
$client_id = 'ohr7okfm5kgta9ohyxxylo15ryemh3';
$client_secret = 'i6tif7zj1u302x2w7csm3tly2s5ag9';

// URL pour obtenir le token d'accès
$token_url = "https://id.twitch.tv/oauth2/token?client_id=$client_id&client_secret=$client_secret&grant_type=client_credentials";

// Effectuer une requête POST pour obtenir le token
$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

// Décoder la réponse
$auth_data = json_decode($response, true);

// Si la réponse est vide ou contient une erreur, vérifiez pourquoi
if (!$response || json_last_error() !== JSON_ERROR_NONE) {
    echo "Erreur de réponse ou JSON invalide.";
} else {
    $auth_data = json_decode($response, true);
    if (isset($auth_data['access_token'])) {
        // Stocker le token d'accès
        $access_token = $auth_data['access_token'];
    } else {
        echo "Erreur lors de la récupération du token d'accès : ";
        if (isset($auth_data['message'])) {
            echo $auth_data['message'];
        } else {
            echo "Réponse vide ou invalide de l'API.";
        }
    }
}
