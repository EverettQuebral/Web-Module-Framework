<?php

/**
 * landing point for the MVC Platform
 */
 
require_once("ModuleFramework.class.php");

$langs = array(
	'en-us',
	'en',
	'fr',
	'fr-fr',
	'de',
	'de-de',
	'es',
	'es-es'
);

$mvcP = new ModuleFramework('index.html');
//echo "<pre>" . print_r($_REQUEST) . "</pre>";

error_log("**** this is the path " . $_SERVER["DOCUMENT_ROOT"] . "/gallery/lang/");
$mvcP->setLanguage($langs, $_SERVER["DOCUMENT_ROOT"] . "/gallery/lang/");
//echo $mvcP->renderPage();
echo $mvcP->render();
//$mvcP->renderPage($_REQUEST);

// if $_REQUEST["x"] the use it, if not use the moduleId
//if(isset($_REQUEST["x"])){
//	echo "whatever";
//	echo $mvcP->renderPage();
//}
//else {
//	if(isset($_REQUEST["rendererId"])){
//		echo "testing";
//		echo $mvcP->renderPage("", $_REQUEST["moduleId"], $_REQUEST["rendererId"]);
//	}
//	else {
//		echo "this is it";
//		echo $mvcP->renderPage();
//	}
//}

?>
