<?php
/**
 * The MIT License
 * 
 * Copyright (c) 2011 Everett Quebral Everett.Quebral@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * 
 * ModuleFramework
 * this class will handle all the modules
 * 
 * @author: Everett Quebral
 * 
 * 
 */

require_once("Connection.class.php");
require_once("Utility.class.php");
 
class ModuleFramework {
    
	/**
	 * the xml file that describes the pages and modules
	 * @var {object} xml
	 */
    private $moduleDefinition;
    
    /**
     * the html file that will be used as a template
     * @var {string} filename
     */
    private $templateFile;
    
    /**
     * holder for the modules that will be parsed
     * @var {array} object
     */
    private $modules = array();             // array of the modules being run
    
    /**
     * holder for the default renderers
     * @var {array} object
     */
    private $defaultRenderers = array();    // array to contain the markup to be substituted with the template/html if it contains <Module id="XX">
    
    /**
     * css content that will be placed on the page
     * @var {string}
     */
    private $cssIncludes = "";              // string to include the css
    
    /**
     * js content that will be placed on the page
     * @var {string}
     */
    private $jsIncludes = "";               // string to include the js
    
    /**
     * container of the translated strings
     * @var {array}
     */
    private $strings = array();
    
    /**
     * languages that will be supported
     * @var {array}
     */
    private $langs = array();
    
    /**
     * the path where to get the language and strings
     * @var {string}
     */
    private $languagePath = "";
    
    /**
     * collection of object as array to hold values that can be called globally
     * $context["module1"] = array("request", "something");
     * $context["module2"] = array("example", "testing");
     * 
     * @var {array}
     */
    private $context = array();				// array of objects that will be passed when parsing the modules definition xml file							
    
    /**
     * this will hold the information of the url for each module
     * so that state can be preserved
     * 
     * $url = array("<moduleId>" => array("rendererId"=>"{rendererValue",..,));
     * $url = array("module1"=>array("rendererId"=>"default", "page"=>2));
     * @var {array}
     */
    private $url = array();					// this is implemented in the Module.class.php
    
    /**
     * the data sources 
     * 
     * @var {array}
     */
    private $sources = array();

    /**
     * The connection class
     * Enter description here ...
     * @var {Connection}
     */
    private $connection;
    
    private $classes = array();
    
    
    
    /**
     * constructor
     * 
     * @param {string} $templateFile
     * 	the filename of the templatefile to be used
     * 
     * @param {string} $moduleFile
     * 	the filename of the module definition (xml) that will be used
     */
    public function __construct($templateFile='', $moduleFile=''){
        if($moduleFile === ''){
            $moduleFile = 'modules.xml';
        }
        if($templateFile === ''){
            $this->templateFile = 'index.html';
           
        }
        else {
            $this->templateFile = $templateFile;
        }
        
        $this->moduleDefinition = simplexml_load_file($moduleFile);
        $this->connection = new Connection();
    }
    
    /**
     * set the language that will be supported and set the 
     * path where to get the language folder
     * 
     * @param {array} $langs
     * 	key=>value pair of the language that will be supported
     * 
     * @param {string} $path
     * 	path to get to the language folder
     */
    public function setLanguage($langs=array(), $path){
    	$this->langs = $langs;
    	$this->languagePath = $path;
    }
    
    /**
     * function that will resolve the sources and returns the data
     * 
     * @param {string} $moduleId
     * 	the id of the module that is currently processed
     * 
     * @param {xml} $source
     *  the xml definition to get the sources
     *  
     * @param {array} $context
     *  array of objects that will hold the modules context
     */
    private function processSource($moduleId, $source, $context=null){
    	/*
    	$endPoint = (string) $source->EndPoint;
    	$args = array();
    	foreach($source->Arguments->Argument as $argument){
    		// parse the value if it is a variable then set the variable as the value
    		$value = (string)$argument->Value;
    		if(substr($value, 0, 2)  === '{$'){
    			$args[(string)$argument->Name] = $context[substr($value, 2, -1)];
    		}
    		else {
    			$args[(string)$argument->Name] = (string)$argument->Value;
    		}
    	}
    	$result = Connection::getResult($endPoint, $args);
    	return json_decode($result, true);
    	*/
    	return json_decode($this->connection->getResult($moduleId), true);
    }
    
