<?php
/**
 * The MIT License
 * 
 * Copyright (c) 2011 Everett Quebral Everett.Quebral@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * 
 * This is be the renderer for the photo
 * 
 * Take note that the structure should be able to accept theme
 * @author: Everett Quebral
 * 
 * 
 */

require_once("../wmf/Module.class.php");

class ContentModule extends Module {
    protected $photos;
    protected $smallLink;
    protected $thumbLink;
    protected $interestingLink;
    protected $favoritesLink;
    protected $setsLink;
    protected $photosLink;
    protected $aboutMeLink;
    protected $pages;
    protected $perPage;
    protected $currentPage;
    
	public function __construct($modId){
        parent::__construct($modId);
    }
    

    /**
     * @override
     * 
     * we need to override the setContext function to add new data to the context
     * @param unknown_type $context
     */
    public function setContext($context){
   		parent::setContext($context);
   		
   		// this is a test only, this needs to be removed
   		//$this->context["testingVariable"] = "someValue";
   		$this->context["photosetId"] = $_REQUEST[$this->modId]["photosetId"];
    }
    
    public function setData($data){
    	switch($this->context["rendererId"]){
    		case "sets" : 
    			$this->data = $data["photosets"]["photoset"];
    			$this->pages = $data["photosets"]["photoset"]["pages"];
    			$this->perPage = $data["photosets"]["photoset"]["perpage"];
    			break;
    		case "set" :
    			$this->data = $data["photoset"]["photo"];
    			$this->pages = $data["photoset"]["pages"];
    			$this->perPage = $data["photoset"]["perpage"];
    			break;
    		case "photos" :
    			$this->data = $data["photos"]["photo"];
    			$this->pages = $data["photos"]["pages"];
    			$this->perPage = $data["photos"]["perpage"];
    			break;
    		default : 
    			$this->data = $data["query"]["results"]["photo"];
    			break;
    	}
    }
    
    public function setStrings($strings){
    	$this->strings = $strings;
    	$this->setLinks();
    }
    
