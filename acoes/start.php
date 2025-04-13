<?php
require_once __DIR__ . '/../docker.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID do container não informado.']);
    exit;
}

$id = $_GET['id'];

$url = "http://localhost:2375/containers/$id/start";
$configcurl = curl_init($url);

curl_setopt_array($configcurl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
]);

$response = curl_exec($configcurl);
$http = curl_getinfo($configcurl, CURLINFO_HTTP_CODE);

if (curl_errno($configcurl)) {
    echo json_encode(['erro' => curl_error($configcurl)]);
    curl_close($configcurl);
    exit;
}

curl_close($configcurl);

if ($http === 204) {
    echo json_encode(['mensagem' => "Container $id iniciado com sucesso."]);
} else {
    echo json_encode([
        'erro' => "Erro ao iniciar container",
        'codigo_http' => $http,
        'resposta' => $response
    ]);
}
