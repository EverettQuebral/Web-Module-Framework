<?php

require_once("Module.class.php");
require_once("IDataModule.interface.php");

class WeatherModule extends Module implements IDataModule {
    
    protected $weatherMarkupLarge;
    protected $weatherMarkupSmall;

    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Weather Module " . $modId);
    }
    
    public function init(){
        error_log("initializing Weather Module");
        $this->weatherMarkupSmall = "Nothing so far";
    }
    
    public function setData($data){
    	$this->data = $data;
    	
    	$this->weatherMarkupLarge = $data["query"]["results"]["channel"]["item"]["description"];
    }
    
    private function renderLinks(){
    	$html = <<<HTML
    		<div class="mod-links">
	    		<a id="{$this->modId}-view-2" href="index.php?moduleId={$this->modId}&rendererId=view-2">Large</a>
				<a id="{$this->modId}-view-1" href="index.php?moduleId={$this->modId}&rendererId=view-1">Small</a>
			</div>
HTML;
		return $html;
    }
    
    public function renderSmall(){
    	$links = $this->renderLinks();
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                {$this->weatherMarkupSmall}
                {$links}
            </div>
            
HTML;
        return $html;
    }
    
    public function renderLarge(){
    	$links = $this->renderLinks();
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                {$this->weatherMarkupLarge}
                {$links}
            </div>
            
HTML;
        return $html;
    }
}
?>
