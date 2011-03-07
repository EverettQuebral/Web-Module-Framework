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
 * @author: Everett Quebral
 * 
 * TODO:: needs to support MultiCurl
 * 
 */

require_once("Utility.class.php");

class Connection {
	
	/** handler for the curl requests **/
	protected $curlHandler = array();
	
	/** results **/
	protected $results = array();
	
	/** the curl multi handler **/
	protected $mhHandler;
	
	public function __construct(){
		$this->mhHandler = curl_multi_init();
		error_log("Initialized curl");
	}
	
	public function addCurl($id, $endPoint,  $args){
		Utility::logError("ID " . $id . " : EndPoint = " . $endPoint . " : Args " . print_r($args, true));
		$this->curlHandler[$id] = curl_init($endPoint);
		curl_setopt($this->curlHandler[$id], CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($this->curlHandler[$id], CURLOPT_TIMEOUT, 60);
	    curl_setopt($this->curlHandler[$id], CURLOPT_HEADER, false);
	    curl_setopt($this->curlHandler[$id], CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($this->curlHandler[$id], CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
	    curl_setopt($this->curlHandler[$id], CURLOPT_POST, true);
	    if($args !== ""){
	        curl_setopt($this->curlHandler[$id], CURLOPT_POSTFIELDS, http_build_query($args));
	    }
	    curl_multi_add_handle($this->mhHandler, $this->curlHandler[$id]);
	}
	
	public function execute(){
		$running = null; 
		
        do{ 
                curl_multi_exec($this->mhHandler,$running); 
        } while($running > 0); 
		
		foreach($this->curlHandler as $chKey=>$chHandler){
			error_log(print_r($chHandler, true));
			$this->results[$chKey] = curl_multi_getcontent($chHandler);
			curl_multi_remove_handle($this->mhHandler, $chHandler);
		}
		curl_multi_close($this->mhHandler);
	}
	
	public function getResult($id){
		return $this->results[$id];
	}
	/*
    public static function getResult($endPoint, $args){
        error_log("Getting result from "  . $endPoint);
        error_log("With args " . print_r($args, true));
        $ch = curl_init($endPoint);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
	    curl_setopt($ch, CURLOPT_POST, true);
	    if($args !== ""){
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args));
	    }
	    try {
		    $retVal = curl_exec($ch);
		    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		    //error_log("Result code : " . $code);
		    //error_log("Return value : " . $retVal);
		    curl_close($ch);
		    if($code !== 200){
			    return false;
		    }
		    else {
			    //return json_decode($retVal, true);
                return $retVal;
		    }
	    }
	    catch(Exception $e){
		    return false;
	    }
    }
    */
}
?>
