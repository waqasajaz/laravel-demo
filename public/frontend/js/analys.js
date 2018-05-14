var currentRadius_service = parseFloat($("#searchrange").val());
var servicetype = "";
var serviceColor = "";

$(document).ready(function(){

    $(".search_category").click(function(){
        $("#service-map").next(".map-loader").show(0);
        $(".search_category").removeClass("active");
        if($(this).attr("category") != servicetype)
        {
            $(this).addClass("active");
            servicetype = $(this).attr("category");
            serviceColor = $(this).data("color");
        }
        else
        {
            servicetype = "";
            serviceColor = "";
        }
        getServices(servicetype,serviceColor);
    });


    $("#searchrange").change(function(){
        $("#service-map").next(".map-loader").show(0);
        currentRadius_service = $(this).val();
        currentRadius_service = parseFloat(currentRadius_service);

        servicemap.setPaintProperty("areacircle", "circle-radius", {
            stops: [
                [0, 0],
                [20, metersToPixelsAtMaxZoom(currentRadius_service)]
            ],
            base: 2
        });

        servicemap.setFilter("services", ["<=", "distance", currentRadius_service]);

        getServices(servicetype);
    });

});


function createanalysService(){

    servicemap = new mapboxgl.Map({
        container: 'service-map',
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1',
        zoom: 12,
        center: [latitude, longitude]
    });

    servicemap.on("load", function(e){
        propertyMarker(servicemap, "service-home");
        servicemap.addControl(new mapboxgl.NavigationControl());

        servicemap.addSource('nearest-place', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: []
            }
        });

        servicemap.addLayer({
            id: 'nearest-place',
            type: 'circle',
            source: 'nearest-place',
            paint: {
                "circle-color": "#FF9700",
                "circle-radius": 8,
                "circle-stroke-width": 2,
                "circle-stroke-color": "#FF9700"
            }
        });
 
        servicemap.addSource('servicesource', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: []
            }
        });

        servicemap.addLayer({
            id: 'services',
            type: 'circle',
            source:'servicesource',
            paint : {
                "circle-color": $(".search_category").data('color'),
                "circle-radius": 8,
                "circle-stroke-width": 2,
                "circle-stroke-color": $(".search_category").data('color')
            },
            filter : ["<=", "distance", currentRadius_service]
        }, "nearest-place");

        servicemap.addSource('serviceroute', {
            "type": "geojson",
            "data": {
                "type": "Feature",
                "properties": {},
                "geometry": {
                    "type": "LineString",
                    "coordinates": [
                        [latitude,longitude]
                    ]
                }
            }
        });

        servicemap.addLayer({
            "id": "serviceroute",
            "type": "line",
            "source": "serviceroute",
            "layout": {
                "line-join": "round",
                "line-cap": "round"
            },
            "paint": {
                "line-color": "#7530B2",
                "line-width":6
            }
        });

        serviceCircle();

    });

    display_service_popup();
    get_route_to_service()

}

function display_service_popup()
{
    var popup = new mapboxgl.Popup({
        closeButton: false,
        closeOnClick: false
    });

    servicemap.on('mouseenter', 'services', function(e) {
        servicemap.getCanvas().style.cursor = 'pointer';

        service_property = e.features[0].properties;
        popuphtml = '<div class="service_popup">' +
            '<span>' + service_property.name + '</span><br>' +
            meterConvert_service(service_property.distance)+ ', <br>' + service_property.street + ' <br>' +
            service_property.city + ', ' + service_property.subcountry + ' ' +
            service_property.country + checkPhone_service(service_property.phone);

        popup.setLngLat(e.features[0].geometry.coordinates)
            .setHTML(popuphtml)
            .addTo(servicemap);
    });

    servicemap.on('mouseleave', 'services', function() {
        servicemap.getCanvas().style.cursor = '';
        popup.remove();
    });
}

function meterConvert_service(meter){
    if (meter <= 500){
        return (meter).toFixed(0)+' meter'
    } else {
        return (meter/1000).toFixed(2) +' km'
    }
}
function checkPhone_service(phone){
    if(phone!==null && phone!=='null'){
        return '<br>'+phone
    } else {
        return ''
    }
}

