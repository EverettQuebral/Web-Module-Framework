<?php
require_once("../../core/ModuleFramework.class.php");
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


$mvcP = new ModuleFramework('mobile.html', 'mobile.xml');
$mvcP->setLanguage($langs, $_SERVER["DOCUMENT_ROOT"] . "/gallery/lang/");
echo $mvcP->render();
?>