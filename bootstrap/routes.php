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

$app->group('/test', function () {
    $this->get('[/]', function ($request, $response) {
        return $response->withJson([
            ['id' => 1, 'file' => 'aca8adfsd3e4c3cd8sadfds5d49c3gfhdf17afhg.jpeg', 'pos' => 1],
            ['id' => 2, 'file' => 'safklhasdre1242344323e628c098437cf17a1as.jpeg', 'pos' => 2],
            ['id' => 3, 'file' => '3cb7a8a3e4c3cd85d49c3e628c098437cf17a1ca.jpeg', 'pos' => 3],
            ['id' => 4, 'file' => '3cb7a8adfas3e4c3cdgjrt85d4098437cf17a135.jpeg', 'pos' => 4],
            ['id' => 5, 'file' => '3cb7a8a3e4c3cd85d49c3e628c3sadg7cf171dsf.jpg', 'pos' => 5],
            ['id' => 6, 'file' => '3cb7a8a3e4c3casdfadsd85d4937csadff17a182.jpg', 'pos' => 6],
            ['id' => 7, 'file' => '3cb7a8a3e4c3cd85d49asdfsadc3e628c0984382.jpg', 'pos' => 7],
            ['id' => 8, 'file' => '3cb7a8a3e4asdfsc3cd85d49c3e628c098437c82.jpg', 'pos' => 8],
            ['id' => 9, 'file' => 'asf3ew4413cb7a8a3e4c3cdasdfsadfwq85d492a.jpg', 'pos' => 9],
            ['id' => 10, 'file' => '3cb7a8a3eas3324c3cd85d49c3e628c098437cfa.jpg', 'pos' => 10],
            ['id' => 11, 'file' => '3cb7a8a3e4c3cddsfa23sdydt85d49c3e628c09x.jpg', 'pos' => 11],
            ['id' => 12, 'file' => 'dfasd3cb7a8a3e4c3cd85d49ahc3e628c098437m.jpg', 'pos' => 12]
        ], 200);
    });
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