    /**
     * function that will process the data and pass the information to the module class handler
     * 
     * @param {object} $object
     * 	the class object for the module
     * 
     * @param {string} $className
     *  the class name that will be used to resolve the filename of the class
     *  
     * @param {xml} $source
     *  the source xml definition for the module
     *  
     * @param {string} $moduleId
     *  the id of the module
     */
    private function processData($object, $className, $source, $moduleId){
     	if(method_exists($className, 'setData')){
     		// check the method again although it's implemented in the Module.class
     		if(method_exists($className, 'getContext')){
				$object->setData($this->processSource($moduleId, $source, $object->getContext()));
     		}
     		else {
     			$object->setData($this->processSource($moduleId, $source));
     		}
     	}
    	return $object;
    }
    
    /**
     * function that will process the string and pass the information to the module class handler
     * 
     * @param {object} $object
     *  the class object for the module
     *  
     * @param {string} $className
     *  the class name that will be used to resolve the filename of the class
     *  
     * @param {string} $contentName
     *  the name of the content that will be used to resolve the content filename
     */
    private function processStrings($object, $className, $contentName){
    	if(method_exists($className, 'setStrings')){
    		//should be be able to get the resource strings if available
    		//need to parse /resource/en-US/content.php
    		
    		$lang = Utility::getPreferedLanguage($this->langs);
    		$contentFile = "{$this->languagePath}{$lang}/{$contentName}.txt";
    		
    		if(!file_exists($contentFile)){
    			$contentFile = "{$this->languagePath}en-us/{$contentName}.txt";
    		}
    		
    		$content = file_get_contents($contentFile);
			$strings = json_decode($content, true);
			$object->setStrings($strings);
    	}
    	return $object;
    }
    

    
    /**
     * needs to deprecate this function as it is buggy
     * unexpected behavior is it is making a duplicate call 
     * to a module that is requested
     * 
     * @deprecated
     */
    private function parseModules(){
        foreach($this->moduleDefinition->Module as $module){
            // get the module ID
            foreach($module->attributes() as $k=>$v){
                if($k === "id") {
                    $moduleId = (string)$v;
                }
            }
            
            // get the classname
            $className = $module->Class;
            
            // include the module class
            //Utility::logError($className);
            if(file_exists($className . ".class.php")){
                include_once($className . ".class.php");
                
                // create an instance of the class
                $myClass = (string)$className;
                $x = new $myClass($moduleId);
               	$x->init();
                
                // get the default renderer
                // if there's only one renderer then assume that is the default renderer
                $defaultRenderer = "";
                $rendererId = "";
                $isDefaultSet = false;
                foreach($module->Renderers->Renderer as $renderer){
                    //Utility::logError("renderer " . $renderer);
                    if((bool)$renderer->attributes()->default){
                        //Utility::logError("Default renderer found");
                        $defaultRenderer = (string)$renderer;
                        $rendererId = (string)$renderer->attributes()->id;
                        $isDefaultSet = true;
                    }
                    
                    // when no default renderer is set, use the last definition of renderer to be the default
                    if(!$isDefaultSet){
                        //Utility::logError("No default renderer using last definition");
                        $defaultRenderer = (string)$renderer;
                        $rendererId = (string)$renderer->attributes()->id;
                    }
                }
                
                // to process the renderers, the sources should be processed first
                $source = $module->Sources->Source;
                //$this->modules[$class]->setData($this->processSource($source));
                $x = $this->processData($x, $myClass, $source);
                
                //get the content for the module
                $x = $this->processStrings($x, $myClass, $this->getContent($moduleId));
                
                $this->modules[$myClass] = $x;
                
                
                // get the markup from the default renderer
                //Utility::logError("adding module " . $moduleId . " to renderers");
                $this->defaultRenderers[$moduleId] = $this->modules[$myClass]->{$defaultRenderer}();
                
                // get the includes
                if(isset($module->Includes->Include)){
                    foreach($module->Includes->Include as $include){
                        //Utility::logError("including css and js");
                        if((string)$include->attributes()->type === "css"){
                            $cssFile = (string)$include;
                            $this->cssIncludes .= "<link href=\"{$cssFile}\" rel=\"stylesheet\" type=\"text/css\">";
                        }
                        if((string)$include->attributes()->type === "js"){
                            $jsFile = (string)$include;
                            $this->jsIncludes .= "<script type=\"text/javascript\" src=\"{$jsFile}\"></script>";
                        }
                    }
                }   
            }
            else {
               // Utility::logError("Error including class " . $className . ".class.php");
            }
        }
    }
    
