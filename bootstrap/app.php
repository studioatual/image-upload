<?php

use Slim\Http\Request;
use Slim\Http\Response;

date_default_timezone_set("America/Sao_Paulo");
setlocale(LC_ALL, 'pt_BR');

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Slim\App(include __DIR__ . '/config.php');

require_once __DIR__ . '/routes.php'; 