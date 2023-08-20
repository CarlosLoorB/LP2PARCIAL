<?php

function incrementarVoto($id) {
    // Firebase database endpoint
    $firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

    // Initialize cURL session
    $ch = curl_init();

    // Fetch existing data
    curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    $denunciaFound = false;

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $existingData = json_decode($response, true);

        // Find the denuncia with the specified ID and increment its voto attribute
        if ($existingData) {
            foreach ($existingData as $key => &$entry) {
                if ($entry["id"] == $id) {
                    $entry["voto"] += 1;
                    $denunciaFound = true;
                    break; // Exit the loop once the denuncia is found and updated
                }
            }

            // Convert updated data to JSON format
            $updatedDataJson = json_encode($existingData);

            // Set cURL options for updating data
            curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $updatedDataJson);

            // Execute cURL session to update data
            $updateResponse = curl_exec($ch);

            if (curl_errno($ch)) {
                echo "cURL Error: " . curl_error($ch);
            } else {
                if($denunciaFound) {
                    echo "Vote incremented successfully for ID: " . $id;
                } else {
                    echo "Denuncia with ID " . $id . " not found.";
                }
                
            }
        } else {
            echo "Data not found";
        }
    }

    // Close cURL session
    curl_close($ch);
}

$jsonData = file_get_contents('php://input');
$requestData = json_decode($jsonData, true);

if (isset($requestData["id"])) {
    $id = $requestData["id"];
    // Call the function to increment the vote for the specified denuncia ID
    incrementarVoto($id);
} else {
    echo "Missing or invalid ID in the request data.";
}

?>
