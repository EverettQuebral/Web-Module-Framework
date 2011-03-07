<?php
require_once("Connection.class.php");


//header("Content-Type: application/json");
$_REQUEST["args"] = isset($_REQUEST["args"]) ? $_REQUEST["args"] : "";
error_log("Args " . print_r($_REQUEST, true));

$result = Connection::getResult($_REQUEST["endPoint"], $_REQUEST);
error_log($result);
echo $result;
?>
