YUI.add("module-framework", function(Y){
    Y.namespace("moduleApi");
    
    /**
     * refreshModule method refreshes the module via AJAX
     *
     * @params
     *  moduleID {string} the id of the module in the html context
     *  args {array} when present, the method will use to override the
     *      default values for the request config object
     */
    Y.moduleApi.refreshModule = function(moduleId, rendererId, args){
        if(!args){
            var handler = {
                    method : "GET",
                    on : {
                        success : function(id, o){
                            Y.log("transaction successful " + o.responseText);
                            
                            if(o.responseText.length > 0){
	                            // purge all listeners via Y.on
	                            Y.Event.purgeElement("#"+moduleId, true);
	                            
	                            // create a new document element
	                            var tempElement = document.createElement("div");
	                            tempElement.innerHTML = o.responseText;
	                            
	                            var oldElement = document.getElementById(moduleId);
	                            var parentElement = oldElement.parentNode;
	                            
	                            parentElement.replaceChild(tempElement.childNodes[1], oldElement);
	                            
	                            // signal the onModuleLoad again
	                            //jsModule[moduleId].onModuleLoad.apply(jsModule[moduleId], [moduleId]);
	                            Y.moduleApi.signal(jsModule[moduleId], jsModule[moduleId].onModuleLoad, [moduleId]);
                            }
                            
                        },
                        failure : function(id, o){
                            Y.log("transaction failed " + o.responseText);
                        }
                    }
                },
                config = {
                    url : encodeURI("moduleService.php?moduleId=" + moduleId + "&rendererId=" + rendererId)
                },
                data = {}
        }
        else {
            var handler = args.handler,
                config = args.config,
                data = args.data;
        }
        
        var request = Y.io(config.url, handler);
    };
    
    /**
     * function that can dynamically add a module
     */
    Y.moduleApi.loadModule = function(domId, modId, rendererId, args){
    };
    
    /**
     * function that will close the module
     */
    Y.moduleApi.closeModule = function(modId){
    };
    
    /**
     * function that will send a message to a module
     */
    Y.moduleApi.sendModuleMessage = function(modId, msg){
    };
    
    Y.moduleApi.signal = function(o, f, a){
        f.apply(o, a);
    };
    
    Y.moduleApi.sendRequest = function(r, c){
    };
    
}, {requires : ["base", "io"]});
