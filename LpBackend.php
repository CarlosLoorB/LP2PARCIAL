<?php
// Habilitar CORS para permitir peticiones desde cualquier origen
header("Access-Control-Allow-Origin: *");

// Especificar los métodos HTTP permitidos para la solicitud
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

// Especificar los encabezados que se pueden incluir en la solicitud
header("Access-Control-Allow-Headers: Content-Type");

function postDenuncia($denunciaData) {

     if (empty($denunciaData["fecha"]) || empty($denunciaData["nombre"]) || empty($denunciaData["descripcion"]) || empty($denunciaData["ubicacion"])) {
        echo json_encode("400");
        echo json_encode("Los campos no pueden estar vacíos.");
        return;
    }
    // Firebase database endpoint
    $firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

    // Initialize cURL session
    $ch = curl_init();

    // Set cURL options to fetch existing data
    curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Execute cURL session to fetch existing data
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        // Decode the JSON response into an associative array
        $existingData = json_decode($response, true);

        // Find the highest ID among existing entries
        $maxId = 0;
        if ($existingData) {
            foreach ($existingData as $key => $entry) {
                if ($entry["id"] > $maxId) {
                    $maxId = $entry["id"];
                }
            }
        }

        // Increment the highest ID to set as the new ID
        $newId = $maxId + 1;

        // Set the new ID and other fields in the denunciaData
        $denunciaData["id"] = $newId;
        $denunciaData["desestimado"] = 0;
        $denunciaData["enTrabajo"] = 0;
        $denunciaData["solucionado"] = 0;
        $denunciaData["activo"] = 1;
        $denunciaData["estado"] = "Activo";
        $denunciaData["voto"] = 0;

        // Convert data to JSON format
        $denunciaJson = json_encode($denunciaData);

        // Set cURL options for inserting data
        curl_setopt($ch, CURLOPT_URL, "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $denunciaJson);

        // Execute cURL session to insert data
        $insertResponse = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            echo json_encode("200");
        }
    }

    // Close cURL session
    curl_close($ch);
}

// Receive JSON data from POST request body
$jsonData = file_get_contents('php://input');
$denunciaData = json_decode($jsonData, true);

// Call the function to post the Denuncia
postDenuncia($denunciaData);

?>