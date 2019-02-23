$__$.latlngPicker = {
	index : 1
};
$__$.registerJQueryPlugin("latlngPicker",function(){
	var $self = this;
	var $elem = $self.$elem;
	var options = $self.options;
	var $latInput, $lngInput, $mapContainer;
	var geocoder, posLat, posLng, map, marker;

	// lat-input, lng-input, map-container
	this.onInit = function(){
		$latInput = $elem.find("[lat-input]");
		$lngInput = $elem.find("[lng-input]");
		$mapContainer = $elem.find("[map-container]");
		this.initGoogleMap();
		$lngInput.on("change",function(){
			$self.updateGoogleMap();
		});
	}

	this.onUpdate = function(){
		$self.updateGoogleMap();
	}

	this.initGoogleMap = function(){
		geocoder = new google.maps.Geocoder();
		function initialize() {
			var latLng = $self.readInput();
			if(!latLng)
				return;
			var index = $__$.latlngPicker.index++;
			$mapContainer.attr("id","latlngPicker-" + index);
			map = new google.maps.Map($mapContainer.get(0), {
					zoom: 14,
				    center: latLng,
				    mapTypeId: google.maps.MapTypeId.ROADMAP
				}
			);
			marker = new google.maps.Marker( {
					position: latLng,
				    map: map,
				    draggable: true
				}
			);
			// Update current position info.
			//updateMarkerPosition(latLng);
			//geocodePosition(latLng);
			// Add dragging event listeners.
			google.maps.event.addListener(marker, 'dragstart', function() {
				// updateMarkerAddress('...');
			}
			);
			google.maps.event.addListener(marker, 'drag', function() {
				// updateMarkerStatus('...');
				updateMarkerPosition(marker.getPosition());
			}
			);
			google.maps.event.addListener(marker, 'dragend', function() {
				// updateMarkerStatus('Drag ended');
				geocodePosition(marker.getPosition());
			}
			);
			google.maps.event.addListener(map, 'click', function(e) {
				var positionClick = e.latLng;
				marker.setPosition(positionClick);
				updateMarkerPosition(marker.getPosition());
			}
			);

			var $panel = $mapContainer.parents("[role=tabpanel]");
			var $tab = $("[role=tab][href=#" + $panel.attr("id") + "]")
			$tab.on('shown.bs.tab', function(){
				$self.updateGoogleMap();
				google.maps.event.trigger(map, 'resize');
			});
		}
		//google.maps.event.addDomListener(window, 'load', initialize);
		initialize();
	}

	this.updateGoogleMap = function(){
		var latLng = $self.readInput();
		if(!latLng)
			return;
		if(!map){
			$self.initGoogleMap();
			return;
		}
		marker.setPosition(latLng);
		map.setCenter(latLng);
	}

	this.readInput = function(){
		posLat = $latInput.val();
		posLng = $lngInput.val();
		if(isNaN(parseFloat(posLat)) || isNaN(parseFloat(posLat))){
			return null;
		}
		var latLng = new google.maps.LatLng(posLat, posLng);
		return latLng;
	}

	function geocodePosition(pos) {
		geocoder.geocode( {
			latLng: pos
		}
		, function(responses) {
			if (responses && responses.length > 0) {
				updateMarkerAddress(responses[0].formatted_address);
			} else {
				updateMarkerAddress('KhĂ´ng tháº¥y Ä‘á»‹a chá»‰');
			}
		}
		);
	}
	function updateMarkerStatus(str) {
		// document.getElementById('markerStatus').innerHTML = str;
	}
	function updateMarkerPosition(latLng) {
		$latInput.val(latLng.lat());
		$lngInput.val(latLng.lng());
	}

	function updateMarkerAddress(str) {
		//$('input#inputBusinessAddress').val(str);
	}

},{},"[latlng-picker]");