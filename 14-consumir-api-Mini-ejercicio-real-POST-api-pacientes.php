<?php
    // Login Endpoint
    $loginUrl = "https://apirest.pablogaray.com.ar/auth.php";

    // Data Login post
    $dataLogin = [
        "user"=> "hola@pablogaray.com.ar",
        "pass"=> "123456"
    ];
    
    // Inicializamos cURL
    $ch = curl_init($loginUrl);
    
    // Configuramos las opciones
    curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($dataLogin),
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json",
                        "User-Agent: MiApiPacientes"
                    ],
                    CURLOPT_SSL_VERIFYPEER => false
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
    if ($loginCode !== 200 || !isset($loginData->result->token)) {
            echo "Error al autenticar. HTTP: $loginCode. Respuesta: " . $loginResponse;
            exit;
    }

    if (!isset($loginData->result->token)) {
        echo "No se recibió el token.";
        exit;
    }

    // Guardamos el token
    $token = $loginData->result->token;
    echo "Login exitoso. Token: $token" . PHP_EOL . "<br><br>";;


    /*--------------------- POST pacientes ---------------------*/

    //Endpoint
    $urlPost = "https://apirest.pablogaray.com.ar/pacientes.php";

    // Definimos el nuevo usuario
    $newUser = [
          "dni" => "999888777",
          "nombre" => "Juan",
          "apellido" => "Argentino",
          "genero" => "Mas",
          "fechaNacimiento" => "1999-10-05",
          "direccion" => "Calle 13",
          "tel" => "321654987",
          "email" => "juanargentino@gmail.com",
          "token" => "$token" 
    ];

    // Inicializar cURL
    $ch = curl_init($urlPost);

    // Configurar opciones
    // Configuramos las opciones
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($newUser),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "User-Agent: MiAplicacionPHP"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar errores de conexión
    if (curl_errno($ch)) {
        echo "Error de conexión: " . curl_error($ch);
        curl_close($ch);
        exit;
    }

    // Almacenamos la respuesta HTTP
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Cerramos la peticion
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
        if (!isset($data->result->pacienteId)) {
            echo "La respuesta no contiene la clave 'pacienteId'.";
            exit;

        } else {
            // Recorrer y mostrar datos
            echo "<pre>";
            print_r($data);
            echo "</pre>";
            echo PHP_EOL . "<br><br>";
            echo "El paciente con el ID: " . $data->result->pacienteId . " ha sido agregado correctamente.<br>";
            /*foreach ($data as $paciente) {
                echo "El paciente con el ID: " . $paciente->result->pacienteId . "ha sido agregado correctamente." . PHP_EOL . "<br>";
            }*/
        }        

    } elseif ($httpCode === 401) {
        echo "Token inválido o expirado.";
    
    } else {
        echo "Error HTTP $httpCode: " . $response;
    }
