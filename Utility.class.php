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
 * This is be the renderer for the photo
 * 
 * Take note that the structure should be able to accept theme
 * @author: Everett Quebral
 * 
 * 
 */

class Utility {
	/**
	 * create a link 
	 * 
	 * @param {string} $modId
	 * @param {string} $url
	 * @param {string} $text
	 * @param {string} $class
	 */
	public static function createLink($modId, $url = array(), $text, $class=""){
		$rendererId = $url[$modId]["rendererId"];
		$url = "?" . http_build_query($url);
		return "<a id=\"{$modId}-{$rendererId}\" {$class} href=\"{$url}\">{$text}</a>";
	}
	
	/**
	 * encode a string to base 64
	 * @param {string} $string
	 */
	public static function encrypt($string){
		$time = microtime();
		return base64_encode($string . "|" . $time);
	}
	
	/**
	 * decode a base 64 string
	 * @param {string} $encryptedString
	 */
	public static function decrypt($encryptedString){
		$string = base64_decode($encryptedString);
		$string = explode('|', $string);
		return $string[0];
	}
	
	/**
	 * a pretty error log
	 * @param {string} $string
	 */
	public static function logError($string){
		error_log(print_r($string, true));
	}
	
	/**
	 * just do a nice print out of the object
	 * @param {object} $object
	 */
	public static function printNice($object){
		echo "<pre>" . print_r($object) . "</pre>";
	}
	
	/**
	 * parse the attributes of an xml element to become an array
	 * @param {xml->attributes} $attributes
	 */
	public static function getAttributes($attributes){
		$retVal = array();
		foreach($attributes as $k=>$v){
			$retVal[$k] = (string)$v;
		}
		
		return $retVal;
	}
	
	/**
	 * TODO:: needs to re-write this
	 * 
	 * @param unknown_type $available_languages
	 * @param unknown_type $http_accept_language
	 */
	public static function getPreferedLanguage($available_languages, $http_accept_language="auto"){
		// if $http_accept_language was left out, read it from the HTTP-Header
	    if ($http_accept_language == "auto") $http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	
	    // standard  for HTTP_ACCEPT_LANGUAGE is defined under
	    // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
	    // pattern to find is therefore something like this:
	    //    1#( language-range [ ";" "q" "=" qvalue ] )
	    // where:
	    //    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
	    //    qvalue         = ( "0" [ "." 0*3DIGIT ] )
	    //            | ( "1" [ "." 0*3("0") ] )
	    preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
	                   "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
	                   $http_accept_language, $hits, PREG_SET_ORDER);
	                   
	    //error_log("**** hits   " . print_r($hits, true));
	
	    // default language (in case of no hits) is the first in the array
	    $bestlang = $available_languages[0];
	    $bestqval = 0;
	
	    foreach ($hits as $arr) {
	        // read data from the array of this hit
	        $langprefix = strtolower ($arr[1]);
	        if (!empty($arr[3])) {
	            $langrange = strtolower ($arr[3]);
	            $language = $langprefix . "-" . $langrange;
	        }
	        else $language = $langprefix;
	        $qvalue = 1.0;
	        if (!empty($arr[5])) $qvalue = floatval($arr[5]);
	     
	        // find q-maximal language 
	        if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
	            $bestlang = $language;
	            $bestqval = $qvalue;
	        }
	        // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
	        else if (in_array($languageprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) {
	            $bestlang = $languageprefix;
	            $bestqval = $qvalue*0.9;
	        }
	    }
	    return $bestlang;
	}
}