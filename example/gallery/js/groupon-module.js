/**
 * definition for module-1
 * this module can be on a separate js file
 */
jsModule["module-groupon"] = {
    /** the module id in html context */
    modId : "",
    
    /** the instance of the YUI 3 library */
    Y : {},
    
    /** instance for this to provide scope correction when events are invoked */
    _this : undefined, 

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
	    this.Y.log("Groupon Module Loaded");


	var x = _this.Y.one("#"+moduleID);
            
	var url = "http://api.groupon.com/v2/deals/?client_id=a1b21ad94e8234c9ffdb3bb088a37f440b5659d8";
                  
        _this.Y.jsonp(url, function (data) {
            //alert(data);
            _this.Y.each(data.deals, function(o) {
                x.append("<div class=\"mod-content\"><h3>" + o.title + "</h3>" + "<img src=\"" + o.largeImageUrl + "\">" + o.pitchHtml +"</div>");
            });
        });

	    this.Y.on("click", this.handleClick, "#" + moduleID);
    },

    /** 
     * implement the callback handler for the handleClick in the onModuleLoad
     *
     * @param : e {event} the event object
     */
    handleClick : function(e){
        e.preventDefault();
        
        
	    // call refreshModule and send information on how to refresh the view
        var viewId,
            targetId = e.target.getAttribute("id"),
            currentTarget = e.currentTarget.getAttribute("id");
   
        var x = _this.Y.one("#"+currentTarget);
            
        var vId = targetId.substr(currentTarget.length+1);
        /*
	_this.Y.moduleApi.refreshModule(currentTarget, vId);
	*/
	var url = "http://api.groupon.com/v2/deals/?client_id=a1b21ad94e8234c9ffdb3bb088a37f440b5659d8";
                  
        _this.Y.jsonp(url, function (data) {
            //alert(data);
            _this.Y.each(data.deals, function(o) {
                x.append("<h3>" + o.title + "</h3>" + "<img src=\"" + o.largeImageUrl + "\">" + o.pitchHtml );
            });
        });
    }

};
