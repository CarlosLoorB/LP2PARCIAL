<?php

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

    // Print the ranking
    echo "<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        padding: 8px;
        text-align: center;
    }
  </style>";

    echo "<table>
        <tr>
            <th>Rank</th>
            <th>Fecha</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Votos</th>
        </tr>";

    $rank = 1;

    foreach ($topRanking as $entry) {
        echo "<tr>
                <td>{$rank}</td>
                <td>{$entry['fecha']}</td>
                <td>{$entry['nombre']}</td>
                <td>{$entry['estado']}</td>
                <td>{$entry['voto']}</td>
            </tr>";
        $rank++;
    }

    echo "</table>";

}

?>