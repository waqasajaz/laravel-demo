var lat = 41.32651488;
var lng = 2.049775145;
var page = 1; nxt = 4, prv = 0;
var flagslide = false;
var features = "";
var property_type = [];
var rooms=[];
var wishlist = [];
var newwishlist = [];
var clusters = {
    "type": "FeatureCollection",
    "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
    "features" : []
};
var clusterflag = true;

var Searchquery = {
    "search" : "BARCELONA",
    "zipcode" : "",
    "provincia" : "Barcelona",
    "page" : page,
    "_token": $("input[name='_token']").val(),
    "type"  : "",
    "min_price" : "",
    "max_price" : "",
    "rooms"     : rooms,
    "bathrooms" : [],
    "searchin"  : "area",
    "property_type" : property_type,
    "features"  : features,
    "min_size"	: "",
    "max_size"	: "",
    "sort_by"	: $("#sort_filter").val(),
    "limit"     : 10
};

var map = "";
var usermarker = "";

$(document).ready(function(){
    setpricerange();
	 $(".slideToTop .features,.slideToTop .checkbox .room_filter,.slideToTop .checkbox .property_type_filter").click(function () {
		  $("html, body").animate({ scrollTop: 0 }, "fast");
     });
	 $(".filter__between-item .custom-select").change(function () {
		 $("html, body").animate({ scrollTop: 0 }, "fast");
     });
	 
    mapboxgl.accessToken = AccessToken;
    create_map();

    $("#filteredResults").html($(".loader-container").html());
    common_functions();
    sliderfunction();

    $('.save_wishlist_block').magnificPopup({
        items: {
            src: '#save-to-collection',
            type: 'inline'
        }
    });

    $(".save_wishlist_block").click(function(){
        if(newwishlist.length > 0)
        {
            $("#collect_property").val(newwishlist.toString());
        }
    });

});

function create_map()
{
    map = new mapboxgl.Map({
        container: 'retailer_map',
        center: [lng,lat],
        zoom: 11,
        style: 'mapbox://styles/david2681/cj251ctkr000w2roux5glfig1',
        trackResize:true
    });

    map.on("load", function(){
        map.addControl(new mapboxgl.FullscreenControl());

        $(".mapboxgl-ctrl-fullscreen").click(function(){
            map.resize();
        });
        get_user_wishlist();


        map.addSource('barcelona', {
            "type": "geojson",
            "data": "https://raw.githubusercontent.com/martgnz/bcn-geodata/master/area-estadistica/area-estadistica_geo.json"
        });

        map.addLayer({
            'id': 'barcelona',
            'type': 'fill',
            'source': "barcelona",
            'layout': {
                "visibility":"none"
            },
            'paint': {
                'fill-color': '#9B4599',
                'fill-opacity': 0.5
            }
        });

    });

}

function init()
{
    $("#pages").html("");
    $("#results").text("Searching ...");
    page = 1; nxt = 4, prv = 0;
    Searchquery.property_type=[];
    Searchquery.rooms=[];
    Searchquery.bathrooms = [];
    Searchquery.features = [];
}

function createSearchQuery()
{
    Searchquery.search      = $("#mapbox_search").val();
    Searchquery.sort_by		= $("#sort_filter").val();
    Searchquery.min_price   = $("#minprice").val();
    Searchquery.max_price   = $("#maxprice_input").val();
    Searchquery.min_size    = $("#minsize").val();
    Searchquery.max_size    = $("#maxsize").val();
    Searchquery.page        = $("#pages a.active").text();
    Searchquery.type        = $("#filter_for").val();
    Searchquery.searchin    = (Searchquery.search != "")?Searchquery.searchin:"listing";

    if(Searchquery.page=='' || Searchquery.page==undefined){
        Searchquery.page=1;
    }

    $(".property_type_filter").each(function(){
        if($(this).prop('checked'))
        {
            Searchquery.property_type.push($(this).val());
        }
    });

    $(".room_filter").each(function(){
        if($(this).prop('checked'))
        {
            Searchquery.rooms.push($(this).val());
        }
    });

    $(".bath_filter").each(function(){
        if($(this).prop('checked'))
        {
            Searchquery.bathrooms.push($(this).val());
        }
    });


    $(".features").each(function(){
        if($(this).prop('checked'))
        {
            Searchquery.features.push($(this).val());
        }
    });
}

