<?php
    // Orden lógico de la estructura y parametros de las opciones (convencional)
    // Convención: → Inicar cURL → Configurar las Opciones de cURL: · Generales · Método · Headers → Ejecución → Cierre.

    $url = "https://api.misitio.com/datos";

    // 1. Iniciar cURL 
    $ch = curl_init($url);

    // 2.Configurar las Opciones de cURL
    
    // 2A. Opciones generales
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // 2B. Tipo de petición
        # Si es GET, normalmente no se agrega nada especial.

        # Si es POST:
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        # Si es PUT:
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

    // 2C. Headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $token"
        ]);

    // 3. Ejecutar
    $response = curl_exec($ch);
    
    // 4. Cierre
    curl_close($ch);

/*-----------------------------------------------------------------------------------------*/

    // Cuando se hace una solicitud HTTPS a un servidor con un certificado SSL válido.

    // Esta es otra forma, sin definir la variable $url, pero queda un poco mas desprolijo.
    $ch = curl_init('https://api.misitio.com/datos');

    // Ejemplo de configuracion de las opciones de cURL usando "curl_setopt_array"
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token,
        ],
        CURLOPT_SSL_VERIFYPEER => true, // Verifica el certificado SSL
    ]);

    $response = curl_exec($ch);