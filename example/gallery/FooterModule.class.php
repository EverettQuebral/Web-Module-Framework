<?php
require_once("../../core/Module.class.php");

class FooterModule extends Module {
    public function __construct($modId){
        parent::__construct($modId);
    }
    
    public function renderDefault(){
    	$link1 = "?" . http_build_query($this->getUrl(array("rendererId"=>"view-1")));
        return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
            	<p>This Gallery is based from the Module Framework Developed by Everett Quebral written in PHP and YUI3</p>
            	<p>
            		All rights reserved 2011 &copy; Copyright <a href="mailto:rainambon@yahoo.com">Everett Quebral</a>
            	</p>
            </div>

HTML;
    }

	public function renderMobile(){
		return <<<HTML
			<div data-role="footer" data-theme-"d">		
				<div data-role="navbar">
					<ul>
						<li><a href="#interesting">Interesting</a></li>
						<li><a href="#favorites">Favorites</a></li>
						<li><a href="#">Sets</a></li>
						<li><a href="#">Photos</a></li>
						<li><a href="#aboutme">About Me</a></li>
					</ul>
				</div>
			</div>
HTML;
	}
}
?>
