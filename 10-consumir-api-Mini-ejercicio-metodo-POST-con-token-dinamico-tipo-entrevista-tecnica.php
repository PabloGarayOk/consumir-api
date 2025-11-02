<?php

    // ---------- PRIMERA PETICIÓN: LOGIN ---------- //

    // Endpoint de autenticación
    $loginUrl = "https://api.ejemplo.com/login";

    // Credenciales del usuario
    $credentials = [
        "email" => "pablo@example.com",
        "password" => "123456"
    ];

    // Inicializamos cURL para login
    $ch = curl_init($loginUrl);

    // Configuramos las opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($credentials),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "User-Agent: MiAplicacionPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    // Ejecutamos la petición de login
    $loginResponse = curl_exec($ch);

    // Verificamos errores de conexión
    if (curl_errno($ch)) {
        echo "Error en la conexión (login): " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Obtenemos el código HTTP
    $loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decodificamos la respuesta del login
    $loginData = json_decode($loginResponse);

    // Validamos el login
    if ($loginCode !== 200 || !isset($loginData->token)) {
        echo "Error al autenticar. HTTP: $loginCode. Respuesta: " . $loginResponse;
        exit;
    }

    // Guardamos el token
    $token = $loginData->token;


    // ---------- SEGUNDA PETICIÓN: CREAR USUARIO ---------- //

    // Endpoint de para crear un usuario
    $createUrl = "https://api.ejemplo.com/users";

    // Datos del usuario
    $newUser = [
        "name" => "Pablo Garay",
        "email" => "pablo@example.com"
    ];

    // Inicializamos cURL
    $ch = curl_init($createUrl);

    // Configuramos las opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($newUser),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $token",
            "User-Agent: MiAplicacionPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    // Ejecutamos la petición de creación
    $response = curl_exec($ch);

    // Verificamos errores de conexión
    if (curl_errno($ch)) {
        echo "Error en la conexión (POST): " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Obtenemos el código HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Manejo de respuesta
    if (in_array($httpCode, [200, 201])) {
        $data = json_decode($response);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Error al decodificar JSON: " . json_last_error_msg();
            exit;
        }
        
        echo "<pre>";
        print_r($data);
        echo "</pre>";

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";

    } else {
        echo "Error HTTP ($httpCode): " . htmlspecialchars($response);
    }

?>
