<?php

require '../app/core/router.php';
require '../app/controllers/discosController.php';

$url = $_SERVER['QUERY_STRING'];
// echo 'URL = ' .$url.'<br>';

$router = new Router();

// Definir las rutas
$router->add('discos/get', [
    'controller' => 'DiscosController', 
    'action' => 'getAll'
]);

$router->add('discos/get/{id}', [
    'controller' => 'DiscosController',
    'action' => 'getById'
]);

$router->add('discos/create', [
    'controller' => 'DiscosController',
     'action' => 'create'
]);

$router->add('discos/update/{id}', [
    'controller' => 'DiscosController',
    'action' => 'update'
]);
$router->add('discos/delete/{id}', [
    'controller' => 'DiscosController', 
    'action' => 'delete'
]);

$url = $_SERVER['QUERY_STRING'];
if ($router->match($url)) {
    $controllerName = $router->getParams()['controller'];
    $controller = new $controllerName();
    $action = $router->getParams()['action'];
    $matches = $router->getParams()['matches'] ?? [];

    $method = $_SERVER['REQUEST_METHOD'];
    $controllerParams = [];

    if ($method === 'GET') {
        $controllerParams = $matches;
    } elseif ($method === 'POST') {
        $data = file_get_contents('php://input');
        $controllerParams[] = json_decode($data, true);
    } elseif ($method === 'PUT') {
        $data = file_get_contents('php://input');
        $controllerParams[] = $matches[0] ?? null;
        $controllerParams[] = json_decode($data, true);
    } elseif ($method === 'DELETE') {
        $controllerParams = $matches;
    }

    call_user_func_array([$controller, $action], $controllerParams);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Recurso no encontrado']);
}