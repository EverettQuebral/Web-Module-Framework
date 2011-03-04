<?php
require_once("Curl.class.php");
require_once("Module.class.php");
//require_once("IDataModule.interface.php");

class DataModule extends Module { //interface IDataModule {
    public function setData($data){
    	$this->data = $data;
    }
    
    public function renderDefault(){
        error_log("XXXX " . print_r($this->data, true));
	print_r($result);
        $result = json_encode($this->data);
        $html = <<<HTML
            <div id="{$this->modId}"><pre>{$result}</pre></div>
HTML;
        return $html;
    }
}
?>
