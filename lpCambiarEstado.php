<?php

function cambiarEstado($id) {
    $firebaseEndpoint = "https://lparcial2-default-rtdb.firebaseio.com/Denuncias.json";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);

    $denunciaFound = false;

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $existingData = json_decode($response, true);
        $a = readline('Escoja a que estado quiere actualizar la denuncia(solucionado,desestimado,enTrabajo): ');
        if ($existingData) {
            foreach ($existingData as $key => &$entry) {
                if ($entry["id"] == $id) {
                    if($a == "desestimado"){
                        $entry["estado"]="desestimado";
                    } else if($a == "solucionado"){
                        $entry["estado"]="solucionado";
                    } else if ($a == "enTrabajo"){
                        $entry["estado"]="enTrabajo";
                    }
                    $denunciaFound = true;
                    break; 
                }
            }

            $updatedDataJson = json_encode($existingData);

            curl_setopt($ch, CURLOPT_URL, $firebaseEndpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $updatedDataJson);

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

    curl_close($ch);
}
cambiarEstado(1);
?>