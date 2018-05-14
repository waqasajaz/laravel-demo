
const metersToPixelsAtMaxZoom = function(meters){
    return (meters / 0.075 / Math.cos(longitude * Math.PI / 180));
};

var currentRadius_school = parseFloat($("#searchrange_school").val());
var schooltype = $(".schooltype:checked").val();
$(document).ready(function(){

    $(".schooltype").click(function(){
        $("#school-map").next(".map-loader").show(0);
        schooltype = $(".schooltype:checked").val();
        getschools(schooltype);
    });

    $("#searchrange_school").change(function(){
        $("#school-map").next(".map-loader").show(0);
        currentRadius_school = $(this).val();
        currentRadius_school = parseFloat(currentRadius_school);

        schoolmap.setPaintProperty("areacircle", "circle-radius", {
            stops: [
                [0, 0],
                [20, metersToPixelsAtMaxZoom(currentRadius_school)]
            ],
            base: 2
        });

        schoolmap.setFilter("schools", ["<=", "distance", currentRadius_school]);

        getschools(schooltype);
    });

});


function createanalysSchool(){

    schoolmap = new mapboxgl.Map({
        container: 'school-map',
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1',
        zoom: 12,
        center: [latitude, longitude]
    });

    schoolmap.on("load", function(e){
        propertyMarker(schoolmap, "school-home");
        schoolmap.addControl(new mapboxgl.NavigationControl());

        schoolmap.addSource('nearest-place', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: []
            }
        });

        schoolmap.addLayer({
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

        schoolmap.addSource('schoolroute', {
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

        schoolmap.addLayer({
            "id": "schoolroute",
            "type": "line",
            "source": "schoolroute",
            "layout": {
                "line-join": "round",
                "line-cap": "round"
            },
            "paint": {
                "line-color": "#7530B2",
                "line-width":6
            }
        });

        bigcircle();

        schoolmap.addSource('schoolsource', {
            type: 'geojson',
            data: {
                type: 'FeatureCollection',
                features: []
            }
        });

        schoolmap.addLayer({
            id: 'schools',
            type: 'circle',
            source:'schoolsource',
            paint : {
                "circle-color": "#7530B2",
                "circle-radius": 8,
                "circle-stroke-width": 2,
                "circle-stroke-color": "#7530B2"
            },
            filter : ["<=", "distance", currentRadius_school]
        }, "nearest-place");

    });

    display_school_popup();
    get_route_to_school();
}

function display_school_popup()
{
    var popup = new mapboxgl.Popup({
        closeButton: false,
        closeOnClick: false
    });

    schoolmap.on('mouseenter', 'schools', function(e) {
        schoolmap.getCanvas().style.cursor = 'pointer';

        school_property = e.features[0].properties;
        popuphtml = '<div class="school_popup">' +
            '<span>' + school_property.name + '</span><br>' +
            meterConvert_school(school_property.distance) + ', <br>' + school_property.street + ' <br>' +
            school_property.city + ', ' + school_property.subcountry + ' ' +
            school_property.country + checkPhone_school(school_property.phone);

        popup.setLngLat(e.features[0].geometry.coordinates)
            .setHTML(popuphtml)
            .addTo(schoolmap);
    });

    schoolmap.on('mouseleave', 'schools', function() {
        schoolmap.getCanvas().style.cursor = '';
        popup.remove();
    });
}

function meterConvert_school(meter){
    if (meter <= 500){
        return (meter).toFixed(0)+' meter'
    } else {
        return (meter/1000).toFixed(2) +' km'
    }
}
function checkPhone_school(phone){
    if(phone!==null && phone!=='null'){
        return '<br>'+phone
    } else {
        return ''
    }
}
function bigcircle()
{
    schoolmap.addSource('areacircle', {
        "type": "geojson",
        "data": {
            "type": "Point",
            "coordinates": [latitude, longitude]
        }
    });

    schoolmap.addLayer({
        "id": "areacircle",
        "type": "circle",
        "source": "areacircle",
        "paint" : {
            "circle-color": "rgba(117, 48, 178, 0.5)",
            "circle-radius": {
                stops: [
                    [0, 0],
                    [20, metersToPixelsAtMaxZoom(currentRadius_school)]
                ],
                base: 2
            },
            "circle-stroke-width": 2,
            "circle-stroke-color": "#7530B2",
            "circle-pitch-scale":"viewport"
        }
    }, "homepmarker");

    getschools(schooltype);

}

function ini_schools()
{
    $("#target4 .map-loader").fadeIn(0);
    schoolmap.getSource('nearest-place').setData({
        type: 'FeatureCollection',
        features: []
    });

    schoolmap.getSource('schoolroute').setData({
        "type": "Feature",
        "properties": {},
        "geometry": {
            "type": "LineString",
            "coordinates": [
                [latitude,longitude]
            ]
        }
    });

}

function getschools(category)
{
    ini_schools();

    result = "";
    schools = {
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

                school = {
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

                schools.features.push(school);
            }
        },
        complete:function(){
            schoolmap.getSource('schoolsource').setData(schools);
            nearest_school();
        }
    });
}

function nearest_school()
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

    var nearest = turf.nearest(placefeatures, schools);

    if (nearest !== null) {
        schoolmap.getSource('nearest-place').setData({
            type: 'FeatureCollection',
            features: [nearest]
        });
    }

    $("#target4 .map-loader").fadeOut(500);
}

function get_route_to_school()
{
    schoolmap.on("click", "schools", function(e){

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
                schoolmap.getSource("schoolroute").setData(res.routes[0].geometry);
            }
        });

    });
}