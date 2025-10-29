<?php    

    /*--------------------- Get pacientes ---------------------*/

    //Endpoint
    $urlGet = "https://apirest.pablogaray.com.ar/pacientes.php";

    // Inicializar cURL
    $ch = curl_init($urlGet);

    // Configurar opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, // Retornar la respuesta como string
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "User-Agent: MiAplicacionPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => false, // Verifica certificado SSL
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
 /*       }

        // Validar que el campo esperado exista
        if (!isset($data->Paciente_Id)) {
            echo "La respuesta no contiene la clave 'Paciente_Id'.";
            exit;
        */
        } else {
            // Recorrer y mostrar datos

            // Mostramos el json crudo de la respuesta de la api
            /*
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            */
            foreach ($data as $paciente) {
                echo "ID: " . $paciente->Paciente_Id . PHP_EOL . "<br>";
                echo "Nombre: " . $paciente->Nombre . PHP_EOL . "<br>";
                echo "Apellido: " . $paciente->Apellido . PHP_EOL . "<br>";
                echo "Mail: " . $paciente->Email . PHP_EOL. "<br><br>";
            }
        }        

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    
    } else {
        echo "Error HTTP $httpCode: " . $response;
    }
