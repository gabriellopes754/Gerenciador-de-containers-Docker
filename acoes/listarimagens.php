<?php

require_once __DIR__ . '/../docker.php';

header('Content-Type: application/json');

$imagens = dockerRequest('GET', '/images/json');

if (!is_array($imagens)) {
    http_response_code(500);
    echo json_encode(['erro' => 'Não foi possível se conectar à API do Docker.']);
    exit;
}

$resultado = [];

foreach ($imagens as $imagem) {
    $info = [
        'id' => substr($imagem['Id'], 0, 12),
        'nome' => $imagem['RepoTags'][0] ?? 'sem tag',
        'tamanho' => round($imagem['Size'] / 1024 / 1024, 2) . ' MB', // Convertendo tamanho para MB
    ];

    $resultado[] = $info;
}

echo json_encode($resultado, JSON_PRETTY_PRINT);
