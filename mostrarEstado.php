<?php
function mostrarEstado($id){
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
    $existingData = json_decode($response, true);
    if($existingData){
        foreach($existingData as $key => $entry){
            if($entry["id"] == $id){
                $estado= $entry["estado"];
                echo "La denuncia con id: $id ";
                echo "tiene de estado: $estado";
            }
        }
    }
}

curl_close($ch);
}
mostrarEstado(1);

?>