
var range = $('.range-slider__range');
var value_label = $('.range-slider__value');
var rangeinput = $('.slider_input');
var remaining = $('.rmning-price');
var range_percentage = $('.range_percentage');
var neg_price_percentage = $('.neg_price_percentage');
var value = range.val();
value_label.html(value);
rangeinput.val(value);


range.on('change, input', function () {
    $(this).next(value_label).html(this.value);
    rangeinput.val(this.value);

    var amount = parseFloat(($('#suggested_price').val() * this.value) / 100).toFixed(0);
    $(this).parent().find('.range_percentage').html("&euro;" + amount);
    $(this).parent().next(remaining).html('Cash: &euro;' + ($('#suggested_price').val() - amount));

    $(this).parent().find('.neg_price_percentage').html(parseFloat(((this.value / $('#listed_price').val()) - 1) * 100).toFixed(2) + '%');

    if ($('#listed_price').val() < 1000) {
        $(this).parent().find('.top_value').html('Refund: ' + Math.abs(parseFloat(this.value - (((this.value / 1000) * 0.5)) * this.value).toFixed(2)));
    } else {
        $(this).parent().find('.top_value').html('Refund: ' + Math.abs(parseFloat(this.value * 0.5.toFixed(2))));
    }

    if ($('#listed_price').val() == this.value) {
        $("#middle_point").css('color', '#00AB8A');
    } else {
        $("#middle_point").css('color', '#000000');
    }

    var val = ($(this).val() - $(this).attr('min')) / ($(this).attr('max') - $(this).attr('min'));
    $(this).css('background-image',
        '-webkit-gradient(linear, left top, right top, '
        + 'color-stop(' + val + ', #00AB8A), '
        + 'color-stop(' + val + ', #C5C5C5)'
        + ')'
    );
});

$("#slider_input").keydown(function(key){
    if(key.keyCode == 13)
    {
        key.preventDefault();
        $(this).trigger("blur");
        return false;
    }
    if(!((key.keyCode >= 48 && key.keyCode <= 57) || key.keyCode == 8 || key.keyCode == 9 || key.keyCode == 13 || (key.keyCode >= 96 && key.keyCode <= 105))) { return false; }
});

$('.slider_input').change(function(e){

    range.val($(this).val());

    if(parseInt(this.value) >= parseInt($(this).attr("max")))
    {
        $(this).val($(this).attr("max"));
    }
    if(parseInt(this.value) <= parseInt($(this).attr("min")))
    {
        $(this).val($(this).attr("min"));
    }
    var reg = /^\d+$/;
    if(!reg.test(this.value))
    {
        $("#suggest_price_btn").attr("disabled", "disabled");
    }
    else {
        $("#suggest_price_btn").removeAttr("disabled");
    }

    var amount = parseFloat(($('#suggested_price').val() * this.value) / 100).toFixed(0);
    $('.range_percentage').html("&euro;" + amount);
    remaining.html('Cash: &euro;' + ($('#suggested_price').val() - amount));

    $('.neg_price_percentage').html(parseFloat(((this.value / $('#listed_price').val()) - 1) * 100).toFixed(2) + '%');

    if ($('#listed_price').val() < 1000) {
        $('.top_value').html('Refund: ' + Math.abs(parseFloat(this.value - (((this.value / 1000) * 0.5)) * this.value).toFixed(2)));
    } else {
        $('.top_value').html('Refund: ' + Math.abs(parseFloat(this.value * 0.5.toFixed(2))));
    }

    if ($('#listed_price').val() == this.value) {
        $("#middle_point").css('color', '#00AB8A');
    } else {
        $("#middle_point").css('color', '#000000');
    }

    var val = ($(this).val() - $(this).attr('min')) / ($(this).attr('max') - $(this).attr('min'));
    range.css('background-image',
        '-webkit-gradient(linear, left top, right top, '
        + 'color-stop(' + val + ', #00AB8A), '
        + 'color-stop(' + val + ', #C5C5C5)'
        + ')'
    );
});

