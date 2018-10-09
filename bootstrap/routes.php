<?php

use Slim\Http\Request;
use Slim\Http\Response;


$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', function (Request $request, Response $response) {
    echo 'home';
});

$app->post('/upload', function (Request $request, Response $response) {
    $params = $request->getParams();
    $folder = __DIR__ . '/../storage';
    if ($_FILES) {
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $folder . "/" . $params['name'])) {
            return $response->withJson("ok", 200);
        } else {
            return $response->withJson("error", 400);
        }
    } else {
        return $response->withJson('no file', 400);
    }
});