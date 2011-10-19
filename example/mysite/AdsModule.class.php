<?php
require_once("../../core/Module.class.php");

class AdsModule extends Module {

    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Ads Module " . $modId);
    }
    
    public function init(){
        error_log("initializing Ads Module");
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
                this is the render small renderer of the Weather Module
                {$links}
            </div>
            
HTML;
        return $html;
    }
    
    public function renderLarge(){
    	$links = $this->renderLinks();
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                this is the render large renderer of the Weather Module
                <p>Since this is the large renderer, a couple of content is also included here</p>
                {$links}
            </div>
            
HTML;
        return $html;
    }
}
?>