function FilterProperties()
{
    createSearchQuery();
    $("#filteredResults").html($(".loader-container").html());
    $("#pages").html("");

    if(Searchquery.searchin == "district")
    {
        map.setFilter('barcelona',["==", "N_Distri", Searchquery.search]);
        map.setLayoutProperty("barcelona", 'visibility', 'visible');
    }
    else if(Searchquery.searchin == "hood_table")
    {
        map.setFilter('barcelona',["==", "N_Barri", Searchquery.search]);
        map.setLayoutProperty("barcelona", 'visibility', 'visible');
    }
    else {
        map.setLayoutProperty("barcelona", 'visibility', 'none');
    }

    $.post(basepath + "/retailer/properties", Searchquery, function (response, status, xhr) {
        prop = jQuery.parseJSON(response);
        showResult(prop);
    });
}

var geojson = {
    type: 'FeatureCollection',
    features: []
};
var property_markers = [];

function showResult(prop)
{
    clusters.features   = [];
    property_markers    = [];

    if (prop != false) {

        $("#results").text(prop.total+" Properties found");

        var props = prop.details.data;

        $("#filteredResults").html("");

        for (i in props) {
            showProperties(props[i]);
        }

        add_to_wishlist();

        $('.open_colleciton_popup').magnificPopup({
            type:'inline',
            midClick: true
        });

        if ($('.card__slider').length < 1){
            return false;
        }else{
            $('.card__slider').each(function() {
                $(this).slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    slide: '.card__slider-img'
                })
            });
        }

        var props = prop.cluster;


        for (i in props) {

            props[i].icon = {
                    className : "property_point",
                    html:"<span>&euro; "+props[i].price+"</span>",
                    iconSize:null
                };

            marker2 = {
                "type": "Feature",
                "properties": props[i],
                "geometry": {
                    "type": "Point",
                    "coordinates": [props[i].latitude, props[i].longitude]
                }
            };

            property_markers.push([props[i].latitude, props[i].longitude]);

            clusters.features.push(marker2);
        }


        var bounds = property_markers.reduce(function(bounds, coord) {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(property_markers[0], property_markers[0]));


        map.fitBounds(bounds, {
            padding: 20
        });


    }
    else {
        $("#filteredResults").html("<h4 style='text-align: center;width: 100%;'>No Result Found</h4>");
        $("#results").text('');
    }

    if(clusterflag)
    {
        create_cluster();
        clusterflag = false;
    }
    else {
        map.getSource("properties").setData(clusters);
    }

    CreatePagination();
}


