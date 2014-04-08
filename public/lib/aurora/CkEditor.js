define([
    "dojo/ready",
    "dojo/on",
    "/lib/ckeditor/ckeditor.js",
    "/lib/ckfinder/ckfinder.js"
], function(ready, on) {

	startup = function() {
		ready(function(){
			var editor = CKEDITOR.replaceAll("ckeditor");
			CKFINDER.SetupCKEditor(editor, "/lib/ckfinder/");
		});
		//CKEDITOR.replaceAll('ckeditor');
	};
	return {
		init: function() {
			startup();
		}
	};
});