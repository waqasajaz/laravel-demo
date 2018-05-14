
jQuery(document).ready(function () {
    $('.timepicker').wickedpicker();
    var dateToday = new Date();

    'use strict';

    jQuery('#filter-date, #search-from-date').datetimepicker(
        {
            format:'Y-m-d',
            minDate: dateToday,
            timepicker:false,

        }
    );

    jQuery('#search-to-date').datetimepicker({
        format:'Y-m-d',
        minDate: dateToday,
        timepicker:false,
        beforeShowDay: function(date) {
            /*
            var schedules = $("#shcedules").val();

            if(schedules != "")
            {
                var availableDates = schedules.split(",");
                console.log(availableDates);
                var dmy = date.getDate() + "-" + (date.getMonth()+1) + "-" + date.getFullYear();
                if ($.inArray(dmy, availableDates) != -1) {
                    return [true, "","Available"];
                } else {
                    return [false,"","unAvailable"];
                }
            }
            */
        }
    });


});
var form = $("#visitor_feedback").show();

form.steps({
    headerTag: "h3",
    bodyTag: "div",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Forbid next action on "Warning" step if the user is to young
        if (newIndex === 3 && Number($("#age-2").val()) < 18)
        {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        form.validate().settings.ignore = ":disabled,:hidden";
        return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
        {
            form.steps("next");
        }
        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
        if (currentIndex === 2 && priorIndex === 3)
        {
            form.steps("previous");
        }
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        $.ajax({
            url: $("#visitor_feedback").attr("action"),
            type: 'POST',
            data: $('#visitor_feedback').serialize(),
            success: function (res) {
                res = $.parseJSON(res);

                if(res.status == 200)
                {
                    $("#visitor_feedback")[0].reset();
                }
                display_alert(res.status, res.message, true);
            }
        });
    }
}).validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    rules: {

    }
});

$( "#visitor_feedbacks" ).validate({
    rules: {
        name:"required",
        email:"required",
        phoneno:"required",
        get_message:"required"
    },
    submitHandler:function(form)
    {
        $.ajax({
            url: $("#visitor_feedback").attr("action"),
            type: 'POST',
            data: $('#visitor_feedback').serialize(),
            success: function (res) {
                res = $.parseJSON(res);

                if(res.status == 200)
                {
                    $("#visitor_feedback")[0].reset();
                }
                display_alert(res.status, res.message, true);
            }
        });
    }
});


// ==========================================   MAP FUNCTIONS START ==================================================
var direccion           = $("#direccion").val();
var provincia           = $("#provincia").val();
var rooms               = $("#rooms").val();
var bathrooms           = $("#bathrooms").val();
var price               = $("#price").val();
var comunidad_autonoma  = $("#comunidad_autonoma").val();
var cops                = $("#cops").val();
var routedirection      = "default";
var agerange            = 100;
var economy             = [];
var markers             = [];
var latitude            = $("#latitude").val();
var longitude           = $("#longitude").val();
var panorama            = "";
var panoramacenter      = "";
var schools = [];
var directions = "";

var demograph = {
    "type": "FeatureCollection",
    "features" : []
};

var insamearea = {
    "type": "FeatureCollection",
    "features" : []
};

var buildmap , infosectionMap = servicemap = buildmap = imagemap = schoolmap = averagepricemap = "";

var schooltype = $(".schooltype:checked").val();

var categorylogdata = {
    "category"      : "",
    "logtype"       : "",
    "categoryflag"  : "",
    "logmessage"    : "",
    "property_id"   : $("#property_id").val(),
    "property_from" : "retailer",
    "_token"        : $("input[name='_token']").val()
};

