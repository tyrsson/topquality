define([
    "dojo/ready",
    "dojo/_base/array",
    "dojo/request",
    "dojo/query",
    "dojo/dom",
    "dojo/dom-attr",
    "dojo/dom-form",
    "dojo/dom-style",
    "dojo/dom-class",
    "dojo/dom-construct",
    "dojo/dom-geometry",
    "dojo/string",
    "dojo/on",
    "dojo/aspect",
    "dojo/_base/config",
    "dojo/_base/lang",
    "dojo/_base/fx",
    "dijit/registry",
    "dijit/WidgetSet",
    "dojo/parser",
    "aurora/admin/module",
    "/lib/ckfinder/ckfinder.js",
    "/lib/ckeditor/ckeditor.js"
], function(ready, arrayUtil, request, query, dom, domAttr, domForm, domStyle, domClass, domConstruct, domGeometry, string, on, aspect, config, lang, baseFx, registry, widgetSet, parser) {

	var ws = new widgetSet();
	

		CKEDITOR.config.filebrowserBrowseUrl = '/lib/ckfinder/ckfinder.html';
		CKEDITOR.config.filebrowserImageBrowseUrl = '/lib/ckfinder/ckfinder.html?type=Media';
		CKEDITOR.config.filebrowserFlashBrowseUrl = '/lib/ckfinder/ckfinder.html?type=Flash';
		//CKEDITOR.config.filebrowserUploadUrl = '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
		//CKEDITOR.config.filebrowserImageUploadUrl = '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
		//CKEDITOR.config.filebrowserFlashUploadUrl = '/lib/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

	
	CKEDITOR.replaceAll('ckeditor');
	
	
	ready(function(){
		// for the pageManager buttons, will be refactored to use what is below
		query(".pageManagerButton").on("click", function(event) {
			event.preventDefault();
			//alert("clicked");
			window.location = event.target.value;
		});
		query(".adminUiButton").on("click", function(event) {
			event.preventDefault();
			//alert("clicked");
			window.location = event.target.value;
		});
	});
	
	
	// called 3rd
	// run the given cmd
	run = function(cmd) {
		load(cmd);
	},
	// called 4th
	load = function(cmd) {
		var workSpace = registry.byId("wSO");
		workSpace.onLoad = function() {
			//scann for widgets loaded into the workSpace so we can set them up for user interaction
			workSpaceScan(workSpace);
		};
		window.location = cmd;
//		request.get(cmd).then(
//				function(response) {
//					
//					workSpace.set("content", response);
//				},
//				function(error) {
//					workSpace.set("content", error);
//				}
//		);
	},
	// called 5th
//	workSpaceScan = function(workSpace) {
//		if(registry.byId("wSFO")) {
//			console.log("found in workSpace scan");
//			ws.add(dijit.byId("wSFO"));
//			setupForms(dijit.byId("wSFO"));
//		}
//	},
	/**
	 * called just before submit
	 * This handles reassigning the editor's iframe content to the hidden element's content 
	 * so that it will be posted to the server in the correct field
	 * 
	 * !!!!!!! If this does not work CHECK THE html id of the editor form element !!!!!!!!
	 * the zend dojo view helper for the editor has been modified to have a 
	 * default $id = 'editor' , but that doesnt mean it wont break for some reason
	 */ 
//	beforeSubmit = function () {
//		if(registry.byClass("dijit/Editor")) {
//			console.log("editor found");
//			var editor = dom.byId("editor");
//			var content = dijit.byId("editor-Editor");
//			editor.value = content.get("value");
//			console.log(editor);
//		}
//	},
	// called 6th
	setupForms = function(form) {
		console.log(form);
		
		form.onSubmit = function(e) {
			e.preventDefault();
			e.stopPropagation();
			// handle the editor see above
			beforeSubmit();
			
			request.post(form.action,
				{
				data: domForm.toObject("wSFO")
				}).then(
				function(response){
					alert("posted");
				},
				function(error) {
					
				}
			);
		};
	},
	// called 2 nd
	createProcessThread = function(execObj) {
		execObj.onClick = function(e){
			if(execObj.cmd != "") {
				run(execObj.cmd);
			}
			
		};
	},
	// called 1
	mainMenuStartUp = function() {
		ready(function() {
			arrayUtil.forEach(registry.toArray(), function(widget, index){
				ws.add(widget);
			});
			ws.byClass("dijit.MenuBarItem").forEach(function(menuBarItemWidget){
				createProcessThread(menuBarItemWidget);
			});
			ws.byClass("dijit.MenuItem").forEach(function(menuItemWidget){
				createProcessThread(menuItemWidget);
			});
		});
		
	},
	startup = function() {
		
	};
	return {
		init: function() {
			startup();
			mainMenuStartUp();
		}
	};
});