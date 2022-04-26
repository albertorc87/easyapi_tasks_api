<?php

require '../bootstrap.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS, PUT, DELETE');
header('Allow: GET, POST, PATCH, OPTIONS, PUT, DELETE');

$method = $_SERVER['REQUEST_METHOD'];
if($method == 'OPTIONS') {
    die();
}

use App\V1\Routes;
Routes::loadRoutes();

$app = new EasyAPI\App();
$app->send();