    private function setLinks(){
    	$this->smallLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId"=>"small")), 
    									$this->strings["small"]);
    	$this->thumbLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId"=>"thumb")), 
    									$this->strings["thumb"]);
    	$this->interestingLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId"=>"interesting")), 
    									$this->strings["interesting"], "right-divider");
    	$this->favoritesLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId"=>"favorites")), 
    									$this->strings["favorites"], "right-divider");
    	$this->setsLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId" => "sets")), 
    									$this->strings["sets"], "right-divider");
    	$this->photosLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId"=>"photos")), 
    									$this->strings["photos"], "right-divider");
    	$this->aboutMeLink = Utility::createLink(
    									$this->modId, 
    									$this->getUrl(array("rendererId" => "aboutMe")), 
    									$this->strings["aboutMe"], "right left-divider");	
    }
    
    private function renderPhoto($photo, $size="m"){
    	//http://farm3.static.flickr.com/2427/3722264533_6f9bde4143_m.jpg
    	return "<img src=\"http://farm{$photo["farm"]}.static.flickr.com/{$photo["server"]}/{$photo["id"]}_{$photo["secret"]}_{$size}.jpg\">";
    }
    
    private function createList($count=9){
    	$size = $count == 9 ? "m" : "s"; 
    	////echo "<pre>". print_r($this->data) . "</pre>";
    	$posts = "<ul class=\"photoset\">";

    	foreach($this->data as $photo){
    		//echo "<pre>". print_r($photo) . "</pre>";
    		$title = $photo["title"];
    		$link = "";
    		//$currentPhoto = "http://farm{$photo["farm"]}.static.flickr.com/{$photo["server"]}/{$photo["id"]}_{$photo["secret"]}_{$size}.jpg";
    		$currentPhoto = "http://soft-worx.com/gallery/index.php?" 
    						. http_build_query($this->getUrl(array("rendererId"=>"photo",
		    														"farm"=>$photo["farm"],
		    														"server"=>$photo["server"],
		    														"id"=>$photo["id"],
		    														"secret"=>$photo["secret"],
		    														"title"=>$photo["title"])));
    		$fbIframe = "<iframe src=\"http://www.facebook.com/plugins/like.php?href={$currentPhoto}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; max-width:210px; height:100px; margin: 0 auto;\"></iframe>";
    		switch($this->context["rendererId"]){
    			case "sets" :
	    			$linkTitle = Utility::createLink(
	    								$this->modId,
	    								$this->getUrl(array("rendererId"=>"set", "photosetId" => $photo["id"], "page"=>1, "perPage"=>9)),
	    								$photo["title"]["_content"]);
    				$photo["id"] = $photo["primary"];
	    			break;
    			case "interesting" :
    				$linkTitle = Utility::createLink($this->modId, 
    												$this->getUrl(array("rendererId"=>"photo",
    														"farm"=>$photo["farm"],
    														"server"=>$photo["server"],
    														"id"=>$photo["id"],
    														"secret"=>$photo["secret"],
    														"title"=>$photo["title"])),
    												$photo["title"]);
    				break;
    			case "set"	:
    				//$linkTitle = "<a href=\"{$currentPhoto}\">{$photo["title"]}</a>{$fbIframe}";
    				$linkTitle = Utility::createLink($this->modId, 
    												$this->getUrl(array("rendererId"=>"photo",
    														"farm"=>$photo["farm"],
    														"server"=>$photo["server"],
    														"id"=>$photo["id"],
    														"secret"=>$photo["secret"],
    														"title"=>$photo["title"])),
    												$photo["title"]) . $fbIframe;
    				break;
    			case "photos" :
					//$linkTitle = "<a href=\"{$currentPhoto}\">{$title}</a>{$fbIframe}";
					$linkTitle = Utility::createLink($this->modId, 
    												$this->getUrl(array("rendererId"=>"photo",
    														"farm"=>$photo["farm"],
    														"server"=>$photo["server"],
    														"id"=>$photo["id"],
    														"secret"=>$photo["secret"],
    														"title"=>$photo["title"])),
    												$photo["title"]) . $fbIframe;
    				break;
    			case "renderOnePhoto" :
    				//$linkTitle = 
    				break;
    			default :
    				$linkTitle = $title;
    				break;
    		}
    		$posts .= <<<HTML
    			<li class="photo">
    				{$this->renderPhoto($photo, $size)}
    				<h3>{$linkTitle}</h3>
    				<p>{$photo["description"]["_content"]}</p>
    			</li>
HTML;
    	}
    		
    	$posts .= "</ul>";
    	return $posts;
    }
    
    private function renderDisplayLinks(){
    	$html = <<<HTML
			<div class="mod-links">
	    		{$this->smallLink}
				{$this->thumbLink}
			</div>
HTML;
	
    }
    
    private function renderModuleLinks(){
    	$html = <<<HTML
			<ul class="menu">
				<li class="right-divider">{$this->interestingLink}</li>
				<li class="right-divider">{$this->favoritesLink}</li>
				<li class="right-divider">{$this->setsLink}</li>
				<li class="right-divider">{$this->photosLink}</li>
				<li class="right left-divider">{$this->aboutMeLink}</li>
			</ul>
HTML;
    	return $html;
    }
    
    /**
     * render one photo on medium size 
     * with some information
     * 
     * @param string $id
     */
    public function renderOne($id){
    	// need to render 1 item only
    }
    
    /**
     * display the list in thumb size and grid layout
     */
    public function renderDefault(){
        return $this->renderThumb();
    }
    
    /**
     * dipslay photo in small size and on grid layout
     */
    public function renderSmall(){
    	return <<<HTML
    		<div id="{$this->modId}" class="{$this->modId} mod-content small">
    			{$this->renderModuleLinks()}
    		</div>
HTML;
    	
    }
    
    /**
     * display photo in thumnail size
     * 
     * if displayed as grid then display some more information about the picture
     * 
     * @param unknown_type $isGrid
     */
    public function renderThumb($isGrid = true){
    	return <<<HTML
    		<div id="{$this->modId}" class="{$this->modId} mod-content thumb">
    			{$this->renderModuleLinks()}
    		</div> 
HTML;
    }
    
    private function renderContent($title, $content, $header="", $footer=""){
    	return <<<HTML
    		<div id="{$this->modId}" class="{$this->modId} mod-content thumb">
    			<div class="hd">{$this->renderModuleLinks()}</div>
    			<div class="bd">
					<h2>{$title}</h2>
    				{$content}
    			</div>
    			<div class="ft">{$footer}</div>
    		</div>
HTML;
    }
    
    public function renderInteresting(){
    	$this->title = " : " . $this->strings["interesting"]; 
    	return $this->renderContent("Interesting", $this->createList());
    }
    
    public function renderFavorites(){
    	$this->title = " : " . $this->strings["favorites"]; 
    	return $this->renderContent("Favorites", $this->createList());
    }
    
    public function renderSets(){
    	$this->title = " : " . $this->strings["sets"]; 
    	return $this->renderContent("Sets", $this->createList());
    }
    
    public function renderSet(){
    	return $this->renderContent("Set", $this->createList());
    }
    
    public function renderPhotos(){
    	$this->title = " : " . $this->strings["photos"]; 
    	return $this->renderContent("Photos", $this->createList());
    }
    
    public function renderOnePhoto(){
    	$farm 	= $_REQUEST[$this->modId]["farm"];
    	$server = $_REQUEST[$this->modId]["server"];
    	$id 	= $_REQUEST[$this->modId]["id"];
    	$secret = $_REQUEST[$this->modId]["secret"];
    	$title 	= $_REQUEST[$this->modId]["title"];
    	
    	//http://soft-worx.com/gallery/index.php?module-content%5BrendererId%5D=photo&module-content%5Bfarm%5D=5&module-content%5Bserver%5D=4144&module-content%5Bid%5D=5088769200&module-content%5Bsecret%5D=0f6fa1d455&module-content%5Btitle%5D=IMG_9947
    	
    	$currentPhoto = "http://soft-worx.com/gallery/index.php?" . http_build_query($_REQUEST);
    	$fbIframe = "<iframe src=\"http://www.facebook.com/plugins/like.php?href={$currentPhoto}\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; max-width:800px; height:75px; margin: 0 auto; float:right;\"></iframe>";
    	
    	$this->title = " : " . $title;
    	return $this->renderContent($title . $fbIframe, "<div class=\"single-photo\"><img src=\"http://farm{$farm}.static.flickr.com/{$server}/{$id}_{$secret}_b.jpg\"></div>");
    }
}
?>
