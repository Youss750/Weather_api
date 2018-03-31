var input = document.getElementById('autocomplete');
var options = {
	types: ['(cities)'],
	componentRestrictions: {country: 'fr'}
	
};

autocomplete = new google.maps.places.Autocomplete(input, options);

var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
	maxZoom: 18, attribution: '[insert correct attribution here!]' });

var clouds = L.OWM.clouds({showLegend: false, opacity: 0.5, appId: 'f2c246eeae237fa30976e8b726af9942'});
var city = L.OWM.current({intervall: 15, lang: 'fr'});

var map = L.map('map', { center: new L.LatLng(51.5, 10), zoom: 10, layers: [osm], preferCanvas: true });
var baseMaps = { "OSM Standard": osm };
var overlayMaps = { "Clouds": clouds, "Cities": city };
var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);