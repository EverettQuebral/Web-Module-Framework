<?php
require_once("Curl.class.php");

/**
 * The Base Module class
 * 
 * I'd like to implement this as an Abstract Class
 * but at the same time I want the user to just use this class
 * as a Default Module to provide RAPID testing
 */
class Module {
	/** the $modId is taken from the module definition .xml file **/
    protected $modId;					// interface
    
    /** the container of $data from the result on the sources query **/
    protected $data;					// interface
    
    /** the container of the localized $strings **/
    protected $strings = array();		// interface
    
    /** the container of the context variables for the module **/
    protected $context = array();		// module context
    
    protected $title = "";

    /**
     * set the id of the module
     * 
     * @param string $modId
     */
    public function __construct($modId){
        $this->modId = $modId;
        Utility::logError("**********************************");
        Utility::logError("Initializing Module " . $modId);
        Utility::logError("**********************************");
    }
    
    /*
    public function setData($data){
    	$this->data = $data;
    }
    */
    
    /**
     * interface
     * this function will be called by the framework when a module is initialized
     * to pass all the moduleContext
     * 
     * @param unknown_type $context
     */
    public function setContext($context){
   		$this->context = $context;
	}
	
	/**
	 * interface
	 * this function will be called by the framework everytime source and data will be processed
	 * 
	 * @param unknown_type $context
	 */
	public function getContext(){
		return $this->context;
	}
    
    /** 
     * an iterface calld by the Module Framework
     */
    public function init(){
    }
    
    /**
     * interface that will be called by the rendering definition
     */
    public function renderDefault(){
        if(!$data){
            return "Module";
        }
        else {
            return "<pre>" . $data . "</pre>";
        }
    }
    
	/**
	 * process the url from the $_REQUEST object and
	 * give the module a way to override itself
	 * this will preserve the state of the application when 
	 * a new module and renderer is requested that will
	 * require a page reload
	 * 
	 * TODO:: the code from the ModuleFramework about the url should be removed
	 * 
	 * @param {array} $override
	 */
    protected function getUrl($override=null){
    	// convert the $_REQUEST to array
    	//return HttpQueryString::toArray();
    	
    	$temp = array($this->modId => $override);
    	//Utility::printNice($temp);
    	$url = array_merge($_REQUEST, $temp);
    	//Utility::printNice($url);
    	return $url;
    }
    
    public function getTitle(){
    	return $this->title;
    }
}
?>
