<?php
	// Consumir una api usando cURL (obteniendo un array asociativo usando "true" en el json_decode)

	// 1. URL de la API para hacer POST
	$url = "https://jsonplaceholder.typicode.com/posts";

	// 2. Datos que vamos a enviar (simulan un nuevo post)
	$postData = [
	    "title" => "Mi primer POST con cURL",
	    "body" => "Este es el contenido del post",
	    "userId" => 1
	];

	// 3. Inicializamos cURL
	$ch = curl_init($url);

	// 4. Configuramos opciones
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Que devuelva el resultado en lugar de imprimirlo
	curl_setopt($ch, CURLOPT_POST, true); // Indicamos que es un POST
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]); // Tipo de contenido
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData)); // Datos a enviar


	// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas con certificados SSL

	// 5. Ejecutamos la petición
	$response = curl_exec($ch);

	// 6. Cerramos la conexión
	curl_close($ch);

	// 7. Decodificamos la respuesta JSON
	$data = json_decode($response, true);

	// 8. Mostramos lo que devolvió la API
	echo "<pre>";
	print_r($data);
	echo "</pre>";

?>
