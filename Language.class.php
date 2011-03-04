<?php
require_once("Curl.class.php");

/**
 * The Base Module class
 * 
 * I'd like to implement this as an Abstract Class
 * but at the same time I want to user to just use this class
 * as a Default Module to provide RAPID testing
 */
class Module {
    protected $modId;					// interface
    protected $data;					// interface

    public function __construct($modId){
        $this->modId = $modId;
        error_log("constructing module " . $modId);
    }
    
    /*
    public function setData($data){
    	$this->data = $data;
    }
    */
    
    public function init(){
    }
    
    public function renderDefault(){
        if(!$data){
            return "Module";
        }
        else {
            return "<pre>" . $data . "</pre>";
        }
    }
}
?>
