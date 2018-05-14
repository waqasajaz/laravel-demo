$(document).ready(function(){
    $hood = $("#hood_name").val();

    mapboxgl.accessToken = AccessToken;
    L.mapbox.accessToken = AccessToken;
    geocoder =  L.mapbox.geocoder('mapbox.places', { country:"Barcelona" });

    hoodMap = new mapboxgl.Map({
        container: 'map-hood',
        center: [2.1631004,41.376841],
        zoom: 10,
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1'
    });

    hoodMap.on("load", function(e){

        hoodMap.addSource('barcelona', {
            "type": "geojson",
            "data": "https://raw.githubusercontent.com/martgnz/bcn-geodata/master/area-estadistica/area-estadistica_geo.json"
        });

        hoodMap.addLayer({
            'id': 'barcelona',
            'type': 'fill',
            'source': "barcelona",
            'layout': {},
            'paint': {
                'fill-color': '#9B4599',
                'fill-opacity': 0.5
            },
            'filter':["==", "N_Barri", $hood]
        });

        fit_to_location($hood+", barcelona", hoodMap);

    });

    loadScript("satelite_hood");

});

function satelite_hood()
{
    $hood = $("#hood_name").val().trim();

    $.ajax({
        "url" : 'https://maps.googleapis.com/maps/api/geocode/json',
        "type" : "get",
        "data" : {
            address : $hood+", barcelona",
            key : googleapi_key
        },
        "success" : function(res){
            place = res.results[0].geometry.location;

            image_propery =  new google.maps.LatLng(place.lat, place.lng);

            var imagemap = new google.maps.Map(document.getElementById("satelite-hood"), {
                center: image_propery,
                mapTypeId: "satellite",
                streetViewControl: true,
                mapTypeControlOptions: {
                    style:google.maps.MapTypeControlStyle.DEFAULT,
                    mapTypeIds: ['satellite']
                },
                zoom:18
            });

            // Construct the polygon.

            var promise = $.getJSON("https://raw.githubusercontent.com/martgnz/bcn-geodata/master/area-estadistica/area-estadistica_geo.json"); //same as map.data.loadGeoJson();
            promise.then(function(data){
               features = data; //save the geojson in case we want to update its values

                var bounds = new google.maps.LatLngBounds();

                for(i in features.features)
                {
                    if(features.features[i].properties.N_Barri==$hood)
                    {
                        cordinates = features.features[i].geometry.coordinates[0];

                        for(j in cordinates)
                        {
                            var myPlace = new google.maps.LatLng(cordinates[j][1], cordinates[j][0]);
                            bounds.extend(myPlace);
                        }
                    }
                }

                imagemap.data.addGeoJson(features,{idPropertyName:"id"});

                var mapstyles = function(feature) {
                    visible = feature.getProperty('N_Barri').trim()==$hood?true:false;
                    console.log(feature.getProperty('N_Barri')+" : "+visible);

                     return {
                         strokeColor: '#7530B2',
                         strokeOpacity: 0.8,
                         strokeWeight: 2,
                         fillColor: '#7530B2',
                         fillOpacity: 0.35,
                         visible:visible
                     };

                };

                imagemap.data.setStyle(mapstyles);

                setTimeout(function(){
                    imagemap.fitBounds(bounds);
                }, 1000);

            });

        }
    });
}

