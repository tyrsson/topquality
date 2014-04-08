define([
    "dojo/request/xhr",
    "dojo/query",
    "dojo/dom",
    "dojo/dom-style",
    "dojo/dom-class",
    "dojo/dom-construct",
    "dojo/NodeList",
    "dojo/NodeList-manipulate"
    "dojo/string",
    "dojo/on",
    "dojo/aspect",
    "dojo/keys",
    "dojo/_base/config",
    "dojo/_base/lang",
    "dojo/_base/fx",
    "dijit/registry",
    "dojo/parser"
], function(xhr, query, dom, domStyle, domClass, domConstruct, domGeometry, formManager, string, on, aspect, keys, config, lang, baseFx, registry, parser, ContentPane, Tooltip, ckeditor) {


	startup = function() {

		query(".navigation a").on("click", function(event) {
			event.preventDefault();
			var title = event.target.title;
			var href = event.target.href;
			createPanel(href, title);
			//alert("Load Image");
		});
				
	};
	return {
		init: function() {
			startup();
		}
	};
});