    private function getContent($moduleId){
    	$contentFile = explode("-", $moduleId);
    	return $contentFile[1];
    }
    
    /**
     * parse all modules except when the $exceptModuleId is set
     * 
     * this function is not being used
     * @deprecated
     * 
     * @param {string} $exceptModuleId
     * 	the id of the module that will not be parsed
     */
    private function parseAllModules($exceptModuleId=""){
    	foreach($this->moduleDefinition->Module as $module){
    		$moduleAttributes = Utility::getAttributes($module->attributes());
    		$moduleId = $moduleAttributes["id"];
    		
    		if($moduleId !== $exceptModuleId){
				$this->parseModule($moduleId);
			}
			
			$this->parseIncludes($module);
    	}
    }
    
    /**
     * parse all the includes associated with a module
     * 
     * @param {xmlObject} $module
     *  the xmlObject pertaining to a module definition
     */
    private function parseIncludes($module){
    	if(isset($module->Includes->Include)){
			foreach($module->Includes->Include as $include){
				if((string)$include->attributes()->type === "css"){
					$cssFile = (string)$include;
					$this->cssIncludes .= "<link href=\"{$cssFile}\" rel=\"stylesheet\" type=\"text/css\">";
				}
				if((string)$include->attributes()->type === "js"){
					$jsFile = (string)$include;
					$this->jsIncludes .= "<script type=\"text/javascript\" src=\"{$jsFile}\"></script>";
				}
			}
		}    	
    }
    
    /**
     * parse the module by finding the module definition in the xml file
     * 
     * @param {string} $moduleId
     *  the module id that will be parsed
     *  
     * @param {string} $rendererId
     *  the renderer id of the module that will be parsed
     */
    private function parseModule($moduleId, $rendererId=''){
    	$module = $this->moduleDefinition->xpath("//Page/Module[@id='".$moduleId ."']");
    	
    	$className = (string)$module[0]->Class;
    	Utility::logError("Loading ClassName " . $className);
    	
    	$this->context[$moduleId] = array("rendererId"=>$rendererId);
    	Utility::logError("Initializing the context for module " . $moduleId);
    	
    	if(file_exists($className . ".class.php")){
    		include_once($className . ".class.php");
    		
    		$x = new $className($moduleId);
    		$x->init();
    		
    		// set the context to the module
    		$x->setContext($this->context[$moduleId]);

    		//$source = $module[0]->Sources;
    		// get the right source for the module and renderer
			if($rendererId !== ""){
				$moduleSource = $module[0]->xpath("//Sources/Source[@bindTo='" . $rendererId . "']");
				$moduleSource = $moduleSource[0];
			}
			else {
				$moduleSource = $module[0]->Sources->Source;
			}
    		    		
    		//$source = $module[0]->Sources->Source;
            //$x = $this->processData($x, $className, $source);
            $x = $this->processData($x, $className, $moduleSource, $moduleId);
    		
            //process the strings for the module
            $x = $this->processStrings($x, $className, $this->getContent($moduleId));
            
            $xpath = "//Page/Module[@id='{$moduleId}']/Renderers/Renderer";
//            $xpath .= $rendererId !== "" ? "[@id='".$rendererId."']" : "[@default='true']";
    		if($rendererId !== ''){
    			//$renderer = $this->moduleDefinition->xpath("//Page/Module[@id='".$moduleId ."']/Renderers/Renderer[@id='".$rendererId."']");
    			$renderer = $this->moduleDefinition->xpath($xpath . "[@id='{$rendererId}']");
    		}
    		else {
    			//$renderer = $this->moduleDefinition->xpath("//Page/Module[@id='".$moduleId ."']/Renderers/Renderer[@default='true']");
    			$renderer = $this->moduleDefinition->xpath($xpath . "[@default='true']");
    		}
    		
    		if(isset($renderer[0])){
    			$rendererMethod = (string)$renderer[0];
    		}
    		else {
    			return;
    		}
    		
    		//Utility::logError("using Renderer Method " . $rendererMethod);  
    		// save the state of the renderers		
    		$this->defaultRenderers[$moduleId] = $x->{$rendererMethod}();
    		
    		// now set the title
    		if($x->getTitle() !== ""){
    			$this->title .= $x->getTitle();
    		}
    	}
    	else {
    		return;
    	}
    }
    
