/**
 * definition for module-1
 * this module can be on a separate js file
 */
jsModule["module-2"] = {
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
	    this.Y.use("json");
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
	    this.Y.log("module 1 loaded");
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
            
        var vId = targetId.substr(currentTarget.length+1);
	    _this.Y.moduleApi.refreshModule(currentTarget, vId);
    }

};
