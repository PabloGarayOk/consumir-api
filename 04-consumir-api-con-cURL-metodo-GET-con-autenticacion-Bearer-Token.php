<?php
    // Ejemplo de como se usaría:
        
        /*
            // URL de la API
            $url = "https://api.ejemplo.com/users";

            // Token de autorización (normalmente lo guardás en una variable segura)
            $token = "abc123xyz";

            // Inicializamos cURL
            $ch = curl_init($url);

            // Configuramos opciones
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ]);

            // Ejecutamos la petición GET
            $response = curl_exec($ch);

            // Cerramos conexión
            curl_close($ch);

            // Decodificamos la respuesta JSON
            $data = json_decode($response, true);

            // Mostramos resultados
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        */

    // Ejemplo real:

        $url = "https://api.github.com/users/PabloGarayOk/repos"; 

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: MiAplicacionPHP" // User-Agent se usa cuando la API lo exige para identificar al cliente, como en GitHub o en algunos servicios de terceros. Si no es obligatorio, se puede omitir, caso contrario previene de un error "403 Forbidden" o "400 Bad Request" como respuesta del servidor.
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        echo "<pre>";
        print_r(array_slice($data, 0, 2)); // mostramos 2 repos
        echo "</pre>";
