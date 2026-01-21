<?php
	// Versión completa con apiRequest() incorporando timeout + reintentos
	
	/*
		Qué hace esta versión

		- Manejo de errores y reconexión automática:

		Si falla la red o la API devuelve un error no 200/201, reintenta hasta 3 veces.

		Espera 2 segundos entre intentos.

		- Timeouts controlados:

		Evita que cURL quede colgado más de 5 segundos por intento.

		Controla tanto conexión (CURLOPT_CONNECTTIMEOUT) como respuesta (CURLOPT_TIMEOUT).

		- Token opcional:

		Si pasás $token, lo agrega automáticamente al header como Authorization: Bearer.

		- JSON seguro:

		Intenta decodificar la respuesta como JSON.

		Si no puede, te devuelve el cuerpo plano (por ejemplo, HTML de error o texto).

		- Código limpio:

		Login y POST se reducen a dos simples líneas, sin duplicar nada:

		$loginData = apiRequest('https://api.ejemplo.com/login', 'POST', $credentials);
		$postData  = apiRequest('https://api.ejemplo.com/posts', 'POST', $data, $token);

	*/

	/**
	 * Realiza una petición HTTP robusta (con cURL, timeouts, reintentos y manejo de JSON).
	 *
	 * @param string $url            Endpoint al que se hace la solicitud.
	 * @param string $method         Método HTTP: GET, POST, PUT, DELETE, PATCH.
	 * @param array|null $body       Datos a enviar (array asociativo o null).
	 * @param string|null $token     Token Bearer opcional.
	 * @param int $maxRetries        Cantidad de reintentos permitidos.
	 * @param int $retryDelay        Segundos de espera entre intentos.
	 * @param int $timeout           Timeout por intento (segundos).
	 *
	 * @return array|string          Devuelve array decodificado o el cuerpo raw si no es JSON.
	 */
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

	        // Configuración base
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

	        // Ejecutamos
	        $response = curl_exec($ch);
	        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	        $curlError = curl_error($ch);

	        // Verificación de error de red
	        if (curl_errno($ch)) {
	            echo "Error de conexión: $curlError\n";
	        }

	        curl_close($ch);

	        // Si no hubo error de conexión y el código es válido
	        if (!curl_errno($ch) && in_array($httpCode, [200, 201])) {
	            echo "Éxito en intento $attempt (HTTP $httpCode)\n";
	            $decoded = json_decode($response, true);
	            if (json_last_error() === JSON_ERROR_NONE) {
	                return $decoded;
	            } else {
	                return $response; // No es JSON válido, devolvemos texto plano
	            }
	        }

	        echo "Falló intento $attempt (HTTP $httpCode). ";
	        if ($attempt < $maxRetries) {
	            echo "Reintentando en {$retryDelay}s...\n";
	            sleep($retryDelay);
	        }
	    }

	    // Si todos los intentos fallaron
	    die("No se pudo completar la solicitud después de $maxRetries intentos.\n");
	}


	// -------------------- 1. LOGIN --------------------
	
	$loginUrl = "https://api.ejemplo.com/login";
	$credentials = [
	    "email" => "pablo@example.com",
	    "password" => "123456"
	];

	$loginResponse = apiRequest($loginUrl, 'POST', $credentials);

	// Validar token
	if (!isset($loginResponse['token'])) {
	    die("No se recibió token en la respuesta del login.\n");
	}

	$token = $loginResponse['token'];
	echo "Login correcto. Token: $token\n\n";


	// -------------------- 2. CREAR POST --------------------
	
	$postUrl = "https://api.ejemplo.com/posts";
	$postData = [
	    "title" => "Nuevo usuario",
	    "body"  => "Este es un ejemplo de registro desde PHP con token.",
	    "userId" => 123
	];

	$response = apiRequest($postUrl, 'POST', $postData, $token);

	echo "POST realizado con éxito:\n";
	print_r($response);

?>
