<?php
require_once("../../core/Module.class.php");

class HeaderModule extends Module {
    public function __construct($modId){
        parent::__construct($modId);
    }

    public function setStrings($stings){
    	$this->strings = $stings;
    }
    
    public function renderDefault(){
    	$link = "?" . http_build_query($this->getUrl(array("rendererId"=>"view-1")));
        /*
		return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                <a href="{$link}">
                	<h2>{$this->strings["title"]}</h2>
                	<h3>{$this->strings["subTitle"]}</h3>
                </a>
            </div>
HTML;
*/
		return <<<HTML
    		<div id="{$this->modId}" class="{$this->modId} mod-content">
		        <a href="{$link}">
		        	<h2>Everett Quebral: Gallery</h2>
		        	<h3>Gallery</h3>
		        </a>
		    </div>
HTML;
    }

	public function renderMobile(){
		return <<<HTML
			<div data-role="header">
				<h1>Everett Quebral</h1>
			</div>
HTML;
	}
}
?>