$(document).ready(function(){

    $("#compare_distance").change(function(){get_compares();});

    avarage_price_chart();
    $("#service_tab .infosection__tab").click(function(){
        $tab = $(this).text();
        $.ajax({
            url:basepath+"/log/tablogs",
            type:"post",
            data:{
                "property" :$("#property_id").val(),
                "tab" : $tab,
                "_token" : $("input[name='_token']").val()
            },
            success:function(res){
                res = jQuery.parseJSON(res);
            }
        });
    });

    mapboxgl.accessToken = AccessToken;
    L.mapbox.accessToken = AccessToken;
    geocoder =  L.mapbox.geocoder('mapbox.places', { country:"Barcelona" });

    infosectionMap = new mapboxgl.Map({
        container: 'transportation-map',
        center: [latitude,longitude],
        zoom: 13,
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1'
    });

    infosectionMap.addControl(new mapboxgl.NavigationControl());
    infosectionMap.scrollZoom.disable();
    infosectionMap.dragRotate.disable();
    infosectionMap.addControl(new MapboxTraffic());

    infosectionMap.on("load", function(){

        propertyMarker(infosectionMap, "transport-home");
        get_transport();

        routepath = {
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
        };

        infosectionMap.addSource('route', routepath);

        infosectionMap.addLayer({
            "id": "route",
            "type": "line",
            "source": "route",
            "layout": {
                "line-join": "round",
                "line-cap": "round"
            },
            "paint": {
                "line-color": "#7530B2",
                "line-width":6
            }
        });

        $("#transport-change").removeAttr("disabled");

        $("#transport-change").click(function(){
            routedirection = (routedirection == "default")?"backward":"default";

            routeorigin = $("#start").val();
            routedestingation = $("#end").val();

            $("#start").val(routedestingation);
            $("#end").val(routeorigin);

            route_direction();
        });

        $("#get_stop").change(function(){
            get_transport();
        });

    });

    allcategories();
    basic_function();
    loadScript("googlemapfunction");

});


function googlemapfunction()
{
 
  var  panoramacenter =  new google.maps.LatLng(longitude,latitude);

  var  panorama = new google.maps.Map(document.getElementById('street-map'),
        {
            position: panoramacenter,
            pov: {heading: 165, pitch: 0},
            zoom: 1
        });
 
 
    panorama.setCenter(panoramacenter);

    panorama = new google.maps.StreetViewPanorama(
        document.getElementById('street-map'),
        {
            position: panoramacenter,
            pov: {heading: 165, pitch: 0},
            zoom: 1
        });
   
       
         /*
        google.maps.event.addListener(panorama, 'click', function(event) {
             alert();

            geocoder.geocode({
            'latLng': event.latLng
          }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if (results[0]) {
                alert(results[0].formatted_address);
              }
            }
          }); 

        });*/
    
}

function propertyMarker(markermap, markerid)
{
    markermap.setCenter([latitude, longitude]);

    markermap.addSource('homemarker', {
        "type": "geojson",
        "data": {
            "type": "Point",
            "coordinates": [latitude,longitude]
        }
    });

    markermap.addLayer({
        "id": "homepmarker",
        "type": "circle",
        "source": "homemarker",
        "filter": ["!has", "point_count"],
        "paint" : {
            "circle-color": "#00AB8A",
            "circle-radius": 9,
            "circle-stroke-width": 2,
            "circle-stroke-color": "#048169"
        }
    });

}