function create_cluster()
{
    map.addSource("properties", {
        type: "geojson",
        data: clusters,
        cluster: true,
        clusterMaxZoom: 14, // Max zoom to cluster points on
        clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
    });


    map.addLayer({
        id: "clusters",
        type: "circle",
        source: "properties",
        filter: ["has", "point_count"],
        paint: {
            "circle-color": {
                property: "point_count",
                type: "interval",
                stops: [
                    [0, "#7530B2"],
                    [30, "#00AB8A"],
                    [50, "#FF9700"],
                    [150, "#EC644B"],
                    [300, "#FFED21"],
                ]
            },
            "circle-radius": {
                property: "point_count",
                type: "interval",
                stops: [
                    [0, 20],
                    [30, 30],
                    [50, 40],
                    [150, 50],
                    [300, 60]
                ]
            }
        }
    });

    map.addLayer({
        id: "cluster-count",
        type: "symbol",
        source: "properties",
        filter: ["has", "point_count"],
        layout: {
            "text-field": "{point_count_abbreviated}",
            "text-font": ["DIN Offc Pro Medium", "Arial Unicode MS Bold"],
            "text-size": 12
        }
    });
    map.addLayer({
        "id": "unclustered-points-2",
        "type": "symbol",
        "source": "properties",
        "filter": ["!has", "point_count"],
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
    map.addLayer({
        "id": "unclustered-points",
        "type": "circle",
        "source": "properties",
        "filter": ["!has", "point_count"],
        "paint" : {
            "circle-color": "#00AB8A",
            "circle-radius": 6,
            "circle-stroke-width": 2,
            "circle-stroke-color": "#048169"
        }
    });

    map.addLayer({
        "id": "unclustered-points-3",
        "type": "circle",
        "source": "properties",
        "filter": ["!has", "point_count"],
        "paint" : {
            "circle-color": "#9963C9",
            "circle-radius": 6,
            "circle-stroke-width": 2,
            "circle-stroke-color": "#7530B2"
        },
        "filter": ["==", "direccion", ""]
    });

    var popup = new mapboxgl.Popup({
        closeButton: false,
        closeOnClick: false
    });


    bounds = [clusters.features[0].geometry.coordinates,clusters.features[clusters.features.length - 1].geometry.coordinates];
    map.fitBounds(bounds,{top: 10, bottom:10, left: 10, right: 10});

    map.on('click', function (e) {
        var features = map.queryRenderedFeatures(e.point, { layers: ['unclustered-points'] });
         map.getCanvas().style.cursor = (features.length) ? 'pointer' : '';
        if (!features.length) {
            popup.remove();
            return;
        }
        var feature = features[0];
        searchin(feature.properties.direccion);
    });

    map.on('mousemove', function (e) {
        var features = map.queryRenderedFeatures(e.point, { layers: ['unclustered-points'] });


        map.getCanvas().style.cursor = (features.length) ? 'pointer' : '';


        if (!features.length) {
            popup.remove();
            return;
        }

        var feature = features[0];

        popup.setLngLat(feature.geometry.coordinates).setHTML(FetchInfo(feature.properties)).addTo(map);

    });

    map.on("mousemove", "unclustered-points", function(e) {
        map.setFilter("unclustered-points-3", ["==", "id", e.features[0].properties.id]);
    });

    map.on("mouseleave", "unclustered-points", function() {
        map.setFilter("unclustered-points-3", ["==", "id", ""]);
    });


   // setTimeout(function(){  $(".page_loader").fadeOut(500); }, 1000);

}



function FetchInfo(property)
{
    images = jQuery.parseJSON(property.images);
    $imageurl = 'https://loquare-s3-data.s3.eu-central-1.amazonaws.com/Properties/'+property.id+'/thumbs/'+images[0].filename;

    var block =  '<div class="  plaseinfo">';
        block += '<div class="results">';
        block += '<div class="place_details">';
        block += '<div class="info-img col-xs-12">';
        block += '<img src="'+$imageurl+'"  class="pdetails_area_width" >';
        block += '</div>';
        block += '<div class="col-xs-12">';
        block += '<div class="popup_content">';
        block += '<p class="pdetails_area_width" ><a href="'+basepath+'/property/detail/'+property.id+'" class="property_name">'+property.direccion+'</a></p>';
        block += '<div class="clearfix"></div>';
        block += '<span class="amount">&euro;'+((property.price.toFixed(2)))+' ('+(property.sizem2)+'m<sup>2</sup>)<br>';
        block += '<span class="address">'+property.localidad+','+property.cops+'</span></span>';
        block += property.rooms+' <img src="'+basepath+'/public/frontend/assets/icons/room.png" height="12px" width="12px" /> ,';
        block += property.bathrooms+' <img src="'+basepath+'/public/frontend/assets/icons/bath.png" height="12px" width="12px" /></span>';
        block += '</div>';
        block += '</div>';
        block += '</div>';
        block += '</div>';

    return block;
}


function showProperties(property)
{
    var block = '';
    inwishlist = (jQuery.inArray(property.id, wishlist)> -1 || jQuery.inArray(property.id, newwishlist)> -1)?"active":"";
    saved = (jQuery.inArray(property.id, wishlist)> -1 || jQuery.inArray(property.id, newwishlist)> -1)?"Saved":"Save";
    block += '<div class="grid__item">';
    block += '<div class="card">';
    block += '<a href="'+basepath+'/property/detail/'+property.id+'" class="card__link"></a>';
    block += '<a href="#save-to-collection">';
    block += '<button type="button" class="card__save add_to_wishlist '+inwishlist+'" data-id="'+property.id+'">'+saved+'</button></a>';
    block += '<div class="card__top">';
    block += '<div class="card__slider">';
    images = property.images;

    for(i in images) {
        propertyImg = "https://loquare-s3-data.s3.eu-central-1.amazonaws.com/Properties/"+property.id+"/thumbs/"+images[i].filename;
        block += '<div class="card__slider-img lazyload" data-sizes="auto" data-bgset="'+propertyImg+' 1x, '+propertyImg+' 2x"></div>';
    }

    block += '</div>'
    block += '</div>';
    block += '<div class="card__bottom">';
    block += '<div class="card__title">'+property.direccion+'</div>';
    block += '<div class="card__footer">';
    block += '<div class="card__desc">'+property.sizem2+' m<sup>2</sup></div>';
    block += '<div class="card__price">&euro;'+property.price+((property.property_deal == "RENT"?'/mo':''))+'</div>';
    block += '</div>';
    block += '</div>';
    block += '</div>';
    block += '</div>';

    $("#filteredResults").append(block);

}

function CreatePagination()
{
    var totalsrecords = parseInt($("#results").text());

    pages = totalsrecords / Searchquery.limit;

    if((totalsrecords % Searchquery.limit) > 0)
    {
        pages += 1;
    }
    pages = parseInt(pages);

    lists = '<li><a href="javascript:void(0)" class="previous_page"><i class="fa fa-chevron-left"></i></a></li>';

    for(i = 1;i <= pages;i++ )
    {
        var activePage = "";
        if(i == page){ activePage = "active"; }
        lists += '<li class="pagenum"><a href="javascript:void(0)" id="page'+i+'" class="pages '+activePage+'">'+i+'</a></li>';
    }

    lists += ' <li><a href="javascript:void(0)" class="next_page"><i class="fa fa-chevron-right"></i></a></li>';
    $("#pages").html(lists);

    $( "#pages li.pagenum:lt("+prv+")" ).css( "display", "none" );
    $( "#pages li.pagenum:gt("+nxt+")" ).css( "display", "none" );


    $("#pages a:not('.active')").click(function(){

        $('html,body').animate({scrollTop: 0},0);

        if($(this).hasClass("previous_page") && page > 1)
        {
            page = page - 1;
            $("#pages a").removeClass("active");
            $("#page"+page).addClass("active");

            blk = parseInt(page / 5);
            if(Number.isInteger((page) / 5)) {
                nxt = (blk * 5) - 1;
                prv = (blk * 5) - 5;
            }

            FilterProperties();
        }
        else if($(this).hasClass("next_page") && page != pages)
        {
            page = page + 1;

            $("#pages a").removeClass("active");
            $("#page"+page).addClass("active");

            blk = parseInt(page / 5)+1;
            if(!Number.isInteger((page) / 5)) {
                nxt = (blk * 5) - 1;
                prv = (blk * 5) - 5;
            }

            FilterProperties();
        }
        else if($(this).hasClass("pages")){
            $("#pages a").removeClass("active");
            $(this).addClass("active");
            page = parseInt($(this).text());

            FilterProperties();
        }
        else{  }

    });

    $('#dashmenu_container').animate({
        scrollTop: 0
    }, 1000);
}

function searchin(search_value)
{
    if(search_value.trim() != '')
    {
          $("#mapbox_search").val(search_value);
    }
    var search_property=$("#mapbox_search").val();
   
    $("#searchresults").addClass("hidden");
    $.ajax({
        "url":basepath+"/searchin",
        "type":"post",
        "data":{
            "search":search_property,
            "_token":$("input[name='_token']").val()
        },
        "success":function(res){
            res = jQuery.parseJSON(res);
            if(res.status = 200)
            {
                Searchquery.searchin = res.searchin;
            }
            else {
                Searchquery.searchin = "listing";
            }
        },
        complete:function(){
            init();
            FilterProperties();
        }
    });
}

function common_functions()
{
    $(document).click(function(e){
        if(!($(e.target).hasClass("range-input"))){
            $(".option-group").fadeOut(0);
            $(".option-group .droplist-option").removeClass("active");
        }
    });

    setpricerange();
    $("#minprice, #minsize, #maxprice_input, #maxsize").change(function(){ init(); FilterProperties(); });

    $(".property_type_filter").change(function () {
        init();
        FilterProperties();
    });

    $(".room_filter, .bath_filter").click(function(){
        init();
        FilterProperties();
    });
    $("#sort_filter").change(function(){
        init();
        FilterProperties();
    });

    $(".features").click(function(){
        init();
        FilterProperties();
    });

    $("#find_address").click(function(){
        searchin("");
    });

    $("#filter_for").change(function(e){
        e.preventDefault();
        setpricerange();
        init();
        FilterProperties();
    });

}

function setpricerange()
{
    $(".min, .max").html("");
    $propertyfor = $("#filter_for").val();


    var gap = 500;
    var initprice = 500;
    $prices = [];
    while(initprice <= 4000000)
    {
        $prices.push(initprice);
        initprice = initprice+gap;
        if(initprice >= 1000){ gap = 1000; }
        if(initprice >= 5000){ gap = 45000; }
        if(initprice >= 50000){ gap = 25000; }
        if(initprice >= 500000){ gap = 50000; }
        if(initprice >= 1000000){ gap = 100000; }
        if(initprice >= 2000000){ gap = 250000; }
        if(initprice >= 3000000){ gap = 500000; }
    }

    if($propertyfor == "RENT")
    {
        $prices = [500,1000,2000,3000,4000,5000];
    }
    if($propertyfor == "SALE") {
        var gap = 5000;
        var initprice = 50000;
        $prices = [];
        while(initprice <= 4000000)
        {
            $prices.push(initprice);
            initprice = initprice+gap;
            if(initprice >= 50000){ gap = 25000; }
            if(initprice >= 500000){ gap = 50000; }
            if(initprice >= 1000000){ gap = 100000; }
            if(initprice >= 2000000){ gap = 250000; }
            if(initprice >= 3000000){ gap = 500000; }
        }
    }
    $(".min").append('<div  class="data-option" data-value="" onclick="setMinPrice(\"\")">All</div>');
    $(".max").append('<div  class="data-option" data-value="" onclick="setMinPrice(\"\")">All</div>');
    for(i in $prices)
    {
        $(".min").append('<div  class="data-option" data-value="'+$prices[i]+'" onclick="setMinPrice('+$prices[i]+')">'+$prices[i]+'</div>');
        $(".max").append('<div  class="data-option" data-value="'+$prices[i]+'" onclick="setMaxPrice('+$prices[i]+')">'+$prices[i]+'</div>');
    }

}

function setMinPrice(minprice)
{
    $("#minprice").val(minprice);
    $(".option-group-max").fadeOut(0);
    $(".option-group .min, .option-group .max").removeClass("active");
    FilterProperties();
}

function setMaxPrice(maxprice)
{
    $("#maxprice_input").val(maxprice);
    $(".option-group-min").fadeOut(0);
    $(".option-group .min, .option-group .max").removeClass("active");
    FilterProperties();
}

function sliderfunction()
{
    $(".range-input").click(function(){
        dropoption = $(this).data("group");
        $(".option-group").fadeOut(0);
        $(".option-group-"+dropoption).fadeIn(500);
        $(".option-group .droplist-option").removeClass("active");
        $(".option-group ."+dropoption).addClass("active");
    });

    $(".data-option").click(function(e){
        $(this).parent().parent().prev().val($(this).data("value"));
        init();
        FilterProperties();
    });
}

function get_user_wishlist()
{
    $.ajax({
        url:basepath+"/user/wishlistarray",
        type:"get",
        data:{"_token":$('input[name="_token"]').val()},
        success:function(res){
            res = jQuery.parseJSON(res);
            if(res)
            {
                wishlist =  res.map(function(item) {
                    return parseInt(item);
                });

            }
            else {
                wishlist = [];
            }
        },
        complete:function(){
            FilterProperties();
        }
    });
}

function add_to_wishlist()
{
    $(".add_to_wishlist").off("click");

    $(".add_to_wishlist").click(function(){

        var existsinold = wishlist.indexOf($(this).data("id"));
        var existsinnew = newwishlist.indexOf($(this).data("id"));

        $(this).toggleClass("active");
        if($(this).hasClass("active")){
            if(existsinold < 0 && existsinnew < 0) {
                newwishlist.push($(this).data("id"));
            }
        }else {
            if(existsinold != -1 || existsinnew != -1) {
                newwishlist.splice(existsinarray, 1);
            }
        }
    });
}