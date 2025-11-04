<?php

    //--------------- LOGICA MODELO ---------------//
    /*
    $url = "https://jsonplaceholder.typicode.com/posts";
    $method = "GET";
    $maxRetries = 3; // Número máximo de reintentos
    $retryDelay = 2; // Segundos de espera entre intentos
    $timeout = 5;    // Timeout en segundos

    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        echo "Intento $attempt...\n";

        $options = [
            "http" => [
                "method"  => $method,
                "timeout" => $timeout,
            ]
        ];

        $context = stream_context_create($options);

        $response = @file_get_contents($url, false, $context);

        if ($response !== false) {
            echo "Petición exitosa en el intento $attempt\n";
            break; // Salimos del bucle
        } else {
            echo "Falló intento $attempt (esperando $retryDelay s)\n";
            sleep($retryDelay);
        }
    }

    if ($response === false) {
        echo "Error: no se pudo completar la petición después de $maxRetries intentos.\n";
    } else {
        $data = json_decode($response, true);
        print_r($data);
    }
    */
    //--------------- FIN LOGICA MODELO ---------------//
    


    //------------------ EJERCICIO REAL -------------------//
    
    // --- CONFIGURACIÓN GENERAL DE TIMEOUT Y REINTENTOS ---
    
    $maxRetries = 3;  // Número máximo de reintentos
    $retryDelay = 2;  // Segundos de espera entre intentos
    $timeout = 5;     // Timeout por intento (segundos)


    // ------------------- 1 LOGIN -------------------
    
    $loginUrl = "https://api.ejemplo.com/login";

    $credentials = [
        "email" => "pablo@example.com",
        "password" => "123456"
    ];

    // Intentos de login
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        echo "Intento de login $attempt...\n";

        $ch = curl_init($loginUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($credentials),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "User-Agent: MiAplicacionPHP"
            ],
            CURLOPT_TIMEOUT => $timeout,         // Timeout de conexión + respuesta
            CURLOPT_CONNECTTIMEOUT => $timeout,  // Timeout de conexión
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $loginResponse = curl_exec($ch);
        $loginCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if (!curl_errno($ch) && in_array($loginCode, [200, 201])) {
            echo "Login exitoso en intento $attempt\n";
            break;
        } else {
            echo "Falló intento $attempt. Error: $curlError (HTTP $loginCode)\n";
            if ($attempt < $maxRetries) {
                echo "Reintentando en $retryDelay segundos...\n";
                sleep($retryDelay);
            }
        }

        curl_close($ch);
    }

    // Si no hubo respuesta válida
    if (empty($loginResponse) || !in_array($loginCode, [200, 201])) {
        die("No se pudo autenticar después de $maxRetries intentos.\n");
    }

    // Procesar respuesta de login
    $loginData = json_decode($loginResponse, true);

    if (!isset($loginData['token'])) {
        die("No se recibió token válido.\n");
    }

    $token = $loginData['token'];
    echo "Login OK. Token: $token\n";


    // ------------------- 2 PETICIÓN POST -------------------
    
    $postUrl = "https://api.ejemplo.com/posts";

    $postData = [
        "title" => "Nuevo usuario",
        "body"  => "Este es un ejemplo de registro desde PHP cURL con token.",
        "userId" => 123
    ];

    // Intentos de envío POST
    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        echo "Intento POST $attempt...\n";

        $ch = curl_init($postUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json; charset=UTF-8",
                "Authorization: Bearer $token",
                "User-Agent: EjercicioPHP"
            ],
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        if (!curl_errno($ch) && in_array($httpCode, [200, 201])) {
            echo "POST exitoso en intento $attempt\n";
            break;
        } else {
            echo "Falló intento $attempt. Error: $curlError (HTTP $httpCode)\n";
            if ($attempt < $maxRetries) {
                echo "Reintentando en $retryDelay segundos...\n";
                sleep($retryDelay);
            }
        }

        curl_close($ch);
    }

    // Si no se logró después de todos los intentos
    if (empty($response) || !in_array($httpCode, [200, 201])) {
        die("No se pudo completar la petición POST después de $maxRetries intentos.\n");
    }

    // ------------------- 3 RESPUESTA POST -------------------
    
    echo "Código HTTP final: $httpCode\n";

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("Error al decodificar JSON: " . json_last_error_msg());
    }

    echo "Respuesta:\n";
    print_r($data);

?>