var stopsobj = [];
var transmarkers = [];
function get_transport()
{
    $("ol.transport__list").html("");
    stopsobj = [];

    if(transmarkers.length != 0)
    {
        for(i in transmarkers)
        {
            transmarkers[i].remove();
        }
        transmarkers = [];
    }

    stoptype = $("#get_stop").val();
    stopnames = $("#get_stop option:selected").data("category");

    $.ajax({
        url: "https://api.foursquare.com/v2/venues/explore",
        type: "get",
        data: {
            ll : longitude+","+latitude,
            query : stopnames,
            limit : 10,
            categoryId: stoptype,
            oauth_token:"CEMNVOMCDY55QMLPRWP30UTDAQ2YUBWZH4NQ4KM0BNQKD431",
            v:"20171011"
        },
        success: function (res) {

            foundstops = res.response.totalResults;

            if(foundstops > 0)
            {
                busstops = res.response.groups[0].items;

                for(i in busstops)
                {
                    var place = busstops[i].venue;

                    busormetro = (place.categories[0].shortName.toLowerCase().indexOf("bus") !== -1)?"bus":"metro";

                    stopsobj.push({
                        "id":busstops[i].venue.id,
                        "name" : busstops[i].venue.name,
                        "location" : (busstops[i].venue.location.hasOwnProperty("address")?busstops[i].venue.location.address+', ':''),
                        "distance" : busstops[i].venue.location.distance,
                        "lat":busstops[i].venue.location.lat,
                        "lng":busstops[i].venue.location.lng,
                        "data":busstops[i].venue,
                        "category" : busormetro
                    });

                    transport_category = (busormetro == "bus")?"marker-violet":"marker-violet";

                    var el = document.createElement('div');
                    el.className = "marker-transport "+transport_category;
                    el.id = place.id;
                    stopmarker = new mapboxgl.Marker(el).setLngLat([place.location.lng, place.location.lat]);

                    var block = '<div class="col-xs-12 plaseinfo">';
                    block += '<div class="results">';
                    block += '<div class="place_details">';
                    block += '<div class="placename">';
                    block += '<a href="javascript:void(0)" class="property_name">'+place.name+'</a>';
                    block += '<div class="clearfix"></div>';
                    if(place.location.hasOwnProperty('address'))
                    { block += '<span class="address">'+place.location.address+','+place.location.city+'</span>'; }
                    block += '</div>';
                    block +=  '<div class="placemeta">';
                    block += '<span class="amount">'+((place.location.distance.toFixed(2)))+' (Meter)</span>';
                    block += '</div>';
                    block += '</div>';
                    block += '</div>';
                    block += '</div>';
                    block += '</div>';

                    var popup = new mapboxgl.Popup().setHTML(block);
                    stopmarker.setPopup(popup);

                    transmarkers.push(stopmarker);

                    stopmarker.addTo(infosectionMap);

                }
            }
        },
        complete:function(){
            showtransport();

            $("li.transport__num").click(function(){
                $("#transportation-map").next(".map-loader").show(0);
                $("li.transport__num").removeClass("active");
                $(this).addClass("active");
                route_direction();
            });

            $("#stop_0").trigger("click");
        }
    });

}

function route_direction()
{
    var routefor =  $("li.transport__num.active");
    var busstop = routefor.attr("route");
    origin = [latitude,longitude];
    destination = [stopsobj[busstop].lng,stopsobj[busstop].lat];

    if(routedirection == "default")
    { $("#end").val(stopsobj[busstop].name); }
    else
    { $("#start").val(stopsobj[busstop].name); }

    get_direction_stop(origin, destination,routefor);
}

function showtransport()
{

    for(i in stopsobj)
    {
        var routedetail = '<li class="transport__num '+stopsobj[i].category+'" route="'+i+'" id="stop_'+i+'" data-transport="'+stopsobj[i].id+'">'+
                '<div class="transport__accord">'+
                '<div class="transport__head">'+
                '<div class="transport__name">'+stopsobj[i].name+' ('+stopsobj[i].distance+' Meter)</div>'+
                '</div>'+
                '<div class="transport__info"></div>'+
                '</div>'+
                '</li>';
        $("ol.transport__list").append(routedetail);
    }

}

function allcategories()
{
    $(".search_category").each(function(){
        markers[$(this).attr('category')] = [];
    });
}


function search_category(term)
{
    $.ajax({
        url:basepath+"/search/category",
        type:"post",
        data:{
            "_token":$('input[name="_token"]').val(),
            "category":term,
            "latitude":longitude,
            "longitude":latitude
        },
        success:function(res){
            res = jQuery.parseJSON(res);
            for(i in res)
            {
                place = res[i];
                categoryMark(place, term);
            }
        }
    });
}


function categoryMark(data, category)
{

    var el = document.createElement('div');
    el.className = "marker marker-"+category.split(" ").join("_").toLowerCase();
    marker = new mapboxgl.Marker(el).setLngLat([data.coordinates.longitude, data.coordinates.latitude]);

    info = placeinfo(data);
    var popup = new mapboxgl.Popup().setHTML(info);
    marker.setPopup(popup);
    markers[category].push(marker);

    marker.addTo(servicemap);

}

function placeinfo(place)
{
    var block = '<div class="col-xs-12 plaseinfo">';
    block += '<div class="results">';
    block += '<div class="place_details">';
    block += '<div class="placename">';
    block += '<a href="javascript:void(0)" class="property_name">'+place.name+'</a>';
    block += '<div class="clearfix"></div>';
    if(place.location.hasOwnProperty('address1'))
    { block += '<span class="address">'+place.location.address1+','+place.location.city+'</span>'; }
    block += '</div>';
    block +=  '<div class="placemeta">';
    block += '<span class="amount">'+((place.distance.toFixed(2)))+' (Meter)</span>';
    block += '</div>';
    block += '</div>';

    block += '</div>';
    block += '</div>';
    block += '</div>';

    return block;
}

