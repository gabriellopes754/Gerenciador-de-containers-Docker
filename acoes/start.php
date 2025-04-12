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
$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'POST',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['erro' => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

if ($httpCode === 204) {
    echo json_encode(['mensagem' => "Container $id iniciado com sucesso."]);
} else {
    echo json_encode([
        'erro' => "Erro ao iniciar container",
        'codigo_http' => $httpCode,
        'resposta' => $response
    ]);
}