function serviceCircle()
{
    servicemap.addSource('areacircle', {
        "type": "geojson",
        "data": {
            "type": "Point",
            "coordinates": [latitude, longitude]
        }
    });

    servicemap.addLayer({
        "id": "areacircle",
        "type": "circle",
        "source": "areacircle",
        "paint" : {
            "circle-color": "rgba(117, 48, 178, 0.5)",
            "circle-radius": {
                stops: [
                    [0, 0],
                    [20, metersToPixelsAtMaxZoom(currentRadius_service)]
                ],
                base: 2
            },
            "circle-stroke-width": 2,
            "circle-stroke-color": "#7530B2"
        }
    }, "homepmarker");

    getServices(servicetype);

}

function ini_service()
{
    servicemap.getSource('nearest-place').setData({
        type: 'FeatureCollection',
        features: []
    });
    servicemap.getSource('servicesource').setData({
        type: 'FeatureCollection',
        features: []
    });

    $("#target5 .map-loader").fadeOut(500);
    return true;
}

function getServices(category,category_color)
{
    servicemap.getSource('serviceroute').setData({
        "type": "Feature",
        "properties": {},
        "geometry": {
            "type": "LineString",
            "coordinates": [
                [latitude,longitude]
            ]
        }
    });

    if(category.trim() == "")
    {
        ini_service();
        return true;
    }

    $("#target5 .map-loader").fadeIn(0);
    servicemap.getSource('nearest-place').setData({
        type: 'FeatureCollection',
        features: []
    });

    result = "";
    services = {
        type: 'FeatureCollection',
        features:[]
    };

    $.ajax({
        url:basepath+"/search/category",
        type:"post",
        data:{
            "_token":$('input[name="_token"]').val(),
            "category":category,
            "latitude":longitude,
            "longitude":latitude
        },
        success:function(res){
            res = jQuery.parseJSON(res);
            for(i in res)
            {
                place = res[i];

                service = {
                    "type":"Feature",
                    "properties":{
                        "phone" : place.name,
                        "name" : place.name,
                        "street" : place.location.address1,
                        "city" : place.location.city,
                        "subcountry" : place.location.country,
                        "country" : "Spain",
                        "distance" :place.distance
                    },
                    "geometry":{
                        "type":"Point",
                        "coordinates":[place.coordinates.longitude, place.coordinates.latitude]
                    }
                };
       
            
                 servicemap.addLayer({
                    id: 'services',
                    type: 'circle',
                    source:'servicesource',
                    paint : {
                        "circle-color": category_color,
                        "circle-radius": 8,
                        "circle-stroke-width": 2,
                        "circle-stroke-color": category_color
                    },
                    filter : ["<=", "distance", currentRadius_service]
                }, "nearest-place");
                
                services.features.push(service);
            }
        },
        complete:function(){
            servicemap.getSource('servicesource').setData(services);
            nearest_service();
        }
    });
}

function nearest_service()
{
    var placefeatures = {
        "type": "Feature",
        "properties": {
            "marker-color": "#0f0"
        },
        "geometry": {
            "type": "Point",
            "coordinates": [latitude, longitude]
        }
    };

    var nearest = turf.nearest(placefeatures, services);

    if (nearest !== null) {
        servicemap.getSource('nearest-place').setData({
            type: 'FeatureCollection',
            features: [nearest]
        });
    }

    $("#target5 .map-loader").fadeOut(500);
}

function get_route_to_service()
{
    servicemap.on("click", "services", function(e){

        //https://api.mapbox.com/directions/v5/mapbox/cycling/-84.518641,39.134270;-84.512023,39.102779?geometries=geojson

        dlatitude = e.lngLat.lng;
        dlongitude = e.lngLat.lat;

        $.ajax({
            url : 'https://api.mapbox.com/directions/v5/mapbox/cycling/'+latitude+','+longitude+';'+dlatitude+','+dlongitude,
            type:"get",
            data:{
                access_token:AccessToken,
                steps:true,
                geometries:"geojson"
            },
            success:function(res){
                servicemap.getSource("serviceroute").setData(res.routes[0].geometry);
            }
        });

    });
}