function removeCategory(category)
{
    marks = markers[category];

    for(i in marks)
    {
        marks[i].remove();
    }
}

function avgPricemap()
{
    averagepricemap = new mapboxgl.Map({
        container: 'averageprice-map',
        center: [latitude,longitude],
        zoom: 13,
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1'
    });

    averagepricemap.on("load", function(){
        averagepricemap.addControl(new mapboxgl.NavigationControl());
        propertyMarker(averagepricemap, "average-price-marker");
        averagepricemap.addSource('nearby', {
            "type": "geojson",
            "data": insamearea
        });

        averagepricemap.addLayer({
            id: 'nearby-property',
            type: 'circle',
            source: 'nearby',
            'layout': {
                'visibility': 'visible'
            },
            'paint': {
                'circle-radius': 8,
                'circle-color': 'rgba(117, 48, 178, 1)'
            }
        });

        averagepricemap.addLayer({
            "id": "average-price-layer",
            "type": "symbol",
            "source": "nearby",
            "layout": {
                "icon-image": "circle-15",
                "text-field": "€{price}",
                "text-offset": [0, 0.6],
                "text-anchor": "top",
                "text-size":14
            },
            "paint":{
                "icon-opacity":0,
                "text-halo-color": "#FFF",
                "text-halo-width": 5
            }
        });

        var popup = new mapboxgl.Popup();

        averagepricemap.on('mouseenter', 'nearby-property', function (e) {
            popup.setLngLat(e.features[0].geometry.coordinates)
                .setHTML(e.features[0].properties.description)
                .addTo(averagepricemap);
        });

       $("#averageprice-map").on("mouseleave", function(){
            popup.remove();
       });

        nearbyproperty();

        $("#nearby").change(function(){
            nearbyproperty();
        });

        $(".property_deal").click(function(){
            $(".property_deal").removeClass("active");
            $(this).addClass("active");

            nearbyproperty();
        });

    });

}

function nearbyproperty()
{
    $("#target3 .map-loader").fadeIn(0);
    var options = {
        "property_deal" : $(".property_deal.active").data("value"),
        "property":$("#property_id").val(),
        "nearby":$("#nearby").val(),
        "_token":$("input[name='_token']").val()
    };

    response = false;
    insamearea.features = [];

    $.ajax({
        url:basepath+"/property/nearby",
        type:"post",
        data:options,
        success:function(res) {
            response = jQuery.parseJSON(res);
        },
        complete:function(){
            if(response != false)
            {
                for(i in response)
                {
                    place = response[i];
				
                var block  = '<div class="plaseinfo nearbypopup">';
                    block += '<div class="results">';
                    block += '<div class="place_details">';
                    block += '<div class="popup_content">';
                    block += '<p class="pdetails_area_width" ><a href="javascript:void(0)" class="property_name">'+place.direccion+'</a></p>';
                    block += '<span class="amount">&euro;'+((place.price.toFixed(2)))+' ('+(place.sizem2)+'m<sup>2</sup>)<br>';
                    block += '</div>';
					block += '</div>';
                    block += '</div>';
                    block += '</div>';
                    
				    var point = {
                        type: 'Feature',
                        geometry: {
                            type: 'Point',
                            coordinates: [place.latitude, place.longitude]
                        },
                        properties: {
                            title: place.direccion,
                            description: block,
                            price:place.price.toFixed(2)
                        }
                    };

                    insamearea.features.push(point);
                }

                averagepricemap.getSource("nearby").setData(insamearea);

                setTimeout(function(){
                    $("#target3 .map-loader").fadeOut(500);
                });
            }
            else {

            }
        }
    });
}

