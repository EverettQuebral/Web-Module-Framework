<?php
require_once("../wmf/Module.class.php");

class HeaderModule extends Module {
    public function __construct($modId){
        parent::__construct($modId);
    }

    public function setStrings($stings){
    	$this->strings = $stings;
    }
    
    public function renderDefault(){
    	$link = "?" . http_build_query($this->getUrl(array("rendererId"=>"view-1")));
        return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                <a href="{$link}">
                	<h2>{$this->strings["title"]}</h2>
                	<h3>{$this->strings["subTitle"]}</h3>
                </a>
            </div>
HTML;
    }
}
?>
