<?php
require_once __DIR__ . '/../docker.php';

header('Content-Type: application/json');

$corporequisicao = file_get_contents('php://input');
$data = json_decode($corporequisicao, true);

if (!isset($data['id']) || empty($data['id'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID do container não informado.']);
    exit;
}

$id = $data['id'];
$response = dockerRequest('POST', "/containers/$id/stop");

if ($response === true) {
    echo json_encode(['mensagem' => "Container $id parado com sucesso."]);
} else {
    http_response_code(500);
    echo json_encode([
        'erro' => "Erro ao parar o container $id.",
        'resposta' => $response
    ]);
}
