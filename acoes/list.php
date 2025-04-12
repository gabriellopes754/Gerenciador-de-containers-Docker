<?php

require_once __DIR__ . '/../docker.php';

header('Content-Type: application/json');

$containers = dockerRequest('GET', '/containers/json?all=true');

if (!is_array($containers)) {
    http_response_code(500);
    echo json_encode(['erro' => 'Não foi possível se conectar à API do Docker.']);
    exit;
}

$resultado = [
    'ativos' => [],
    'parados' => [],
];

foreach ($containers as $container) {
    $info = [
        'id' => substr($container['Id'], 0, 12),
        'nome' => $container['Names'][0],
        'imagem' => $container['Image'],
        'status' => $container['Status'],
    ];

    if ($container['State'] === 'running') {
        $resultado['ativos'][] = $info;
    } else {
        $resultado['parados'][] = $info;
    }
}

echo json_encode($resultado, JSON_PRETTY_PRINT);
