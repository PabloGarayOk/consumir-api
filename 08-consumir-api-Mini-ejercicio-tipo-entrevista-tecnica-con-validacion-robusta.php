<?php

    // Endpoint
    $url = "https://api.ejemplo.com/v1/users";

    // Token
    $token = "abc123xyz789";

    // Inicializar cURL
    $ch = curl_init($url);

    // Configurar opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, // Retornar la respuesta como string
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $token", // Header con el token
            "User-Agent: MiAplicacionPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => true, // Verifica certificado SSL
    ]);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar errores de conexión
    if (curl_errno($ch)) {
        echo "Error de conexión: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Obtener el código HTTP de la respuesta
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Manejar el código HTTP COMO OBJETOS
    if ($httpCode === 200) {

        $data = json_decode($response);

        // Verificar si hay error en la decodificación JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }

        // Verificar si el JSON está vacío o sin datos útiles
        if (empty($data)) {
            echo "La API respondió, pero el contenido está vacío.";
            exit;
        }

        // Validar que el campo esperado exista
        if (!isset($data->users)) {
            echo "La respuesta no contiene la clave 'users'.";
            exit;
        
        } else {
            // Recorrer y mostrar datos
            foreach ($data->users as $user) {
                echo "Usuario: " . $user->name . PHP_EOL;
            }
        }        

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    
    } else {
        echo "Error HTTP $httpCode: " . $response;
    }

/*---------------------------------------------------------------*/

    // Manejar el código HTTP COMO ARRAY
    if ($httpCode === 200) {

        $data = json_decode($response, true);

        // Verificar si hay error en la decodificación JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }

        // Verificar si el JSON está vacío o sin datos útiles
        if (empty($data)) {
            echo "La API respondió, pero el contenido está vacío.";
            exit;
        }

        // Validar que el campo esperado exista
        if (!isset($data['users'])) {
            echo "La respuesta no contiene la clave 'users'.";
            exit;
        
        } else {
            // Recorrer y mostrar datos
            foreach ($data['users'] as $user) {
                echo "Usuario: " . $user['name'] . PHP_EOL;
            }
        }        

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    
    } else {
        echo "Error HTTP $httpCode: " . $response;
    }