<?php

require_once("../../core/Module.class.php");
require_once("../../core/IDataModule.class.php");

class GrouponModule extends Module implements IDataModule {
    //protected $modId;					// interface
    //protected $data;					// interface
    
    protected $deals;

    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Updates Module " . $modId);
    }
    
    public function setData($data){
    	$this->data = $data;
    	
    	$this->deals = $data["deals"];
	print_r($this->deals);
    }
    
    public function init(){
        error_log("initializing Updates Module");
    }
    
    private function renderHeader(){
        $html = <<<HTML
            <h2>Groupon Deals</h2>
HTML;
        return $html;
    }
    
    private function renderLinks(){
    	$html = <<<HTML
    		<div class="mod-links">
	    		<a id="{$this->modId}-view-2" href="index.php?moduleId={$this->modId}&rendererId=view-2">Photo & Links</a>
				<a id="{$this->modId}-view-1" href="index.php?moduleId={$this->modId}&rendererId=view-1">Links Only</a>
			</div>
HTML;
		return $html;
    }
    
    
    public function renderLinksOnly(){
        $posts = "<ul>";
        foreach($this->deals as $deal){
            $posts .= $deal["title"];
        }
        $posts .= "</ul>";
    
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                {$this->renderHeader()}
                {$posts}
                {$this->renderLinks()}
            </div>
            
HTML;
        return $html;
        
    }
    
    public function renderPhotoLinks(){
    	$posts = "<ul>";
        foreach($this->memePosts as $post){
            $posts .= $this->getFormattedPost($post, false);
        }
        $posts .= "</ul>";
    
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                {$this->renderHeader()}
                {$posts}
                {$this->renderLinks()}
            </div>
            
HTML;
        return $html;
    }
    
    public function renderDefault(){
/*
        $posts = "<ul>";

        foreach($this->deals as $deal){
            $posts .= $deal["title"];
        }
        $posts .= "</ul>";
*/    
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                Groupon Deals
            </div>
            
HTML;
        return $html;
    }
}
?>
