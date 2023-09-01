<?php

// Habilitar CORS para permitir peticiones desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Especificar los métodos HTTP permitidos para la solicitud
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Especificar los encabezados que se pueden incluir en la solicitud
header("Access-Control-Allow-Headers: Content-Type");

// Firebase database endpoint
$firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

// Initialize cURL session
$ch = curl_init();

// Set cURL options to fetch data
curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute cURL session to fetch data
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Decode the JSON response into an associative array
    $data = json_decode($response, true);

    // Return the data as JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Close cURL session
curl_close($ch);

?>