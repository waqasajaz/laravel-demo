var userLocation = {
    latitude:"",
    longitude:""
}

$.fn.bindFirst = function(name, fn) {
    this.on(name, fn);
    this.each(function() {
        var handlers = $._data(this, 'events')[name.split('.')[0]];
        var handler = handlers.pop();
        handlers.splice(0, 0, handler);
    });
};

window.fbAsyncInit = function() {
    FB.init({
        appId            : '151273565510156',
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v2.11'
    });
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));



$(document).ready(function(){

    $("[data-title]").each(function(){
        helpelement = $(this);
        datatitle = (helpelement.data("title").trim() == "")?helpelemen.text():helpelement.data("title");
        helpcontent = '<div class="help_tooltip"><i class="fa fa-question-circle"></i><span>'+datatitle+'</span></div>';
        helpelement.append(helpcontent);
    });

    $(document).on("click", ".save_to_collection", function(){
        $(".popup-form--save-to-collection #collect_property").val($(this).data('id'));
    });

    $(".search-add__btn").click(function(){
        search_collcetion();
    });


    $('.open_colleciton_popup').magnificPopup({
        type:'inline',
        midClick: true
    });

    request_contact();

});

function search_collcetion()
{
    var collect_property = $(".popup-form--save-to-collection #collect_property").val();
    $(".list-checks").html("");
    collection = $(".search-add__field").val();
    if(collection.trim() != "")
    {
        $.ajax({
            "url":basepath+"/search_add_collection",
            "type":"post",
            "data":{
                "collection" : collection,
                "property"  : collect_property,
                "_token" : $('input[name="_token"]').val()
            },
            success:function(res) {
                res = jQuery.parseJSON(res);
                if (res != false)
                {
                    $(".list-checks").html(res);
                }
            }
        });
    }
}

function get_userLocation(callback)
{
    navigator.geolocation.getCurrentPosition(function(pos){

        userLocation.latitude = pos.coords.latitude;
        userLocation.longitude = pos.coords.longitude;

        if(callback != "")
        {
            callback();
        }

    }, function (err) {
        console.warn('ERROR'+err.code+':'+err.message);
    }, {
        enableHighAccuracy: true
    });
}

function display_alert(type, message, reload)
{
    type = (type == 200) ?"success":"error";
    $("#alert-"+type+" .visit-react__desc").html(message);

    options = {
        items: {
            src: '#alert-'+type
        },
        type: 'inline'
    };

    if(reload)
    {
        options.callbacks =  {
            close: function(){
                location.reload();
            }
        }
    }

    $.magnificPopup.open(options);
}


function FBShare(shareid)
{
    
     var url=basepath+'/property/detail/'+shareid;
     window.open('https://www.facebook.com/dialog/share?app_id=145634995501895&display=popup&href='+encodeURIComponent(url), '', 'left=0,top=0,width=550,height=450,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
    
     FB.ui({
        method: 'share',
        href:text,
        mobile_iframe: true,
        
    }, function(response){
        console.log(response);
    });
}

function shareTwitter(shareid)
{

    $.post(basepath+"/property/get/json", { "id":shareid, "_token":$("input[name='_token']").val() },function(){

    }).done(function(res){
        res = jQuery.parseJSON(res);
        if(res != false)
        {
            var url = basepath+'/property/detail/'+res[0].id;
            var text = res[0].discription.substring(0,250)+" ...\n";
            window.open('http://twitter.com/share?url='+encodeURIComponent(url)+'&text='+encodeURIComponent(text), '', 'left=0,top=0,width=550,height=450,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
        }
    });
}

function shareMail(shareid)
{
	$.post(basepath+"/get_collection_data", { "id":shareid, "_token":$("input[name='_token']").val() },function(){

	}).done(function(data){

		if(data != false)
		{
		   var JSONObject_data = JSON.parse(data);
		   console.log(JSONObject_data);   

			var img_src ="http://18.195.202.33/storage/Property/316/thumbs/2017111114220997.jpg"; 
			var image = "<br><br><img src='"+img_src+"' class='img img-responsive' /><br> ";
			var share_link=basepath+'/property/detail/'+shareid;

			var body_title = JSONObject_data['direccion'].replace(",", "_");
			var subject = "Loquare_"+body_title;

			window.open('mailto:info@loquare.com?body='+body_title+'<br><br>'+share_link+'<br><br>'+image+'&subject='+subject,'_self');

		}
	 });
}

function loadScript(callback) {

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleapi_key + "&callback="+callback;
    document.body.appendChild(script);
}

function fit_to_location(address, map)
{
    $.ajax({
        "url" : 'https://maps.googleapis.com/maps/api/geocode/json',
        "type" : "get",
        "data" : {
            address : address,
            key : googleapi_key
        },
        "success" : function(res){
            place = res.results[0].geometry.bounds;

            bounds =  [[
                place.southwest.lng,
                place.southwest.lat
            ], [
                place.northeast.lng,
                place.northeast.lat
            ]];


            map.fitBounds(bounds);
        }
    });
}

function request_contact()
{
    $("#request_contact").click(function(){
        $.ajax({
            url:basepath+"/request_contact",
            type:"post",
            data:$("#contact_us_form").serialize(),
            success:function(res){
                res = jQuery.parseJSON(res);
                if(res)
                {

                }
            }
        });
    });
}
