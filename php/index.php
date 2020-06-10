<?php
require_once( "DB.php" );
$db = new DB();

$result = $db::getRows("SELECT * FROM `test` ");

header("Access-Control-Allow-Origin: *");
echo json_encode($result);