function basic_function()
{

    $('button[data-target="#target3"]').click(function(){
        if($("#averageprice-map").html().trim() == "")
        {
            setTimeout(function(){
                avgPricemap();
            },1000);
        }
    });

    $('button[data-target="#target4"]').click(function(){
        if($("#school-map").html().trim() == "")
        {
            setTimeout(function(){
                createanalysSchool();
            },1000);
        }
    });


    $('button[data-target="#target5"]').click(function(){
        if($("#service-map").html().trim() == "")
        {
            setTimeout(function(){
                createanalysService();
            },1000);
        }
    });

    $('button[data-target="#target6"]').click(function(){
        if($("#satelite-map").html().trim() == "")
        {
            setTimeout(function(){ google_image(); },1000);
        }
    });

    $('button[data-target="#target7"]').click(function(){
        if($("#buildings").html().trim() == "")
        {
            setTimeout(function(){
                buildmap = new mapboxgl.Map({
                    container: 'buildings',
                    center: [latitude, longitude], // starting position
                    zoom: 16, // starting zoom
                    style: 'mapbox://styles/mapbox/light-v9',
                    pitch: 45,
                    bearing: -17.6,
                });

                buildmap.addControl(new mapboxgl.NavigationControl());
                buildmap.scrollZoom.disable();

                viewbuildings();
                propertyMarker(buildmap, "building-home");
            },1000);


        }
    });

    mapposition = $("#service_tab").position();

    $(window).scroll(function() {
        mapheight = $(".infosection").height();
        if($(window).scrollTop() >= (mapposition.top-30) && ($(window).scrollTop() <= (mapposition.top+mapheight)))
        {
            $(".infosection__tabs-fixed").fadeIn(500);
        }
        else {
            $(".infosection__tabs-fixed").fadeOut(0);
        }
    });
}

function google_image()
{
    image_propery =  new google.maps.LatLng(longitude,latitude);
    var imagemap = new google.maps.Map(document.getElementById('satelite-map'), {
        center: image_propery,
        mapTypeId: "satellite",
        mapTypeControl: true,
        mapTypeControlOptions: {
            style:google.maps.MapTypeControlStyle.DEFAULT,
            mapTypeIds: ['satellite']
        },
        streetViewControl: true,
        zoom:18
    });

    var cityCircle = new google.maps.Circle({
        strokeColor: '#486DE0',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#486DE0',
        fillOpacity: 0.35,
        map: imagemap,
        center: image_propery,
        radius: 50
    });


}


function viewbuildings()
{
    buildmap.on('load', function() {
        buildmap.addLayer({
            'id': '3d-buildings',
            'source': 'composite',
            'source-layer': 'building',
            'filter': ['==', 'extrude', 'true'],
            'type': 'fill-extrusion',
            'minzoom': 2,
            'paint': {
                'fill-extrusion-color': '#7530B2',
                'fill-extrusion-height': {
                    'type': 'identity',
                    'property': 'height'
                },
                'fill-extrusion-base': {
                    'type': 'identity',
                    'property': 'min_height'
                },
                'fill-extrusion-opacity': .6
            }
        });
    });
}


function get_direction_stop(originpoint,destinationpoint,routefor)
{

    homemarker = $("#transport-home");
    stopmarker = $(".transport__num.active").data("transport");

    $(".marker-transport").removeClass("marker-home marker-red").addClass("marker-red");

    if(routedirection == "default")
    {
        origin = originpoint.toString();
        destination = destinationpoint.toString();

        homemarker.removeClass("marker-red").addClass("marker-home");
    }
    else {

        homemarker.removeClass("marker-home").addClass("marker-red");
        $("#"+stopmarker).removeClass("marker-red").addClass("marker-home");

        origin = destinationpoint.toString();
        destination = originpoint.toString();
    }


    var pathroute = "";

    $.ajax({
        url:"https://api.mapbox.com/directions/v5/mapbox/walking/"+origin+";"+destination+".json",
        type:"get",
        data:{
            "access_token":AccessToken,
            "steps":true,
            "geometries":"geojson"
        },
        success:function(res){
            pathroute = res.routes[0];
        },
        complete:function(){

            var routefor =  $("li.transport__num.active");
            var busstop = routefor.attr("route");
            paintcolor = stopsobj[busstop].category == "bus"?"#7530B2":"#7530B2";

            infosectionMap.setPaintProperty("route", 'line-color', paintcolor);
            infosectionMap.getSource('route').setData(pathroute.geometry);

            $("#transportation-map").next(".map-loader").fadeOut(1000);

            routesteps = pathroute.legs[0].steps;
            var instruction = "";
            for(i in routesteps)
            {
                var step = routesteps[i];

                dist_time = step.maneuver.instruction+" "+step.distance+' Meter '+Math.round(parseFloat(step.duration)/60)+' mins';

                if(step.maneuver.type != "arrive")
                {
                    instruction += '<div class="transport__line">'+
                        '<span class="transport__icons">'+
                        '<img src="https://maps.gstatic.com/mapfiles/transit/iw2/6/walk.png" class="transport__image">'+
                        '</span> '+dist_time+
                        '</div>';
                }
            }

            $(".transport__info",  routefor).html(instruction);

        }
    });
}

