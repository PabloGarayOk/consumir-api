<?php
	// Consumir una api usando file_get_contents (obteniendo un array asociativo usando "true" en el json_decode)
		
		// URL de la API - API URL
		$url = "https://jsonplaceholder.typicode.com/posts";

		// Hacemos la peticion Get - Make get petittion
		$response = file_get_contents($url);

		// Converimos la respuesta JSON a un array de PHP
		$data = json_decode($response, true);

		// Mostramos los resultados
		foreach (array_slice($data, 0, 3) as $post) {
			echo "ID: " . $post['id'] . "<br>";
			echo "Titulo: " . $post['title'] . "<br>";
			echo "Contenido: " . $post['body'] . "<hr>";
		}

	// Consumir una api usando file_get_contents (obteniendo un array de objetos sin usar "true" en el json_decode)
	// Aclaracion: Se va a obtiener un array de objetos si el JSON empieza con [...] y un objeto si el JSON empieza con {...}
	// En este caso es un array de objetos
	
		// URL de la API - API URL
		$url = "https://jsonplaceholder.typicode.com/posts";

		// Hacemos la peticion Get - Make get petittion
		$response = file_get_contents($url);

		// Converimos la respuesta JSON a un array de PHP
		$data = json_decode($response);

		// Mostramos los resultados
		foreach (array_slice($data, 0, 3) as $obj) {
			echo "ID obj: " . $obj->id . "<br>";
			echo "User ID obj: " . $obj->userId . "<br>";
			echo "Contenido obj" . $obj->body . "<hr>";
		}
?>