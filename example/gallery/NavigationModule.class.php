<?php
require_once("../wmf/Module.class.php");

class NavigationModule extends Module {
    public function __construct($modId){
        $this->modId = $modId;
        parent::__construct($modId);
    }
    
    public function renderDefault(){
        return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
            	<ul>
            		<li class="right-divider"><a href="interesting">INTERESTING</a></li>
            		<li class="right-divider"><a href="favorites">FAVORITES</a></li>
            		<li class="right-divider"><a href="sets">SETS</a></li>
            		<li><a href="photos">PHOTOS</a></li>
            		<li class="right left-divider"><a href="http://everettquebral.com">ABOUT ME</a>
            		</li>
            	</ul>
            </div>

HTML;
    }
}
?>