    /**
     * replaces the modules in the template
     * 
     * @param {string} $template
     *  the template as a string
     */
    private function replaceModules($template){
        foreach($this->defaultRenderers as $k => $v){
            $template = str_replace('{' . $k . '}', $v, $template);
        }
     
        return $template;
    }
    
    /**
     * add the js settings to the jsModule variable
     */
    private function addJsSettings(){
        $html = '<script type="text/javascript">var jsModule = {};</script>';

        return $html;
    }
    
    private function addJsPlatform(){
        $html = '<script type="text/javascript" src="http://yui.yahooapis.com/combo?3.1.1/build/yui/yui-debug.js&3.1.1/build/dump/dump-debug.js"></script>';
        $html .= '<script type="text/javascript" src="js/module-framework-api.js"></script>';
        $html .= '<script type="text/javascript" src="js/module-framework-manager.js"></script>';
        
        return $html;
        
    }
    
    private function addIncludes($template){
        $template = str_replace('{includeCSS}', $this->cssIncludes, $template);
        $template = str_replace('{includeJS}', $this->addJsSettings() . $this->jsIncludes . $this->addJsPlatform(), $template);
        return $template;    
    }
    
    private function parseSources($moduleId='', $rendererId=''){
    // parse the sources
    	foreach($this->moduleDefinition->Module as $module){
    		$moduleAttributes = Utility::getAttributes($module->attributes());
    		$moduleId = $moduleAttributes["id"];
    		
    		$module = $this->moduleDefinition->xpath("//Page/Module[@id='".$moduleId ."']");
    		
    		if($rendererId !== ""){
				$moduleSource = $module[0]->xpath("//Sources/Source[@bindTo='" . $rendererId . "']");
				$moduleSource = $moduleSource[0];
			}
			else {
				$moduleSource = $module[0]->Sources->Source;
			}
			
			$endPoint = (string) $moduleSource->EndPoint;
			$args = array();
			foreach($moduleSource->Arguments->Argument as $argument){
				$value = (string)$argument->Value;
				if(substr($value, 0, 2) === '{$'){
					$args[(string)$argument->Name] = $context[substr($value, 2, -1)];
				}
				else {
					$args[(string)$argument->Name] = (string)$argument->Value;
				}
			}
			
			$this->connection->addCurl($moduleId, $endPoint, $args);
    	}
    }
    
    private function parseRenderers($moduleId='', $rendererId=''){
    	
    }
    
    /**
     * Render the Page
     *  - parse the modules so that all data source request can be processed using multi curl
     *  - parse the renderers
     *  
     * @param unknown_type $encodedX
     * @param unknown_type $moduleId
     * @param unknown_type $rendererId
     */
    public function renderPage($encodedX='', $moduleId='', $rendererId=''){
    	$this->parseSources($moduleId, $rendereId);
    	$this->connection->execute();
    	
    	// parse the renderers
    	foreach($this->moduleDefinition->Module as $module){
			$moduleAttributes = Utility::getAttributes($module->attributes());
    		$moduleId = $moduleAttributes["id"];
		    	
    		if(isset($_REQUEST[$moduleId])){
    			$this->parseModule($moduleId, $_REQUEST[$moduleId]["rendererId"]);
    		}
    		else {
    			$this->parseModule($moduleId);
    		}
    			
    		$this->parseIncludes($module);
	    }
		
    	// get the template and replace the strings
        $template = file_get_contents($this->templateFile);
        
        $template = $this->replaceModules($template);
        $template = $this->addIncludes($template);
        $template = str_replace('{title}', $this->title, $template);
        return $template;
    }
    
