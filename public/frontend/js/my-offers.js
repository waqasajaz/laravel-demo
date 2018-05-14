var my_offer_options = {
    "total": 0,
    "page": 1,
    "limit": 10,
    "property_for": $(".filter_for.active").data("type"),
    "_token": $("input[name='_token']").val()
};
var offer = {};
$(document).ready(function () {

    get_My_offers();

    $(".filter_for").click(function () {
        $(".filter_for").removeClass("active");
        $(this).addClass("active");
        initialize_myoffers();
        get_My_offers();
    });

    $(".load_more").on('click', function () {
        my_offer_options.page++;
        get_My_offers();
    });


});
function initialize_myoffers() {
    $("#my_offers").html("");
    $(".load_more").addClass("hidden");

    my_offer_options.page = 1;
    my_offer_options.limit = 10;
    my_offer_options.property_for = $(".filter_for.active").data("type");
}


function deleteAoffer() {

    $('.delete_offer_popup').magnificPopup({
        type: 'inline',
        midClick: true
    });

    $(".delete_offer_popup").on("click", function(){
        offer.id = $(this).data("id");
        $("#delete-offer .popup__subtitle span").text($("#offer-"+offer.id+" .my-offer__title").text());
    });

    $('#delete-offer .popup__ok-btn').on('click', function () {

        $.ajax({
            url: basepath + '/offer/cancel',
            type: 'POST',
            data: {
                    "id": offer.id,
                "_token": $('input[name="_token"]').val()
            },
            success: function (res) {
                res = $.parseJSON(res);

                if (res.status == 200) {
                    $.magnificPopup.close();
                    display_alert(res.status, res.message, false);
                    $("#offer-"+offer.id).remove();
                }
                else {
                    display_alert(res.status, res.message, false);
                }
            }
        });
    });

    $("#delete-property .popup__cancel-btn").on("click", function () {
        $.magnificPopup.close();
    });

}


function get_My_offers() {
    $.ajax({
        url: basepath + "/get-my-offers",
        type: "POST",
        data: my_offer_options,
        success: function (res) {
            res = $.parseJSON(res);
            my_offer_options.total = res.total;
            res.content = res.content.trim();
            if (res.content != "") {
                $("#my_offers").append(res.content);
            }
        },
        complete: function () {
            if (my_offer_options.total > (my_offer_options.page * my_offer_options.limit)) {
                $(".load_more").removeClass("hidden");
            }
            else {
                $(".load_more").addClass("hidden");
            }
            $(".my-offers").fadeIn(1000);
            $('.property_status').off("click");
            deleteAoffer();
        }
    });
}
 