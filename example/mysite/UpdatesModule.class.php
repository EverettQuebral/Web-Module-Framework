<?php

require_once("../../core/Module.class.php");
require_once("../../core/IDataModule.class.php");

class UpdatesModule extends Module implements IDataModule {
    //protected $modId;					// interface
    //protected $data;					// interface
    
    protected $memePosts;

    public function __construct($modId){
        $this->modId = $modId;
        error_log("construct Updates Module " . $modId);
    }
    
    public function setData($data){
    	$this->data = $data;
    	
    	$this->memePosts = $data["query"]["results"]["post"];
    }
    
    public function init(){
        error_log("initializing Updates Module");
    }
    
    private function getFormattedPost($post, $linksOnly=true){
        switch($post["type"]){
            case "text" : 
               // $content = $post["content"]["thumb"];  
				$content = '<img src="'. $post["content"]["thumb"] .'">';
                break;
			case "video" :
				$content = '<a href="' . $post["content"] . '"> Video Here </a>';
				break; 
            case "photo" : 
                if($linksOnly){
                    //$content = $post["content"];
					$content = '<img src="'. $post["content"]["thumb"] .'">';
                }
                else {
                    $content = '<img src="'. $post["content"]["content"] .'">';
                }
                break;
        }
        
        $html = <<<HTML
            <li class="meme-post">
                <p>{$post["caption"]}</p>	
                <a href="{$post["url"]}">{$content}</a>
            </li>
HTML;
        return $html;
    }
    
    private function renderHeader(){
        $html = <<<HTML
            <h2>Most Popular Meme</h2>
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
        foreach($this->memePosts as $post){
            $posts .= $this->getFormattedPost($post, true);
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
        $posts = "<ul>";
        foreach($this->memePosts as $post){
            $posts .= $this->getFormattedPost($post);
        }
        $posts .= "</ul>";
    
        $html = <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
                {$posts}
            </div>
            
HTML;
        return $html;
    }
}
?>
