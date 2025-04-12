<?php
function dockerRequest($method, $endpoint, $data = null) {
    $url = "http://localhost:2375$endpoint";
    $ch = curl_init();

    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
    ];

    if ($data !== null) {
        $payload = json_encode($data);
        $options[CURLOPT_POSTFIELDS] = $payload;
        $options[CURLOPT_HTTPHEADER] = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload),
        ];
    }

    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Erro cURL: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 204) return true;

    return json_decode($response, true);
}
