<?php
/**
 * initial implementation only
 * proof of concept 
 */
class Curl {
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
}
?>
