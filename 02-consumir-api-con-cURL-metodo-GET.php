<?php
	// Consumir una api usando cURL (obteniendo un array asociativo usando "true" en el json_decode)

	// 1. URL de la API
	$url = "https://jsonplaceholder.typicode.com/posts";

	// 2. Inicializamos cURL
	$ch = curl_init($url);

	// 3. Configuramos las opciones
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Que devuelva el resultado en lugar de imprimirlo
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Evita problemas con certificados SSL

	// 4. Ejecutamos la petición
	$response = curl_exec($ch);

	// 5. Cerramos la conexión
	curl_close($ch);

	// 6. Decodificamos la respuesta JSON
	$data = json_decode($response, true); //Convierte un string en JSON a un objeto (sin el parametro "true")o un array asociativo (con el parametro "true") en PHP .

	// 7. Mostramos los primeros 3 resultados
	foreach (array_slice($data, 0, 3) as $post) {
	    echo "ID: " . $post['id'] . "<br>";
	    echo "Título: " . $post['title'] . "<br>";
	    echo "Contenido: " . $post['body'] . "<hr>";
	}
?>
