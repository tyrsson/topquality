define([
    "dojox/grid/DataGrid", 
    "dojox/grid/cells", 
    "dojox/grid/cells/dijit",
    "dojo/date/locale", 
    "dojo/currency", 
    "dijit/form/DateTextBox", 
    "dijit/form/CurrencyTextBox",
    "dijit/form/HorizontalSlider", 
    "dijit/registry",
    "dojo/domReady!"
], function(DataGrid, gridCells, cellDijit, locale, currency, dateBox, currencyBox, registry) {

	// called 1
	connectGrid = function() {
		if(dojo.byId("pageGrid")) {
			console.log(dojo.byId("pageGrid"));
		}
	},
	startup = function() {
		//alert("running");
	};
	return {
		init: function() {
			startup();
			connectGrid();
		}
	};
});