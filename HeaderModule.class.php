<?php
require_once("Module.class.php");

class HeaderModule extends Module {
    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Header Module " . $modId);
    }

    public function renderDefault(){
        return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                <h2>Web Module Framework</h2>
                <h3>Everett Quebral</h3>
            </div>
HTML;
    }
}
?>
