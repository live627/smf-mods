// Constructor
function smf_BoardSend(oOptions) {
	this.opt = oOptions;
	this.oBoardAutoSuggest = null;
	this.oBoardListContainer = null;
	this.init();
}

smf_BoardSend.prototype.init = function() {
    // Create the autosuggest object
	this.oBoardSpecAutoSuggest = new smc_AutoSuggest({
		sSelf : this.opt.sSelf + '.oBoardSpecAutoSuggest',
		sSessionId : this.opt.sSessionId,
        sSessionVar : this.opt.sSessionVar,
		sSuggestId : 'boardspec',
		sControlId : this.opt.sControlId,
		sSearchType : 'board',
		sPostName : 'search',
		sTextDeleteItem : this.opt.sTextDeleteItem,
		bItemList : false,
	});
    // Override the itemClicked prototype from smc_AutoSuggest
    smc_AutoSuggest.prototype.itemClicked = function(oCurElement) {
        // Remove the unfinished search text, this also appends the text to the actual textbox
        this.oTextHandle.value = this.oTextHandle.value.replace(new RegExp(this.sLastSearch, "g"), "");
  		this.oTextHandle.value += oCurElement.sItemId; 
        // Set this so the boards URL parameter doesnt store the wrong value
        // What actually hapens here is the value from oTextHandle is then stored in oRealTextHandle
        this.oRealTextHandle.value = this.oTextHandle.value;
        // Set no name to prevent the dummy URL parameter
        this.oTextHandle.name = "";
       	this.autoSuggestActualHide();
       	this.oSelectedDiv = null;
    }
    this.oBoardSpecAutoSuggest.registerCallback('onBeforeAddItem', this.opt.sSelf + '.callbackAddItem');
}

smf_BoardSend.prototype.callbackAddItem = function(oAutoSuggestInstance, sSuggestId) {
	this.oBoardSpecAutoSuggest.deleteAddedItem(sSuggestId);
	return true;
}