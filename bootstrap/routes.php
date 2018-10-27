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

$app->group('/images', function () {
    $this->get('[/]', function ($request, $response) {
        $images = [];
        $folder = __DIR__ . '/../public/db/images';
        if ($handle = opendir($folder)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $images[] = "http://image-upload.test/db/images/" . $entry;
                }
            }
            closedir($handle);
        }
        return $response->withJson(['result' => $images], 200);
    });

    $this->post('/destroy', function ($request, $response) {
        $params = $request->getParams();
        if (!isset($params['file'])) {
            return $response->withJson(["result" => "no file"], 400);
        }
        $folder = __DIR__ . '/../public/db/images';
        if (is_file($folder . '/' . $params['file'])) {
            if (unlink($folder . '/' . $params['file'])) {
                return $response->withJson(["result" => "ok"], 200);
            }
            return $response->withJson(["result" => "can't delete"], 400);
        }
        return $response->withJson(["result" => "no exists"], 400);
    });
    
    $this->post('/upload', function ($request, $response) {
        $folder = __DIR__ . '/../public/db/images';
        if ($_FILES) {
            $filename = sha1($_FILES['file']['name']) . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $folder . "/" . $filename)) {
                return $response->withJson(["result" => "http://image-upload.test/db/images/" . $filename], 200);
            } else {
                return $response->withJson(["result" => "error"], 400);
            }
        } else {
            return $response->withJson(["result" => 'no file'], 400);
        }
    });
});