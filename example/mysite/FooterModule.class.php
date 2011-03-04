<?php
require_once("Module.class.php");

class FooterModule extends Module {
    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Footer Module " . $modId);
    }
    
    public function renderDefault(){
        return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">This is the footer</div>

HTML;
    }
}
?>
