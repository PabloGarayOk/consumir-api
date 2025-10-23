<?php

    // ---------- 1 LOGIN SIMULADO ----------

    // Simulamos que la API nos devolvió un token tras un login exitoso
    $token = base64_encode('usuario:clave123'); // solo para ejemplo, no es real JWT

    echo "Login simulado. Token generado: $token" . PHP_EOL . PHP_EOL . "<br><br>";


    // ---------- 2 PETICIÓN POST REAL ----------

    $url = "https://jsonplaceholder.typicode.com/posts";

    // Datos a enviar (simulan un nuevo post o usuario)
    $postData = [
        "title" => "Nuevo usuario",
        "body"  => "Este es un ejemplo de registro desde PHP cURL con token.",
        "userId" => 123
    ];

    // Inicializamos cURL
    $ch = curl_init($url);

    // Configuramos las opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json; charset=UTF-8",
            "Authorization: Bearer $token",
            "User-Agent: EjercicioPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    // Ejecutamos la petición
    $response = curl_exec($ch);

    // Verificamos errores de conexión
    if (curl_errno($ch)) {
        echo "Error en la conexión: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Obtenemos código HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Cerramos conexión
    curl_close($ch);


    // ---------- 3 MANEJO DE RESPUESTA ----------

    echo "El Código HTTP es: $httpCode" . PHP_EOL . "<br>";

    // Solo seguimos si la respuesta fue exitosa
    if (in_array($httpCode, [200, 201])) {

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
        echo "Respuesta decodificada correctamente:" . PHP_EOL . "<br><br>";
        echo "<pre>";
        print_r($data);
        echo "</pre>";

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    } else {
        echo "Error HTTP ($httpCode): " . htmlspecialchars($response);
    }
?>