function avarage_price_chart()
{
    var config = {
        type: 'line',
        data: {
            labels: ["_2016Q1","_2016Q2","_2016Q3","_2016Q4","_2017Q1","_2017Q2","_2017Q3","_2017Q4"],
            datasets: [{
                label: 'Filled',
                backgroundColor: 'rgba(255,151,0,1)',
                borderColor: 'rgba(255,151,0,1)',
                data: [80000, 100000, 90000, 120000, 100000, 300000, 500000, 400000],
                fill: false,
                pointBackgroundColor:"#00AB8A"
            }]
        },
        options: {
            legend: {
                display: false
            },
            responsive: true,
            title: {
                display: false,
                text: 'Average Price'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                        labelString: 'Month'
                    },
                    ticks: {
                        autoSkip: false
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: false,
                        labelString: 'Price'
                    },
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    };

    var average_price_graph = document.getElementById('average_price_chart').getContext('2d');
    var chart_average_price = new Chart(average_price_graph, config);

    $pricedata = {
        typerent:[
            [646.487126308,653.5325517895,661.4056178917,670.1342990296,679.7498587831,690.2870364263,701.7842559253,714.2838591597],
            [882.121261113,913.7042465865,948.7534361889,987.5780872207,1030.5282296581,1077.9998634941,1130.4409553472,1188.3583599786],
            [1089.5942387844,1134.3956411984,1184.1129947517,1239.2262163014,1300.2800073995,1367.8926778203,1442.7663739804,1525.6989454442],
            [1203.1853290838,1258.8347650901,1320.8501122884,1389.9108824924,1466.7935171682,1552.3856410154,1647.7027285931,1753.9076139832],
            [1425.3014355691,1491.0428054851,1564.3897278492,1646.1570063211,1737.2767559811,1838.8157644049,1951.995817536,2078.2175227298],
            [2103.4377264046,2187.5819569197,2280.4627351362,2382.8988108662,2495.8138747503,2620.250197487,2757.3843322418,2908.5452060668]
        ],
        typesale:[
            [290269.251749241,309886.741732149,332100.618041186,357273.741296949,385831.105884818,418271.335981144,455180.548868812,497249.107967577],
            [212883.862425868,219459.325764366,226800.916370512,234971.416091308,244042.085931795,254093.694311607,265217.704353845,277517.644448414],
            [252027.254785949,260716.066327928,270426.543876347,281249.706294329,293289.202264521,306662.949118399,321505.034779868,337967.925354622],
            [289463.988260133,299205.576544846,310083.995239954,322198.522010962,335662.065119635,350602.901523655,367166.690587744,385518.807248673],
            [411295.227417172,422290.954826631,434531.321790958,448106.859720145,463119.743550482,479685.066335761,497932.299173488,518006.962233926],
            [660806.299460471,673656.863751573,688115.774091237,704275.365820985,722240.25722062,742128.476016212,764072.747858449,788221.966411495]
        ]
    };

    $(".filter_average_price").click(function(){
        $bathtype = $(this).val();
        $property_deal = $("#property_deal").val().toLowerCase();
        config.data.datasets[0].data = $pricedata["type"+$property_deal][$bathtype];
        chart_average_price.update();
    });
}

function get_compares()
{
    if($(".compare__slider").html().trim() != "")
    {
        $(".compare__slider").slick('unslick');
    }
    $(".compare__slider").html("");
    $.ajax({
        url:basepath+"/property/compare",
        type:"POST",
        data:{
            "_token":$('input[name="_token"]').val(),
            "property":$("#property_id").val(),
            "distance":$("#compare_distance").val()
        },
        success:function(res){
            res = jQuery.parseJSON(res);
            if(res != false)
            {
                $(".compare__slider").html(res);
            }
        },
        complete:function(){
            if($(".compare__slider").html().trim() != "")
            {
                $(".compare__slider").slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    slide: '.nearby__item',
                    prevArrow: false,
                    nextArrow: false
                });
            }
        }
    });
}