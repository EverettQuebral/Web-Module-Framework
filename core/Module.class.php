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
 * The Base Module class
 * 
 * I'd like to implement this as an Abstract Class
 * but at the same time I want the user to just use this class
 * as a Default Module to provide RAPID testing
 * 
 * @author: Everett Quebral
 * 
 */
require_once("Connection.class.php");

class Module {
	/** the $modId is taken from the module definition .xml file **/
    protected $modId;					// interface
    
    /** the container of $data from the result on the sources query **/
    protected $data;					// interface
    
    /** the container of the localized $strings **/
    protected $strings = array();		// interface
    
    /** the container of the context variables for the module **/
    protected $context = array();		// module context
    
    /** the title of the page **/
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
    
  	/**
  	 * interface
  	 * this is the function that will be called by the framework to set the data to the module
  	 * @param {object} $data
  	 */  
//    public function setData($data){
//    	$this->data = $data;
//    }
    
    
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
	public final function getContext($key=""){
		if(!$key){
			return $this->context;
		}
		else {
			return $this->context[$key];
		}
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
		$url = array("modId" => $this->modId);
    	$temp = array($this->modId => $override);
		$temp = array_merge($url, $temp);
		return $temp;
    }
    
    /**
     * interface
     * function that will be called by the framework to get the Title of the page
     */
    public function getTitle(){
    	return $this->title;
    }
}
?>
