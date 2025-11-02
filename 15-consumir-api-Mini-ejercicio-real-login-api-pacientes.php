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
	echo "Login exitoso. Token: $token" . PHP_EOL;
    