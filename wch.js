
// based largely on WCH.js - Windowed Controls Hider v3.10
var WCH_Constructor = function() {
	//	exit point for anything but IE5.0+/Win
	if ( !(document.all && document.getElementById && !window.opera && navigator.userAgent.toLowerCase().indexOf("mac") == -1) ) {
		this.Apply = function() {};
		this.Discard = function() {};
		return;
	}

	//	private properties
	var _bIE55 = false;
	var _bIE6 = false;
	var _oRule = null;
	var _bSetup = true;
	var _oSelf = this;

	//	public: hides windowed controls
	this.Apply = function(vLayer, vContainer, bResize) {
		if (_bSetup) {
			document.getElementById(vLayer).style.left = "auto";
			_Setup();
		}

		if ( _bIE55 && (oIframe = _Hider(vLayer, vContainer, bResize)) ) {
			document.getElementById(vLayer).style.left = "auto";
			oIframe.style.visibility = "visible";
		} else if(_oRule != null) {
			document.getElementById(vLayer).style.left = "-999em";
			_oRule.style.visibility = "hidden";
		}

	};

	//	public: shows windowed controls
	this.Discard = function(vLayer, vContainer) {
		if ( _bIE55 && (oIframe = _Hider(vLayer, vContainer, false)) ) {
			document.getElementById(vLayer).style.left = "-999em";
			oIframe.style.visibility = "hidden";
		} else if(_oRule != null) {
			document.getElementById(vLayer).style.left = "auto";
			_oRule.style.visibility = "visible";
		}
	};

	//	private: returns iFrame reference for IE5.5+
	function _Hider(vLayer, vContainer, bResize) {
		var oLayer = _GetObj(vLayer);
		var oContainer = ( (oTmp = _GetObj(vContainer)) ? oTmp : document.getElementsByTagName("body")[0] );
		if (!oLayer || !oContainer) return;
		//	is it there already?
		var oIframe = document.getElementById("WCHhider" + oLayer.id);
		
		//	if not, create it
		if ( !oIframe ) {
			//	IE 6 has this property, IE 5 not. IE 5.5(even SP2) crashes when filter is applied, hence the check
			var sFilter = (_bIE6) ? "filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);" : "";
			//	get z-index of the object
			var zIndex = oLayer.style.zIndex;
			if ( zIndex == "" ) zIndex = oLayer.currentStyle.zIndex;
			zIndex = parseInt(zIndex);
			//	if no z-index, do nothing
			if ( isNaN(zIndex) ) return null;
			//	if z-index is below 2, do nothing (no room for Hider)
			if (zIndex < 2) return null;
			//	go one step below for Hider
			zIndex--;
			var sHiderID = "WCHhider" + oLayer.id;
			oContainer.insertAdjacentHTML("afterBegin", '<iframe class="WCHiframe" src="javascript:false;" id="' + sHiderID + '" scroll="no" frameborder="0" style="position:absolute;visibility:hidden;' + sFilter + 'border:0;top:0;left;0;width:0;height:0;background-color:#ccc;z-index:' + zIndex + ';"></iframe>');
			oIframe = document.getElementById(sHiderID);
			//	then do calculation
			_SetPos(oIframe, oLayer);
		} else if (bResize) {
			//	resize the iFrame if asked
			_SetPos(oIframe, oLayer);
		}
		return oIframe;
	};

	//	private: set size and position of the Hider
	function _SetPos(oIframe, oLayer) {
		//	fetch and set size
		oIframe.style.width = oLayer.offsetWidth + "px";
		oIframe.style.height = oLayer.offsetHeight + "px";
		//	move to specified position
		oIframe.style.left = oLayer.offsetLeft + "px";
		oIframe.style.top = oLayer.offsetTop + "px";
	};

	//	private: returns object reference
	function _GetObj(vObj) {
		var oObj = null;
		switch( typeof(vObj) ) {
			case "object":
				oObj = vObj;
				break;
			case "string":
				oObj = document.getElementById(vObj);
				break;
		}
		return oObj;
	};

	//	private: setup properties on first call to Apply
	function _Setup() {
		_bIE55 = (typeof(document.body.contentEditable) != "undefined");
		_bIE6 = (typeof(document.compatMode) != "undefined");

		if (!_bIE55) {
			if (document.styleSheets.length == 0)
				document.createStyleSheet();
			var oSheet = document.styleSheets[0];
			oSheet.addRule(".WCHhider", "visibility:visible");
			_oRule = oSheet.rules(oSheet.rules.length-1);
		}

		_bSetup = false;
	};
};
var WCH = new WCH_Constructor();

