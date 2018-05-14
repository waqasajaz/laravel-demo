$(document).ready(function(){

    $(".property_images").click(function(e){
        e.preventDefault();
        property_images = [];
        images_source = $(this).attr("href");
        $.ajax({
            url : images_source,
            type:"post",
            data:{
                "_token":$("input[name='_token']").val()
            },
            success:function(res){
                property_images = jQuery.parseJSON(res);
            },
            complete:function(){
                if(property_images)
                {
                    jQuery.magnificPopup.open({
                        items: property_images,
                        type: 'image',
                        gallery: {
                            enabled: true
                        },
                    });
                }
            }
        });
    });
});