<?php
header("Access-Control-Allow-Origin: *");
require_once './lib/Database.php';
require_once './lib/RequestHandler.php';

$db = new Database();
$rh = new RequestHandler($db, 'test');

echo $rh->response();