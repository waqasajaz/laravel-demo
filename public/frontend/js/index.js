var controller = "searchresult";
var searchin = "listing";

$(document).ready(function(){
    $("body").click(function(){
        $("#searchlist").html("");
        $("#searchresults").addClass("hidden");
    });

    $('#mapbox_search').keyup(function(event) {
        var donnotsearch = [13,17,37,38,39,40];
        if((jQuery.inArray(event.which, donnotsearch) <= -1) && !event.ctrlKey)
        {
            showSearchList();
        }
    });
    $('#mapbox_search').click(function() { showSearchList(); });

    $("#search_property").click(function(){
        $.ajax({
            url:basepath+"/searchfromhome",
            type:"post",
            data:{
                "_token" : $("input[name='_token']").val(),
                "search" : $("#mapbox_search").val(),
                "searchin" : searchin,
                "type"   : $(".type.grn-act").data("value")
            },
            success:function(res){
            },
            complete:function(){
                $(location).attr("href", basepath+"/properties");
            }

        });
    });

});

function showSearchList()
{
    if($("#mapbox_search").val().length >= 3) {
        $.ajax({
            "url": basepath + "/"+controller,
            "type": "post",
            "data": {
                "_token": $('input[name="_token"]').val(),
                "states" : "Barcelona",
                "query": $("#mapbox_search").val()
            },
            success: function (response) {
                $("#searchlist").html("");
                res = $.parseJSON(response);
                $("#searchlist").show(0);

                district    = res.district;
                hoods       = res.hoods;
                cops        = res.cops;
                listing     = res.listing;
                areas       = res.areas;

                if(district.length != 0 || listing.length != 0 || hoods.length != 0 || cops.length != 0 || areas.length != 0)
                {
                    $("#searchresults").removeClass("hidden");
                }
                else {
                    $("#searchresults").addClass("hidden");
                }

                if(district.length != 0)
                {
                    $("#searchlist").append("<li class='list_title'><span>Search homes in District </span><ul>");
                    for(i in district)
                    {
                        distname = district[i].district.trim();
                        $("#searchlist").append("<li class='list-item district' searchin = 'district' place='"+distname+"'>"+distname+"</li>");
                    }
                    $("#searchlist").append("</ul></li>");
                }

                if(hoods.length != 0)
                {
                    $("#searchlist").append("<li class='list_title'><span>Search homes in Hood</span><ul>");
                    for(i in hoods)
                    {
                        hoodname = hoods[i].hood.trim();
                        $("#searchlist").append("<li class='list-item hoods' searchin = 'hood_table' place='"+hoodname+"'>"+hoodname+"</li>");
                    }
                    $("#searchlist").append("</ul></li>");
                }

                if(cops.length != 0)
                {
                    $("#searchlist").append("<li class='list_title'><span>Search homes in Cop area</span><ul>");
                    for(i in cops)
                    {
                        $("#searchlist").append("<li class='list-item cops' searchin = 'cops' place='"+cops[i].cops+"'>"+cops[i].cops+"</li>");
                    }
                    $("#searchlist").append("</ul></li>");
                }

                if(areas.length != 0)
                {
                    $("#searchlist").append("<li class='list_title'><span>Search In Area </span><ul>");
                    for(i in areas)
                    {
                        $("#searchlist").append("<li class='list-item areas' searchin = 'areas' place='"+areas[i].provincia+"'>"+areas[i].provincia+"</li>");
                    }
                    $("#searchlist").append("</ul></li>");
                }

                if(listing.length != 0)
                {
                    $("#searchlist").append("<li class='list_title'><span>Listing</span><ul>");
                    for(i in listing)
                    {
                        item = "<li class='list-item listing' searchin='listing' place='"+listing[i].direccion+";"+listing[i].cops+" "+listing[i].provincia+"'>"+listing[i].direccion+"<span> - ";
                        item += "Bed "+listing[i].rooms+" | ";
                        item += "Bath "+listing[i].bathrooms+" | ";
                        item += "Price $"+listing[i].price+"</span></li>";

                        $("#searchlist").append(item);
                    }
                    $("#searchlist").append("</ul></li>");
                }


                $("#searchlist li.list-item").click(function(){

                    searchtext = $(this).text();
                    searchtext = $(this).attr("place");

                    searchin = $(this).attr("searchin");

                    $("#mapbox_search").val(searchtext);
                    $("#searchlist").html("");

                    $("#searchresults").addClass("hidden");

                });

            }

        });
    }
    else {
        $("#searchlist").html("");
        $("#searchresults").addClass("hidden");
    }
}



$(window).scroll(function() {

    $(".home_area").slick({
        autoplay: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        prevArrow: false,
        nextArrow: false
    });

    if ($(this).scrollTop() > 60){
        $('.navbar-fixed-top').addClass("logo-fix-adon");

    }
    else{
        $('.navbar-fixed-top').removeClass("logo-fix-adon");

    }

});


$(document).ready(function() {
    $(".select-type li").click(function () {
        $(".select-type li").removeClass("grn-act");
        $(this).addClass("grn-act");
    });

    $(".midl-box .circle-radio li").click(function () {
        $(".midl-box .circle-radio li").removeClass("grn-act_nw");
        $(this).addClass("grn-act_nw");
    });

});

$(function() {
    $('.pos-botm').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });

    $('body').scrollspy({
        target: '.navbar-fixed-top'
    })


    $('.snd-col').hover(function () {
        jQuery(".tabl-width").addClass('hover-bg');
    }, function () {
        $(".tabl-width").removeClass('hover-bg');
    });

    $('.thrd-col').hover(function () {
        jQuery(".tabl-width").addClass('hover-bg_2');
    }, function () {
        $(".tabl-width").removeClass('hover-bg_2');
    });

});