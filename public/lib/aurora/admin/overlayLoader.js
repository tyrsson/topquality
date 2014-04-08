define([
    "dojo/ready",
    "dojo/request",
    "dojo/query",
    "dojo/dom",
    "dojo/dom-attr",
    "dojo/dom-form",
    "dojo/dom-style",
    "dojo/dom-class",
    "dojo/dom-construct",
    "dojo/_base/window",
    "dojo/dom-geometry",
    "dojo/string",
    "dojo/on",
    "dojo/_base/fx",
    "dojo/parser"
], function(ready, request, query, dom, domAttr, domForm, domStyle, domClass, domConstruct, win, domGeometry, string, on, baseFx, parser) {
	
	var overlayNode;
	

	build = function() {
		overlayNode = domConstruct.place('<div id="loadingOverlay" class="loadingOverlay pageOverlay">Loading... from js</div>', win.body());
	},
	show = function() {
		//alert("running");
		domStyle.set(overlayNode, "display", "block");
	},
	hide = function() {
		domStyle.set(overlayNode, "display", "none");
	};
	
	ready(function() {
		alert("dom is ready");
	});
	
	return {
		init: function() {
			build();
			return this;
		}

	};
});