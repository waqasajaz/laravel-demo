$(document).ready(function(){

    mapboxgl.accessToken = AccessToken;
    L.mapbox.accessToken = AccessToken;
    geocoder =  L.mapbox.geocoder('mapbox.places', { country:"Barcelona" });

    stateMap = new mapboxgl.Map({
        container: 'map-state',
        center: [2.1631004,41.376841],
        zoom: 13,
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1'
    });
    dist_name = $("#dist_name").val();

    stateMap.on("load", function(e){

        stateMap.addSource('barcelona', {
            "type": "geojson",
            "data": "https://raw.githubusercontent.com/martgnz/bcn-geodata/master/area-estadistica/area-estadistica_geo.json"
        });

        stateMap.addLayer({
            'id': 'barcelona',
            'type': 'fill',
            'source': "barcelona",
            'layout': {},
            'paint': {
                'fill-color': '#9B4599',
                'fill-opacity': 0.5
            },
            'filter':["==", "N_Distri", dist_name]
        });

        fit_to_location(dist_name+", barcelona", stateMap);

    });


});
