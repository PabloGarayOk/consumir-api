<?php
    // [...Resto del codigo del Login...]

    // Guardamos el token
    $token = $loginData->result->token;
    echo "Login exitoso. Token: $token" . PHP_EOL . "<br><br>";

    // ------------- 3 PETICIÓN PUT REAL -------------

    $patchUrl = "https://api.ejemplo.com/users/1";

    // Datos a enviar
    $partialData = [
        "email" => "nuevo_email@example.com"
    ];

    // Inicializamos cURL
    $ch = curl_init($patchUrl);

    // Configuramos las opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json; charset=UTF-8",
            "Authorization: Bearer $token",
            "User-Agent: EjercicioPHP"
        ],
        CURLOPT_POSTFIELDS => json_encode($partialData),
    ]);

    // Ejecutamos la petición
    $response = curl_exec($ch);

    // Verificamos errores de conexión
    if (curl_errno($ch)) {
        echo "Error conexión: " . curl_error($ch);
        curl_close($ch);
        exit;
    }
    
    // Obtenemos código HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Cerramos la peticion
    curl_close($ch);

    // ---------- 4 MANEJO DE RESPUESTA PATCH ----------
    
    echo "El Código HTTP es: $httpCode\n" . PHP_EOL . "<br>";

    // Verificamos si la respuesta fue exitosa antes de seguir 
    if ($httpCode === 200) {
        
        // Decodificamos el JSON
        $data = json_decode($response, true);

        // Verificamos que el JSON sea válido
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }

        // Verificamos si el JSON está vacío
        if (empty($data)) {
            echo "El JSON está vacío.";
            exit;
        }
        
    // Mostramos la respuesta formateada
        echo "Recurso actualizado parcialmente:\n" . PHP_EOL . "<br><br>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
        
    } else {
        echo "Error HTTP ($httpCode): " . htmlspecialchars($response);
    }

?>
