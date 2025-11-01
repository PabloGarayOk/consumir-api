<?php

// Escenario del ejercicio: 

	// Necesitamos que consultes una API protegida por token.

	# Endpoint: https://api.ejemplo.com/v1/users

	# Método: GET

	# Autenticación: Bearer Token

	# Tu token: abc123xyz789

	// Tu tarea:
	// Hacer la solicitud con cURL.
	// Decodificar la respuesta JSON.
	// Mostrar los nombres de los usuarios en consola.
	// Manejar posibles errores (por ejemplo, fallo de conexión o token inválido).

/*----------------------------------------------------------------------------------*/

// Resolución:

	// Endpoint
	$url = "https://api.ejemplo.com/v1/users";

	// Token
	$token = "abc123xyz789";

	// Inicializar cURL
	$ch = curl_init($url);

	// Configurar opciones
	curl_setopt_array($ch, [
	    CURLOPT_RETURNTRANSFER => true, // Retornar la respuesta como string
	    CURLOPT_HTTPHEADER => [
	        "Content-Type: application/json",
	        "Authorization: Bearer $token", // Header con el token
	        "User-Agent: MiAplicacionPHP"
	    ],
	    CURLOPT_SSL_VERIFYPEER => true, // Verifica certificado SSL
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

	// Manejar el código HTTP
	if ($httpCode === 200) {
	    
	    // Decodificar JSON
	    $data = json_decode($response); // Obtenemos los datos como objeto

	    if (isset($data->users)) {
	        foreach ($data->users as $user) {
	            echo "Usuario: " . $user->name . PHP_EOL;
	        }
	    } else {
	        echo "No se encontraron usuarios.";
	    }
	} elseif ($httpCode === 401) {
	    echo "Token inválido o expirado.";
	} else {
	    echo "Error HTTP $httpCode: " . $response;
	}
?>


<?php

	$data = json_decode($response, true); // Obtenemos los datos como array

    // Mostramos en tabla HTML
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th></tr>";

    foreach ($data as $user) {
        echo "<tr>";
        echo "<td>{$user['id']}</td>";
        echo "<td>{$user['name']}</td>";
        echo "<td>{$user['email']}</td>";
        echo "</tr>";
    }

    echo "</table>";


    // Manejar el código HTTP
	if ($httpCode === 200) {
    // Decodificar JSON como array
    $data = json_decode($response, true);

    if (isset($data['users'])) {
        foreach ($data['users'] as $user) {
            echo "Usuario: " . $user['name'] . PHP_EOL;
        }
    } else {
        echo "No se encontraron usuarios.";
    }
}
