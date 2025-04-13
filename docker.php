<?php
function dockerRequest($method, $endpoint, $data = null) {
    $url = "http://localhost:2375$endpoint";
    $guardar = curl_init();

    $config = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
    ];

    if ($data !== null) {
        $dados = json_encode($data);
        $config[CURLOPT_POSTFIELDS] = $dados;
        $config[CURLOPT_HTTPHEADER] = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($dados),
        ];
    }

    curl_setopt_array($guardar, $config);
    $response = curl_exec($guardar);

    if (curl_errno($guardar)) {
        echo 'Erro cURL: ' . curl_error($guardar);
        curl_close($guardar);
        return null;
    }

    $http = curl_getinfo($guardar, CURLINFO_HTTP_CODE);
    curl_close($guardar);

    if ($http === 204) return true;

    return json_decode($response, true);
}
