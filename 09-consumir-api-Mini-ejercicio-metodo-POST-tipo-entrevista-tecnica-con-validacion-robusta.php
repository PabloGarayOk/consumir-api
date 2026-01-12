<?php

    // Endpoint
    $url = "https://api.ejemplo.com/users";

    // Token
    $token = "12345abcde";

    // Definimos el nuevo usuario
    $newUser = [
        "name"  => "Pablo Garay",
        "email" => "pablo@example.com",
    ];

    // Inicializamos cURL
    $ch = curl_init($url);

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

    // Ejecuamos la peticion POST
    $response = curl_exec($ch);

    // Verificamos la conexion
    if (curl_errno($ch)) {
        echo "Error en la conexión: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Almacenamos la respuesta HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Cerramos la peticion
    curl_close($ch);

    // Manejamos los códigos y mostramos la respuesta
    
    // if ( $httpCode === 201) {...      <<<--- Opcional 1, una forma "mas simple" pero menos abarcativo.
    // if (in_array($httpCode, [200, 201, 202])) {...      <<<--- Opcional 2, una forma "mas precisa" pero todavía no completamente abarcativa.

    if ($httpCode >= 200 && $httpCode < 300) {

        // Mostramos la respuesta en crudo
        echo "Respuesta cruda: " . htmlspecialchars($response);

        // Decodificamos el json
        $data = json_decode($response, true);

        // Verificamos el json ok
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "La API respondió bien, pero el JSON está defectuoso: " . json_last_error_msg();
            exit;
        }

        // Verificamos si el json no esta vacio
        if (empty($data)) {
            echo "El JSON está vacío.";
            exit;
        }

        // Verificamos y mostramos los datos
        if (!isset($data['id'])) {
            echo "La API respondió, pero no devolvió el campo 'id'.";
        } else {

            // Forma clasica de ver un array
            echo "<pre>";
            print_r($data);
            echo "</pre>";

            // Opcional, para verlo como JSON “bonito”:
            // echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    } else {
        echo "Error HTTP de tipo: $httpCode " . htmlspecialchars($response);
    }


    /*-------------- Si NO usamos true en el json_decode-------------*/

    // Decodificamos el json
            $data = json_decode($response);
            
            // Verificamos el json ok
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo "La api respondio bien, pero el json esta defectuoso: " . json_last_error_msg();
                exit;
            }
            
            // Verificamos si el json no esta vacio
            if (empty($data)) {
                echo "El json esta vacio.";
                exit;
            }
            
            // Extraemos y mostramos los datos
            if (!isset($data->id)) {
                echo "La api no almaceno los datos, la respuesta no tiene el campo 'id'.";
            
            }else {

                // Forma clasica de ver un objeto
                echo "<pre>";
                var_dump($data); 
                echo "</pre>";

                // Opcional, para verlo como JSON “bonito”:
                // echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }

            // ...resto del codigo