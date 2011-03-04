<?php

/**
 * landing point for the MVC Platform
 */
 
require_once("ModuleFramework.class.php");


$moduleId = isset($_REQUEST["moduleId"]) ? $_REQUEST["moduleId"] : "";
if($moduleId === "") echo "";

$rendererId = isset($_REQUEST["rendererId"]) ? $_REQUEST["rendererId"] : "";

error_log("moduleService " . $moduleId . ":" . $rendererId);

$mvcP = new ModuleFramework();
echo $mvcP->renderModuleRenderer($moduleId, $rendererId);



?>
