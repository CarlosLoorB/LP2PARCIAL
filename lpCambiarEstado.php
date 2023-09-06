<?php

function cambiarEstado($id,$estado) {
    // Firebase database endpoint
    $firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

    // Initialize cURL session
    $ch = curl_init();

    $estadoNuevo= $estado;

    // Fetch existing data
    curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    $denunciaFound = false;

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $existingData = json_decode($response, true);
        if ($existingData) {
            foreach ($existingData as $key => &$entry) {
                if ($entry["id"] == $id) {
                    if($estadoNuevo == "desestimado"){
                        $entry["estado"]="desestimado";
                    } else if($estadoNuevo == "solucionado"){
                        $entry["estado"]="solucionado";
                    } else if ($estadoNuevo == "enTrabajo"){
                        $entry["estado"]="enTrabajo";
                    }else if ($estadoNuevo == "activo"){
                        $entry["estado"]="activo";
                    }
                    $denunciaFound = true;
                    break; 
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
                    echo "state successfully changed for ID: " . $id;
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
    cambiarEstado($id,$estadoNuevo);
} else {
    echo "Missing or invalid ID in the request data.";
}
?>