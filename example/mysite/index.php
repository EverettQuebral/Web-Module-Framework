<?php

/**
 * landing point for the MVC Platform
 */
 
require_once("../../core/ModuleFramework.class.php");


$mvcP = new ModuleFramework('index.html');
echo $mvcP->renderPage($_REQUEST["moduleId"], $_REQUEST["rendererId"]);



?>
