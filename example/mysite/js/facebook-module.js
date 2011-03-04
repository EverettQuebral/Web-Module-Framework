/**
 * definition for module-1
 * this module can be on a separate js file
 */
jsModule["module-facebook"] = {
    /** the module id in html context */
    modId : "",
    
    /** the instance of the YUI 3 library */
    Y : {},
    
    /** instance for this to provide scope correction when events are invoked */
    _this : {}, 

    /** 
     * implementation of the initModule interface
     * this interface is called when all modules are loaded
     * and the dom is ready
     *
     * @param : api {array} of objects
     *  api.moduleApi {object} the moduleApi sent back from the module platform
     *  api.Y   {object}    the YUI 3 instance sent back from the module platform
     */
    initModule : function(api){ 
	    /** get the api objects */
	    var Y = api.Y,                  // get the instance of Y and put in a local var
	        modId = api.modId;            // get the ID of the module
	    
	    /** extend the libraries and make a reference to this scope */
	    this.Y = Y;
	    this.Y.use("json", "gallery-jsonp");
	    this.modId = modId;
	    
	    _this = this;                   // reference of this
    },

    /**
     * implementation of the onModuleLoad interface
     * this interface is called when all modules are initialized
     *
     * @param : moduleID {string} the id of the module in the html view
     */
    onModuleLoad : function(moduleID){
	    this.Y.log(moduleID + "loaded");
	    
	    // we can load the facebook updates once the access_token value is set
	    var loc, 
	        accessTokenIndex, 
	        accessTokenName = "access_token", 
	        accessTokenValue;
	    
	    loc = window.location.href;
	    accessTokenIndex = loc.indexOf(accessTokenName);
	    if(accessTokenIndex != -1){
	        accessTokenValue = loc.substring(accessTokenIndex+1);
accessTokenValue = accessTokenValue.substring(accessTokenValue.indexOf("=")+1);	        
	        this.facebookUpdates(accessTokenName, accessTokenValue, moduleID);	        
	        
	    }   
	    
	    this.Y.on("click", this.handleClick, "#" + moduleID);
    },
    
    facebookUpdates : function(atn, atv, moduleId){
        var url = "https://graph.facebook.com/me/home?access_token="+atv+"&callback=myCallback",
            x = _this.Y.one("#"+moduleId),
            strMain = ["<ul>"],
	    str = [];     

        _this.Y.jsonp(url, function (data) {
            _this.Y.log(data);
            _this.Y.each(data["data"], function(v,k) {
                str.push("<li>");
		_this.Y.each(v, function(_v, _k){
                    switch(_k){
                        case "comments" : 
                            //_this.Y.each(v, function(_v, _k){});
                            break;
                        case "from" :
                            str.push("<span>", "From " , _v["name"], "</span>"); 
                            break; 
                        case "message" :
                            str.push("<span>", "Message ", _v, "</span>");
                    }
                });
		str.push("</li>");
		strMain.push(str.join(""));
		str = [];
            });
		strMain.push("</ul>");
		x.set("innerHTML", "");
		x.append(strMain.join(""));
        });
    
},

    /** 
     * implement the callback handler for the handleClick in the onModuleLoad
     *
     * @param : e {event} the event object
     */
    handleClick : function(e){
        // if the window.location.href already contains the access_token, it means
        // that we have an access to facebook so we can remove the link saying login to facebook

        var viewId,
            targetId = e.target.getAttribute("id"),                     // the module view
            currentTarget = e.currentTarget.getAttribute("id");         // the element clicked
   
	if(targetId === currentTarget + "-login"){
            // let the user click the link
        }
        else {
            e.preventDefault();
        }
        /*    
        var vId = targetId.substr(currentTarget.length+1);
	    _this.Y.moduleApi.refreshModule(currentTarget, vId);
	    */  
	    
	    
	    /*
	    var url = "http://query.yahooapis.com/v1/public/yql?" +
                  "q=select%20*%20from%20upcoming.events%20" +
                  "where%20woeid%20in%20" + 
                  "(select%20woeid%20from%20geo.places%20" +
                  "where%20text%3D%22North%20Beach%22)" +
                  "&format=json&diagnostics=false";
                  
        _this.Y.jsonp(url, function (data) {
            alert(data);
            _this.Y.each(data.query.results.event, function (o) {
                x.append("<h3>" + o.name + "</h3><p>" + o.description + "</p>");
            });
        });
        */
    }

};
