<?php
/*
    Ejercicio:

    Conéctate a una API con Bearer Token, obtené una lista de usuarios y mostralos en una tabla HTML.

    Vamos a simular que la API responde con algo así (JSON ficticio):

    [
      {"id":1, "name":"Alice", "email":"alice@example.com"},
      {"id":2, "name":"Bob", "email":"bob@example.com"},
      {"id":3, "name":"Charlie", "email":"charlie@example.com"}
    ]
*/

    // Resolucion:

    // URL de la API 
    $url = "https://api.ejemplo.com/users";

    // Token ficticio
    $token = "abc123xyz";

    // Inicializamos cURL
    $ch = curl_init($url);

    // Configuramos opciones
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token",
        "Content-Type: application/json",
        "User-Agent: MiAplicacionPHP" 
    ]);

    // Ejecutamos la petición
    $response = curl_exec($ch);

    // Cerramos cURL
    curl_close($ch);

    // Decodificamos JSON
    $data = json_decode($response, true);

    // Mostramos en tabla HTML
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th></tr>";

    foreach ($data as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['name']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "</tr>";
    }

    echo "</table>";

?>