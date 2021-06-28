/*
Author       : Sean Kelly
Template Name: DocBooking - Bootstrap Template
Version      : 1.2
*/

google.maps.visualRefresh = true;
var slider, infowindow = null;
var bounds = new google.maps.LatLngBounds();
var map, current = 0;

var icons = {
  'default': 'assets/img/marker.png'
};

function show() {
  infowindow.close();
  if (!map.slide) {
    return;
  }
  var next, marker;
  if (locations.length == 0) {
    return
  } else if (locations.length == 1) {
    next = 0;
  }
  if (locations.length > 1) {
    do {
      next = Math.floor(Math.random() * locations.length);
    } while (next == current)
  }
  current = next;
  marker = locations[next];
  setInfo(marker);
  infowindow.open(map, marker);
}

function initialize() {
  // This is the minimum zoom level that we'll allow
  var EU_BOUNDS = {
    north: 52.598152,
    south: 44.678547,
    west: -1.359646,
    east: 28.193576
  };
  var bounds = new google.maps.LatLngBounds();
  var mapOptions = {
    center: new google.maps.LatLng(47.3068538, 7.8957733),
    /*restriction: {
      latLngBounds: EU_BOUNDS,
            strictBounds: false,
    },*/
    zoom: 12
  };
  
  var input = document.getElementById("search-location");
  var countries = ["ch"];
  var autocomplete = new google.maps.places.Autocomplete(input);
  // Set initial restrict to the greater list of countries.
  autocomplete.setComponentRestrictions({ country: countries });
  // Specify only the data fields that are needed.
  autocomplete.setFields(["address_components", "geometry", "icon", "name"]);
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
	  var place = autocomplete.getPlace();
	  
	  if (!place.geometry) {
		  // User entered the name of a Place that was not suggested and
		  // pressed the Enter key, or the Place Details request failed.
		  window.alert("No details available for input: '" + place.name + "'");
		  return;
	  }
	  
	  var address = "";
	  
	  if (place.address_components) {
		  address = [
			(place.address_components[0] && place.address_components[0].short_name) || "",
			(place.address_components[1] && place.address_components[1].short_name) || "",
			(place.address_components[2] && place.address_components[2].short_name) || ""
		  ].join(" ");
	  }
  });

  map = new google.maps.Map(document.getElementById('map'), mapOptions);
  map.slide = true;

  setMarkers(map, locations);
  infowindow = new google.maps.InfoWindow({
    content: "loading..."
  });
  google.maps.event.addListener(infowindow, 'closeclick', function () {
    infowindow.close();
  });
  slider = window.setTimeout(show, 3000);
}

function setInfo(marker) {
  var content =
    '<div class="profile-widget" style="width: 100%; display: inline-block;">' +
    '<div class="doc-img">' +
    '<a href="' + marker.profile_link + '" tabindex="0" target="_blank">' +
    '<img class="img-fluid " alt="' + marker.doc_name + '" src="' + marker.image + '">' +
    '</a>' +
    '</div>' +
    '<div class="pro-content">' +
    '<h3 class="title">' +
    '<a href="' + marker.profile_link + '" tabindex="0">' + marker.doc_name + '</a>' +
    '<i class="fas fa-check-circle verified"></i>' +
    '</h3>';
  if (marker.speciality) {
    content += '<p class="speciality">' + marker.speciality + '</p>';
  }
  if (marker.address || marker.next_available || marker.amount) {
    content += '<ul class="available-info">' +
      '<li><i class="fas fa-map-marker-alt conveythis-no-translate "></i> ' + marker.address + ' </li>' +
      '<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>' +
      '<li><i class="far fa-money-bill-alt"></i> ' + marker.amount + '</li>' +
      '</ul>';
  }
  content += '</div>' + '</div>';
  infowindow.setContent(content);
}

function setMarkers(map, markers) {
  for (var i = 0; i < markers.length; i++) {
    var item = markers[i];
    var latlng = new google.maps.LatLng(item.lat, item.lng);
    var marker = new google.maps.Marker({
      position: latlng,
      map: map,
      doc_name: item.doc_name,
      address: item.address,
      speciality: item.speciality,
      next_available: item.next_available,
      amount: item.amount,
      profile_link: item.profile_link,
      total_review: item.total_review,
      animation: google.maps.Animation.DROP,
      icon: icons[item.icons],
      image: item.image
    });
    bounds.extend(marker.position);
    markers[i] = marker;
    google.maps.event.addListener(marker, "click", function () {
      setInfo(this);
      infowindow.open(map, this);
      window.clearTimeout(slider);
    });
  }
  map.fitBounds(bounds);
  google.maps.event.addListener(map, 'zoom_changed', function () {
    // if (map.zoom > 16) map.slide = false;
  });
}

google.maps.event.addDomListener(window, 'load', initialize);