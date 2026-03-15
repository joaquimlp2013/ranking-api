<?php

// Carrega o autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Define o cabeçalho para JSON
header('Content-Type: application/json; charset=utf-8');

// Carrega as rotas da API
$routes = require_once __DIR__ . '/../routers/api.php';

// Obtém o método HTTP e a URI da requisição
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try{
    // Remove o prefixo da API, se necessário
    if (!isset($routes[$method][$uri])) {
        http_response_code(404);
        echo json_encode([
            'error' => true,
            'message' => 'Route not found'
        ]);
        exit;
    }

    // Chama o controlador e a ação correspondente
    [$controllerClass, $action] = $routes[$method][$uri];

    // Verifica se a classe do controlador existe
    $controller = new $controllerClass();
    $response = $controller->$action();

    // Retorna a resposta como JSON
    echo json_encode($response);

} catch (Exception $e) {
    // Em caso de erro, retorna uma resposta de erro genérica
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
} 