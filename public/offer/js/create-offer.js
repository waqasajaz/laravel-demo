//Step 2 Controls
function step2_save(form,type)
{
    $('#loader').show();
    $('#step2_box .check-fill-txt').hide();
    $('#step2_success').show();
    $('#step2_box').addClass('fill-correct');

    if(type == 'negotiate')
    {
        $('#confirmed').html('&euro;'+$("input[name='customer_offer_price']").val());
        var suggested_price = $("input[name='customer_offer_price']").val();
    }
    else
    {
        $('#confirmed').html('&euro;'+$("input[name='owner_offer_price']").val());
        var suggested_price = $("input[name='owner_offer_price']").val();
    }
    $('#suggested_price').val(suggested_price);


    var percentage_mortgage_not_approved = $('#payment_method_mortgage_dp_amount_not_approved').val();
    var percentage_mortgage_approved = $('#payment_method_mortgage_dp_amount_approved').val();

    if(percentage_mortgage_not_approved > 0) {
        var amount_mortgage = parseFloat((percentage_mortgage_not_approved * suggested_price) / 100).toFixed(0);
        var remaining_mortgage = suggested_price - amount_mortgage;
        $('#amount_mortgage_not_approved').html('&euro;'+amount_mortgage);
        $('#remaining_mortgage_not_approved').html('&euro;'+remaining_mortgage);
    }

    if(percentage_mortgage_approved > 0) {
        var amount_mortgage = parseFloat((percentage_mortgage_approved * suggested_price) / 100).toFixed(0);
        var remaining_mortgage = suggested_price - amount_mortgage;
        $('#amount_mortgage_approved').html('&euro;'+amount_mortgage);
        $('#remaining_mortgage_approved').html('&euro;'+remaining_mortgage);
    }

    saveData(form);

    $('#loader').hide();
    $('#step1_box').removeClass('div_disabled');

    return true;
}

function step2_cancel()
{
    $('#step2_box .check-fill-txt').show();
    $('#step2_success').hide();
    $('#step2_box').removeClass('fill-correct');

    return true;
}


//Step 1 Controls
function step1_save(form)
{
    $('#loader').show();
    $('#step1_box .inqry-box').hide();
    $('#step1_success').show();
    $('#step1_box').addClass('fill-correct');

    saveData(form);

    var payment_method =  $("input[type='radio'][name='payment_method']:checked").val();
    if(payment_method == 'Mortgage')
    {
        var amount = '';
        if($("input[type='radio'][name='payment_method_mortgage']:checked").val() == 'Not Approved')
        {
            amount = $("input[name='payment_method_mortgage_dp_amount_not_approved']").val();
        }
        else
        {
            amount = $("input[name='payment_method_mortgage_dp_amount_approved']").val();
        }
        $('#step1_success_text').html(payment_method+', &euro;'+amount+' down.');
    }
    else
        console.log(payment_method);
    {
        $('#step1_success_text').html(payment_method);
    }

    $('#loader').hide();
    $('#step3_box').removeClass('div_disabled');

    return true;
}

function step1_cancel()
{
    $('#step1_box .inqry-box').show();
    $('#step1_success').hide();
    $('#step1_box').removeClass('fill-correct');
    return true;
}

function mortgage()
{
    $('#step2_box .check-fill-txt').show();
    $('#step2_success').hide();
    return true;
}


//Step 3 Controls
function step3_save(form, next_step)
{
    var name = $('#customer_name').val();
    var phone = $('#customer_phone').val();
    var email = $('#customer_email').val();
    $('#customer_name,#customer_phone,#customer_email').removeClass('border_red');
    if(name == '') {
        $('#customer_name').addClass('border_red');
        $("#customer_name").focus();
        return false;
    }

    if(phone == '') {
        $('#customer_phone').addClass('border_red');
        $("#customer_phone").focus();
        return false;
    }
    else
    {
        $('#customer_phone').removeClass('border_red');
    }

    if(email == '') {
        $('#customer_email').addClass('border_red');
        $("#customer_email").focus();
        return false;
    } else if(email != '') {
        var atpos = email.indexOf("@");
        var dotpos = email.lastIndexOf(".");
        if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) {
            $('#customer_email').addClass('border_red');
            $("#customer_email").focus();
            return false;
        }
        
    }


    $('#loader').show();
    $('#step3_box .inqry-box').hide();
    $('#step3_success').show();
    $('#step3_box').addClass('fill-correct');

    $('#name').text(name);
    $('#numbrs').text(phone);
    $('#mail-id').text(email);

    saveData(form);

    $('#loader').hide();
    $('#step'+next_step+'_box').removeClass('div_disabled');

    return true;
}

