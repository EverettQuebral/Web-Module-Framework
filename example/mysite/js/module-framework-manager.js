YUI().use("node", "module-framework", "io", "gallery-jsonp",
    function(Y){
        
        var jsMod = jsModule,           /** this is the global variable that handles the modules */
            jsFailedInit = [];          /** modules that failed that initModule */
       
        function initModules(){
            Y.log("initializing the modules");
		    Y.each(jsMod, function(modObject, modId){
		        if(!modObject.initModule){
		            Y.log("initModule for " + modId + " was not implemented");
		        }
		        else {
		            try{
		                Y.moduleApi.signal(modObject, modObject.initModule, [{"Y" : Y, "modId" : modId}]);
		            }
		            catch(ex){
		                jsFailedInit.push(modId);
		                Y.log("Error caught in initModule for " + modId + " : " + ex.message);
		            }
		        }
		        
		    });
        };
        
        function loadModules(){
            Y.log("loading the modules");
		    Y.each(jsMod, function(modObject, modId){
		        if(jsFailedInit.indexOf(modId) === -1){
		            if(!modObject.onModuleLoad){
		                Y.log("onModuleLoad for " + modId + " was not implemented");
		            }
		            else {
		                try{
		                	Y.log("signal onModuleLoad to " + modId)
		                    Y.moduleApi.signal(modObject, modObject.onModuleLoad, [modId]);
		                }
		                catch(ex){
		                    Y.log("Error caught in onModuleLoad for " + modId + " : " + ex.message);
		                }
		            }
		        }
		        else {
		            Y.log("bypass module that failed in initModule " + modId);
		        }
		    });
        }
        
        
        function runIt(){
            initModules();
            loadModules();
        }
        
        
        // runit 
	    Y.on("domready", runIt);
    });
