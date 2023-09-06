<?php
header("Access-Control-Allow-Origin: *");

// Especificar los métodos HTTP permitidos para la solicitud
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Especificar los encabezados que se pueden incluir en la solicitud
header("Access-Control-Allow-Headers: Content-Type");

// Firebase database endpoint
$firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

// Initialize cURL session
$ch = curl_init();

// Fetch existing data
curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    $existingData = json_decode($response, true);

    // Sort data by 'voto' in descending order
    usort($existingData, function($a, $b) {
        return $b['voto'] - $a['voto'];
    });

    // Close cURL session
    curl_close($ch);

    // Take the top 10 entries
    $topRanking = array_slice($existingData, 0, 10);

    // Return the top ranking data as JSON
    header('Content-Type: application/json');
    echo json_encode($topRanking); 

}

?>