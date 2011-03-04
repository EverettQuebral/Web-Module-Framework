<?php
require_once("Module.class.php");
require_once("../facebook-0.1.0/facebook.php");

class FacebookModule extends Module {
    public function __construct($modId){
        $this->modId = $modId;
    }

    public function init(){
        

    }
    
    public function renderDefault(){
        $appapikey = 'b819effa4c59bdc1f2d50fb4118222e6';
        $appsecret = '3849b7cda2c9a1bc35c600f584e167da';
        $facebook = new Facebook($appapikey, $appsecret);
        //$user_id = $facebook->require_login();
        
        error_log("USER LOGGEDIN " . $user_id);
        

// Print out at most 25 of the logged-in user's friends,
// using the friends.get API method

//$friends = $facebook->api_client->friends_get();
//$friends = array_slice($friends, 0, 25);
//foreach ($friends as $friend) {
//  echo "<br>$friend";
//}
        
       return <<<HTML
            <div id="{$this->modId}" class="{$this->modId} mod-content">
            <!--<p>Hello, <fb:name uid=\"$user_id\" useyou=\"false\" />!</p>-->
                <a id="{$this->modId}-login" href="https://graph.facebook.com/oauth/authorize?client_id=116762935025716&redirect_uri=http://soft-worx.com/wmf/index.php&type=user_agent&display=popup">Login to facebook</a>
            </div>
HTML;
    }
}
?>