    /**
     * TODO:: this function seems to be duplicating a task
     * 
     * @deprecated
     * 
     * Enter description here ...
     * @param unknown_type $moduleId
     * @param unknown_type $rendererId
     */
    public function renderModuleRenderer($moduleId, $rendererId){
    	$this->parseModule($moduleId, $rendererId);
    	
        $html = $this->defaultRenderers[$moduleId];
        //Utility::logError("Renderer " . $moduleId);
        //Utility::logError($html);
        return $html;
    }
    
    /**
     * read the xml file and make it as an array
     * 
     */
    private function prepareModules(){
    	foreach($this->moduleDefinition->Module as $module){
    		$modAttr = Utility::getAttributes($module->attributes());
    		$modId = $modAttr["id"];
    		
    		echo "Parsing module " . $modId . "<br />";
    		
    		$this->modules[$modId] = array();
    		$this->modules[$modId]["className"] = (string) $module[0]->Class;
    		
    		//get the renderer for the module
			$rendererId = isset($_REQUEST[$modId]) ? $_REQUEST[$modId]["rendererId"] : "";
    		
    		if($rendererId !== ""){
    			//get the context and set to the data
    			$moduleSource = $module[0]->xpath("//Sources/Source[@bindTo='" . $rendererId . "']");
    			$moduleSource = $moduleSource[0];
    		}
    		else {
    			$moduleSource = $module[0]->Sources->Source;
    		}

    		$this->modules[$modId]["source"] = array();
    		$this->modules[$modId]["source"]["endPoint"] = (string) $moduleSource->EndPoint;
    		$args = array();
    		foreach($moduleSource->Arguments->Argument as $argument){
   				$args[(string)$argument->Name] = (string)$argument->Value;
    		}
    		$this->modules[$modId]["source"]["args"] = $args;
    		
    		if($rendererId !== ""){
    			$renderer = $module[0]->xpath("//Renderers/Renderer[@id='" . $rendererId . "']");
    		}
    		else {
    			$renderer = $module[0]->xpath("//Renderers/Renderer[@default='true']");	
    		}
    		
    		$this->modules[$modId]["renderer"] = (string) $renderer[0];
    	}
    }
    
    private function prepareSources(){
    	echo "<pre>";
    	print_r($this->modules);
    	echo "</pre>";
    	
    	foreach($this->modules as $moduleId=>$moduleValue){
    		$classFileName = $moduleValue["className"] . ".class.php";
    		echo $moduleId . "=>" . $moduleValue . "=>" . $classFileName . "<br/>";
    		
    		echo "<pre>";
	    	print_r($moduleValue);
	    	echo "</pre>";
	    	
    		if(file_exists($classFileName)){	
	    		include_once($classFileName);
	    		
	    		$className = $moduleValue["className"];
	    		$x = new $className($moduleId);
	    		$x->init();
	    		
	    		$x->setContext($this->context[$moduleId]);
	    		if(method_exists($className, 'setData')){
	    			$args = array();
	    			if(method_exists($className, 'getContext')){
	    				foreach($moduleValue["source"]["args"] as $argsKey=>$argsValue){
	    					if(substr($argsValue, 0, 1) === '{$'){
	    						$args[$argsKey] = $x->getContext(substr($argsValue, 2, -1));
	    					}
	    				}
	    			}
	    			$this->connection->addCurl($moduleId, $moduleValue["source"]["endPoint"], $args);
	    		}
	    		
	    		echo "Adding to class " . $moduleId . "<br />";
	    		$this->classes[$moduleId] = $x;
    		}
    	}
    }
    
    private function prepareRenderer(){
    	echo "<pre>";
    	print_r($this->classes);
    	echo "</pre>";
    	
    	foreach($this->classes as $key=>$class){
    	 	print_r($key);
    		//echo $class->{$this->modules[$moduleId]->renderer}();
    		echo $class->renderDefault();
    	 	//$this->modules[$moduleId]["renderer"] = $class->{$this->modules[$moduleId]->renderer}();
    	}
    }
    
    
    /**
     * render the whole page
     * 
     * 1. initialize all the modules
     * 2. set all the context
     * 3. set all the sources -> all sources should execute all the curl request before calling render
     * 4. render the modules
     * 5. render the page
     */
    public function render(){
    	$this->prepareModules();
    	$this->prepareSources();
    	$this->prepareRenderer();
    }
}

?>
