<?php
session_start();

// Paramètres de votre application Twitch
$client_id = 'ohr7okfm5kgta9ohyxxylo15ryemh3';
$client_secret = 'i6tif7zj1u302x2w7csm3tly2s5ag9';
$redirect_uri = 'http://localhost/callback'; // L'URL définie dans Twitch

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Étape 1 : Échanger le code d'autorisation contre un token d'accès
    $token_url = "https://id.twitch.tv/oauth2/token";
    $post_data = [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => $redirect_uri,
    ];

    $options = [
        'http' => [
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($post_data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($token_url, false, $context);
    $auth_data = json_decode($response, true);

    if (isset($auth_data['access_token'])) {
        $access_token = $auth_data['access_token'];

        // Étape 2 : Utiliser le token pour récupérer les informations utilisateur
        $user_url = "https://api.twitch.tv/helix/users";
        $user_options = [
            'http' => [
                'header' => [
                    "Authorization: Bearer " . $access_token,
                    "Client-ID: " . $client_id
                ]
            ]
        ];

        $user_context = stream_context_create($user_options);
        $user_response = file_get_contents($user_url, false, $user_context);
        $user_data = json_decode($user_response, true);

        if (isset($user_data['data'][0])) {
            // Stocker les informations de l'utilisateur en session
            $_SESSION['twitch_id'] = $user_data['data'][0]['id'];
            $_SESSION['twitch_username'] = $user_data['data'][0]['display_name'];
            $_SESSION['twitch_email'] = $user_data['data'][0]['email'];

            // Rediriger l'utilisateur vers la page d'accueil
            header('Location: index.php');
        } else {
            echo "Erreur lors de la récupération des informations utilisateur.";
        }
    } else {
        echo "Erreur lors de l'authentification.";
    }
} else {
    echo "Code d'autorisation non reçu.";
}
?>
