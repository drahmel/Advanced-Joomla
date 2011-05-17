var overlay;

function storelocatorToolTip(options) {
	this.latlng_ = options.marker ? options.marker.getPosition() : options.latlng;
	this.map_ = options.map;
	this.marker_ = options.marker ? options.marker : null;

	this.offsetHorizontal_ = options.offset ? options.offset.x : 15;
	this.offsetVertical_ = options.offset ? options.offset.y : -35;
	this.height_ = options.height ? options.height : 'auto';
	this.width_ = options.width ? options.width : 'auto';

	
	var css = {
		border: '1px solid black',
		backgroundColor: '#ffffff',
		padding: '2px 5px',
		fontSize: '11px',
		whiteSpace: 'nowrap',
		maxWidth: '300px',
		fontFamily: 'Tahoma, Verdana, Arial, Sans-serif',
		cursor: 'pointer'
	};
	if (options.css) {
		for (var i in options.css) {
			css[i] = options.css[i];
		}
	}


	this.css_ = css;
	this.html_ = options.html ? options.html : null;
	if (!this.html_ && options.marker) { 
		this.html_ = options.marker.title;
		// Clear browser tooltip
		options.marker.title = null; 
	} else if (!this.html_) {
		this.html_ = 'no content';
	}

	this.div_ = null;
	this.setMap(this.map_);
}

storelocatorToolTip.prototype = new google.maps.OverlayView();

storelocatorToolTip.prototype.onAdd = function() {
	var self = this;
	var div = document.createElement('DIV');
	div.style.border = "none";
	div.style.borderWidth = "0px";
	div.style.position = "absolute";
	div.setAttribute("class", "storelocatorToolTip");
	div.innerHTML = this.html_;
	for (var i in this.css_) {
		div.style[i] = this.css_[i];
	}

	if (typeof div.style.filter != 'undefined') {
		div.style.filter += "progid:DXImageTransform.Microsoft.gradient(gradientType=0, startColorstr='#ffffff', endColorstr='#cccccc')"; 
	}

	this.div_ = div;

	if (this.marker_) {
		google.maps.event.addDomListener(this.marker_, 'click', function() { self.toggle(); });
	}

	google.maps.event.addDomListener(this.div_, 'click', function() { self.toggle(); });

	var panes = this.getPanes();
	panes.overlayLayer.appendChild(div);
	div.parentNode.style.zIndex = 1*div.parentNode.style.zIndex + 10;
}

storelocatorToolTip.prototype.draw = function() {
	if (!this.div_) return;

	var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng_);
	if (!pixPosition) return;

	this.div_.style.left = (pixPosition.x + this.offsetHorizontal_) + "px";
	this.div_.style.top = (pixPosition.y + this.offsetVertical_) + "px";
	this.div_.style.width = this.width_ != 'auto' ? this.width_ + "px" : this.width_;
	this.div_.style.height = this.height_ != 'auto' ? this.height_ + "px" : this.height_;
	this.div_.style.display = 'block';
}

storelocatorToolTip.prototype.onRemove = function() {
	this.div_.parentNode.removeChild(this.div_);
	this.div_ = null;
}

storelocatorToolTip.prototype.hide = function() {
	if (this.div_) {
		this.div_.style.visibility = "hidden";
	}
}

storelocatorToolTip.prototype.show = function() {
	if (this.div_) {
		this.div_.style.visibility = "visible";
	}
}

storelocatorToolTip.prototype.toggle = function() {
	if (this.div_) {
		if (this.div_.style.visibility == "hidden") {
			this.show();
		} else {
			this.hide();
		}
	}
}

storelocatorToolTip.prototype.toggleDOM = function() {
	if (this.getMap()) {
		this.setMap(null);
	} else {
		this.setMap(this.map_);
	}
}
