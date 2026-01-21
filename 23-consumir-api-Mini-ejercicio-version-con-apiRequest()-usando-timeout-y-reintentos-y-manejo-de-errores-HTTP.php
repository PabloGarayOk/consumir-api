<?php
    // apiRequest() con manejo de errores HTTP

    function apiRequest(
                        string $url,
                        string $method = 'GET',
                        ?array $body = null,
                        ?string $token = null,
                        int $maxRetries = 3,
                        int $retryDelay = 2,
                        int $timeout = 5
                    )
    {
        $method = strtoupper($method);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            echo "Intento $attempt: $method $url\n";

            $ch = curl_init($url);

            $headers = [
                "User-Agent: MiAplicacionPHP",
                "Content-Type: application/json; charset=UTF-8"
            ];

            if ($token) {
                $headers[] = "Authorization: Bearer $token";
            }

            $options = [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_HTTPHEADER => $headers
            ];

            // Método y cuerpo
            switch ($method) {
                case 'POST':
                    $options[CURLOPT_POST] = true;
                    $options[CURLOPT_POSTFIELDS] = json_encode($body ?? []);
                    break;

                case 'PUT':
                case 'PATCH':
                case 'DELETE':
                    $options[CURLOPT_CUSTOMREQUEST] = $method;
                    if (!empty($body)) {
                        $options[CURLOPT_POSTFIELDS] = json_encode($body);
                    }
                    break;
            }

            curl_setopt_array($ch, $options);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            // Error de conexión (sin respuesta HTTP)
            if ($curlError) {
                echo "Error de conexión: $curlError\n";
            }

            // Respuesta válida (200 o 201)
            if (in_array($httpCode, [200, 201])) {
                echo "Éxito en intento $attempt (HTTP $httpCode)\n";
                $decoded = json_decode($response, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : $response;
            }

            // Errores que NO conviene reintentar
            if (in_array($httpCode, [400, 401, 403, 404])) {
                echo "Error HTTP ($httpCode): No se reintentará.\n";
                echo "Respuesta: " . htmlspecialchars($response) . "\n";
                break;
            }

            // Error 429: demasiadas peticiones
            if ($httpCode === 429) {
                echo "Demasiadas peticiones. Esperando 10 segundos antes de reintentar...\n";
                sleep(10);
                continue;
            }

            // Error del servidor (5xx)
            if ($httpCode >= 500 && $httpCode < 600) {
                echo "Error del servidor (HTTP $httpCode). Reintentando en {$retryDelay}s...\n";
                sleep($retryDelay);
                continue;
            }

            // Otro error genérico
            echo "Error HTTP ($httpCode): " . htmlspecialchars($response) . "\n";

            // Esperar antes del próximo intento (si queda alguno)
            if ($attempt < $maxRetries) {
                echo "Reintentando en {$retryDelay}s...\n";
                sleep($retryDelay);
            }
        }

        // Si llegamos acá, no se logró obtener respuesta válida
        die("No se pudo completar la solicitud después de $maxRetries intentos.\n");
    }


// Ejemplo de uso

    $loginUrl = "https://api.ejemplo.com/login";
    $credentials = [
        "email" => "pablo@example.com",
        "password" => "123456"
    ];

    $loginData = apiRequest($loginUrl, 'POST', $credentials);

    if (!isset($loginData['token'])) {
        die("Login fallido: no se recibió token\n");
    }

    $token = $loginData['token'];

    $postUrl = "https://api.ejemplo.com/posts";
    $newPost = [
        "title" => "Nuevo registro",
        "body" => "Ejemplo de API con reintentos y manejo de errores HTTP.",
        "userId" => 123
    ];

    $response = apiRequest($postUrl, 'POST', $newPost, $token);
    print_r($response);