function step3_cancel()
{
    $('#step3_box .inqry-box').show();
    $('#step3_success').hide();
    $('#step3_box').removeClass('fill-correct');

    return true;
}


//Step 4 Controls
function step4_save(form,type)
{
    $('#loader').show();
    $('#step4_box .inqry-box').hide();
    $('#step4_success').show();
    $('#step4_box').addClass('fill-correct');
    
    saveData(form);

    if(type == 'confirm')
    {
        var schedule1 = $("input[name='schedule_visit_1']").val();
        var schedule2 = $("input[name='schedule_visit_2']").val();

        if(schedule1 != '' && schedule2 != '')
        {
            $('#step4_success').html('Scheduted to visit the apartment at '+schedule1+' & '+schedule2+' <span class="right-btin close-btn" onclick="return step4_cancel();">Cancel</span>');
        }
        else if(schedule1 != '' && schedule2 == '')
        {
            $('#step4_success').html('Scheduted to visit the apartment at '+schedule1+' <span class="right-btin close-btn" onclick="return step4_cancel();">Cancel</span>');
        }
    }
    else
    {
        $('#step4_success').html('You marked apartment as visited. '+' <span class="right-btin close-btn" onclick="return step4_cancel();">Cancel</span>');
    }

    $('#loader').hide();
    $('#step5_box').removeClass('div_disabled');

    return true;
}

function step4_cancel()
{
    $('#step4_box .inqry-box').show();
    $('#step4_success').hide();
    $('#step4_box').removeClass('fill-correct');

    return true;
}

function step4_save_1(form)
{
    $('#loader').show();
    $('#step4_box .inqry-box').hide();
    $('#step4_success').show();
    $('#step4_box').addClass('fill-correct');

    saveData(form);

    if($('#rental_period').val() == 1) {
        var selection = '1 Year';
    } else if($('#rental_period').val() == 2) {
        var selection = '3 Year';
    } else if($('#rental_period').val() == 3) {
        var selection = '&gt; 3 Year';
    }

    $('#step4_success').html('You selected rental period <strong>'+selection+'</strong>. '+' <span class="right-btin close-btn" onclick="return step4_cancel();">Cancel</span>');

    $('#loader').hide();
    $('#step5_box').removeClass('div_disabled');

    return true;
}


//Step 5 Controls
function step5_save(form)
{
    $('#loader').show();
    $('#step5_box .inqry-box').hide();
    $('#step5_success').show();
    $('#step5_box').addClass('fill-correct');

    saveData(form);
    $('#loader').hide();
    $('#step6_box').removeClass('div_disabled');

    return true;
}

function step5_cancel()
{
    $('#step5_box .inqry-box').show();
    $('#step5_success').hide();
    $('#step5_box').removeClass('fill-correct');

    return true;
}


//Step 6 Controls
function step6_save_1(form)
{
    var first_name_1 = $('#first_name_1').val();
    var last_name_1 = $('#last_name_1').val();

    $('#first_name_1,#last_name_1').removeClass('border_red');
    if(first_name_1 == '') {
        $('#first_name_1').addClass('border_red');
        $("#first_name_1").focus();
        return false;
    }

    if(last_name_1 == '') {
        $('#last_name_1').addClass('border_red');
        $("#last_name_1").focus();
        return false;
    }

    $('#loader').show();
    $('#box_1').hide();
    $('#step6_success_buyer').show();
    $('#step6_box').addClass('fill-correct');

    saveData(form)
    $('#loader').hide();
    $('#step7_submit').show();
    $('#step7_box').removeClass('div_disabled');

    return true;
}

function step6_cancel_1()
{
    $('#box_1').show();
    $('#step6_success_buyer').hide();
    $('#step6_box').removeClass('fill-correct');

    return true;
}

function step6_save_2(form)
{
    var first_name_2 = $('#first_name_2').val();
    var last_name_2 = $('#last_name_2').val();

    $('#first_name_2,#last_name_2').removeClass('border_red');
    if(first_name_2 == '') {
        $('#first_name_2').addClass('border_red');
        $("#first_name_2").focus();
        return false;
    }

    if(last_name_2 == '') {
        $('#last_name_2').addClass('border_red');
        $("#last_name_2").focus();
        return false;
    }

    $('#loader').show();
    $('#box_2').hide();
    $('#step6_success_cobuyer').show();
    $('#step6_box').addClass('fill-correct');

    saveData(form);
    $('#loader').hide();
    $('#step7_submit').show();
    $('#step7_box').removeClass('div_disabled');

    return true;
}

function step6_cancel_2()
{
    $('#box_2').show();
    $('#step6_success_cobuyer').hide();
    $('#step6_box').removeClass('fill-correct');

    return true;
}