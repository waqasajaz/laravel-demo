@extends('layouts.app')
@section('title', 'Create Offer | '.$property['direccion'])
@section('content')
    <link href="{{ asset('offer/css/bootstrap-combined.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('offer/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('offer/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('offer/css/main-style.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('offer/css/font-awesome.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('offer/css/slick.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('offer/css/custom.css') }}" type="text/css" rel="stylesheet"/>
<style>
.overlay h4 a:hover {
    text-decoration: underline;
}

#loader {
    display: none;
}

.drop-icon::before {
    position: absolute;
    content: "";
    background: url('../offer/img/down-arrow_gray.png');
    top: 12px;
    right: 13px;
    height: 18px;
    width: 17px;
}

.border_red{
    border: 1px solid #a94442 !important;
}

.div_disabled:before {
    position: absolute;
    content: '';
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    /*background: rgba(0,0,0,0.2);*/
    z-index: 9;
    cursor: not-allowed;
}
.div_disabled {
    position: relative;
    pointer-events:none;
}
.right-section-view{
  position: sticky;
  position: -webkit-sticky;
  position: -moz-sticky;
  position: -ms-sticky;
  position: -o-sticky;
  top: 110px;
  overflow-y: scroll;
  height: -webkit-fill-available;
}

span.range-slider__value_custom:before {
    position: absolute;
    content: "%";
    left: 0;
    top: 0;
    height: 40px;
    border-right: 1px solid #e2e0e0;
    line-height: 40px;
    text-align: center;
    width: 40px;
    font-size: 16px;
}

</style>
<div class="main-body less-pad mt-top-40">
    <div class="container">
        <?php 
            $chatId = 0;
            if(isset($chat))
            {
                $chatId = $chat->id;
            }

        ?>
        <script>
            var chatId = {{$chatId}};
        </script>
        <div class="row" style="padding-top: 70px;">
            <div class="col-md-7">
                <!--start buying section-->
                <div class="white-body">
                    <div class="form-fill-box">
                        <div class="box-up-brd">
                            @if($property['property_deal'] == "SALE")
                            <h2 class="iner-head">Buying offer</h2>
                            @elseif($property['property_deal'] == "RENT")
                            <h2 class="iner-head">Renting offer</h2>
                            @endif
                        </div>
                        @if(isset($offer['step_7_completed']) && $offer['step_7_completed'] == 1)
                        <div class="done-form">
                            <img src="{{asset('offer/img/check-mark.png')}}" alt="">
                            <div class="appont-lst" style="text-align: center;">
                                @if($offer['accept_status'] == 0)
                                    <p>Your offer has already been submitted to our agents and is currently being reviewed.</p>
                                    <p>
                                        <br>
                                        <a href="javascript:goBack();" class="visit__make" style="height:47px;width:200px; margin: 0 auto;">Back</a>
                                    </p>
                                @elseif($offer['accept_status'] == 1)
                                    <p>Congratulations!!<br>Your offer has been accepted by owner.</p>
                                    <p>
                                        <br>
                                        <a href="javascript:goBack();" class="visit__make" style="height:47px;width:200px; margin: 0 auto;">Back</a>
                                    </p>
                                @elseif($offer['accept_status'] == 2)
                                    <p>
                                        Sorry!<br>Your offer has been rejected by owner.
                                    </p>
                                    <p>
                                        <br>
                                        <a href="{{url('modify/offer')}}/{{$offer->id}}" class="visit__make" style="height:47px;width:200px; margin: 0 auto;">Modify Offer</a>&nbsp;
                                        <a href="javascript:goBack();" class="visit__make" style="height:47px;width:200px; margin: 0 auto;">Back</a>
                                    </p>
                                @endif
                            </div>
                            <div class="buttons text-center">
                                <!--<a href="#" class="btn-round">review order details</a>-->
                            </div>
                        </div>
                        @else
                        <!--End buying heading section-->
                        <div class="box-up-brd">
                            <p>This checklist will guide you through the offer process. Please follow the steps carefully and
                                <a href="#" onclick="$('#letusModal').modal();" class="grn-clr">let us know</a> if you have any question</p>
                        </div>
                        <input type="hidden" id="listed_price" value="{{$property['price']}}"/>



                        <!--start second renting  section-->
                        @if(isset($offer['step_2_completed']) && $offer['step_2_completed'] == 1)
                        <?php
                            if(isset($offer['customer_offer_price']) && $offer['customer_offer_price'] != '') {
                                $offered_price = $offer['customer_offer_price'];
                                $offered_price_value = $offer['customer_offer_price'];
                            } else {
                                $offered_price = $offer['owner_offer_price'];
                                $offered_price_value = $offer['owner_offer_price'];
                            }
                            $offered_price = (trim($property['property_deal']) == "SALE")?$offered_price:$offered_price."/mo";
                        ?>
                        <div class="box-up-brd fill-correct" id="step2_box">
                        @else
                            <?php $offered_price = (trim($property['property_deal']) == "SALE")?$property['price']:$property['price']."/mo"; ?>
                            <?php $offered_price_value = $property['price']; ?>
                            <div class="box-up-brd" id="step2_box">
                        @endif
                        <input type="hidden" id="suggested_price" value="{{$offered_price}}"/>
                            <div class="check-box-wrp">
                                <form enctype="multipart/form-data" method="POST">
                                    <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                    <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                    <input type="hidden" name="step_2_completed" value="1"/>
                                    <input type="hidden" id="step_2_negotiate_flag" name="step_2_negotiate_flag" value="0"/>
                                    <input type="hidden" name="owner_offer_price" value="{{$property['price']}}"/>

                                    <h3 class="check-head" data-title="Confirm or negotiate the price">1. Confirm or negotiate the price</h3>
                                    <div class="check-fill-txt-hide">
                                        Owner  set up price of <strong> &euro; {{(trim($property['property_deal']) == "SALE")?$property['price']:$property['price']."/mo"}}</strong>. You can suggest your price.
                                        <p>Owner  may or may not accept your offer at their discreation</p>
                                    </div>
                                    <!--start input range box-->
                                    <div class="inqry-box collapse" id="collapseExample_5">
                                        <div class="gray-clr-bg">
                                            <div class="card-block">
                                                <div class="range-slider">
                                                    <div class="rang-sld">
                                                        <?php $max_value = $property['price'] * 2;?>
                                                        @if($property['property_deal'] == "RENT")
                                                            <input class="range-slider__range" type="range" step="25" type="range" value="{{$offered_price_value}}" min="0" max="{{$max_value}}" name="customer_offer_price" id="customer_offer_price"/>
                                                        @elseif($property['property_deal'] == "SALE")
                                                            <?php $gaps = round((1 * $property['price']) / 100); ?>
                                                            <input class="range-slider__range" type="range" step="{{$gaps}}" type="range" value="{{$offered_price_value}}" min="0" max="{{$max_value}}" name="customer_offer_price" id="customer_offer_price" list="volsettings"/>
                                                        @endif
                                                        <span class="range-slider__value hidden">{{$max_value}}</span>

                                                        <div class="slider_input_block">
                                                            <input type="text" class="slider_input" id="slider_input" value="{{$max_value}}" min="0" max="{{$max_value}}" pattern="^[0-9]+$" />
                                                        </div>

                                                        @if($property['property_deal'] == "RENT")
                                                        <span class="top_value">&nbsp;</span>
                                                        @endif

                                                        @if($property['property_deal'] == "SALE")
                                                        <span class="neg_price_percentage" style="top: 9px !important;"></span>
                                                        @elseif($property['property_deal'] == "RENT")
                                                        <span class="neg_price_percentage"></span>
                                                        @endif
                                                    </div>
                                                    <span style="display: none;"></span>
                                                    <div id="middle_point" style="color: #00AB8A;text-align: center;margin-top: 5px;font-weight: bold"><i class="fa fa-arrow-up"></i></div>
                                                    <div class="center-bx">
                                                        <div class="buttons text-center">
                                                            <div>
                                                                <button class="btn-round" id="suggest_price_btn" type="button" onclick="return step2_save(this.form,'negotiate');">
                                                                    Suggest this price
                                                                </button>
                                                            </div>
                                                            <span class="right-btin close-btn" onclick="return step2_cancel();">Cancel</span> </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end of inqry-box-->
                                    @if(isset($offer['step_2_completed']) && $offer['step_2_completed'] == 1)
                                    <div class="check-fill-txt shw-btn" style="display: none;">
                                    @else
                                    <div class="check-fill-txt shw-btn">
                                    @endif
                                        <p>You offered a price : &euro; <span cusmer-prce>1 100 000</span></p>
                                        <p class="grn-txt">Onwer offered a price : &euro; <span class="owner-prc">1 115 000</span></p>
                                        <div class="buttons">
                                            <button class="btn-round" type="button" onclick="$('#step_2_negotiate_flag').val('0');return step2_save(this.form,'confirm');">
                                                Confirm the price
                                            </button>
                                            <span class="btn-round" data-toggle="collapse" data-target="#collapseExample_5" aria-expanded="false" aria-controls="collapseExample_5" onclick="$('#step2_box .check-fill-txt').hide();$('#step_2_negotiate_flag').val('1');">Negotiate</span>
                                        </div>
                                    </div>
                                    <!--end of check fil txt box-->
                                    <p class="success-txt" id="step2_success">You confirmed the price of <strong id="confirmed">&euro;{{$offered_price}}.</strong> <span class="right-btin close-btn" onclick="return step2_cancel();">Cancel</span></p>
                                </form>
                            </div>
                        </div>
                        <!--end second renting  section-->


                        <!--End buying text section-->
                        @if($property['property_deal'] == "SALE")
                        <?php
                            $payment_method = '';
                            $dp_amount_percentage  = 0;
                            $amount_mortgage = '';
                            $remaining_mortgage = '&nbsp;';
                        ?>
                        @if(isset($offer['step_1_completed']) && $offer['step_1_completed'] == 1)
                        <?php
                            if(isset($offer['payment_method']) && $offer['payment_method'] == 'Mortgage') {
                                if($offer['step_2_negotiate_flag'] == 0) {
                                    $suggested_price = $offer['owner_offer_price'];
                                } else {
                                    $suggested_price = $offer['customer_offer_price'];
                                }
                                $payment_method = $offer['payment_method'];
                                if($offer['payment_method'] == 'Mortgage') {
                                    $dp_amount_percentage = $offer['payment_method_mortgage_dp_amount'];

                                    $amount_mortgage = round(($suggested_price * $dp_amount_percentage) / 100, 0);
                                    $remaining_mortgage =  "&euro;".($suggested_price - $amount_mortgage);
                                    $amount_mortgage = "&euro;".$amount_mortgage;
                                }
                            }
                        ?>
                        <div class="box-up-brd fill-correct" id="step1_box">
                        @else
                            @if(isset($offer['step_2_completed']) && $offer['step_2_completed'] == 1)
                            <div class="box-up-brd" id="step1_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step1_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="Choose your payment method">2. Choose your payment method</h3>
                                <div class="inqry-box">
                                    <form enctype="multipart/form-data" method="POST">
                                        <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                        <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                        <input type="hidden" name="step_1_completed" value="1"/>
                                        <div class="filling-bx">
                                            <div class="check-option">
                                                <input id="all-case" type="radio" value='All cash' name="payment_method" id="payment_method" <?php if(isset($offer['payment_method']) && $offer['payment_method'] == 'All cash'){echo 'checked="checked"';} ?>/>
                                                <div class="check-btn"></div>
                                                <label for="all-case">All cash</label>
                                            </div>
                                            <div class="check-option">
                                                <input id="mortgages" type="radio" value='Mortgage' name="payment_method" id="payment_method" data-toggle="collapse" data-target="#pay-mnt-option" aria-expanded="false" aria-controls="pay-mnt-option" <?php if(isset($offer['payment_method']) && $offer['payment_method'] == 'Mortgage'){echo 'checked="checked"';} ?>>
                                                <div class="check-btn"></div>
                                                <label for="mortgages">Mortgage</label>
                                            </div>
                                        </div>
                                        <!--  Mortgage is not yet approved or not by the bank section-->
                                        <!--start radio button of mortgages option-->
                                        <div class="collapse filling-bx" id="pay-mnt-option">
                                            <div class="pad-left">
                                                <div class="check-option">
                                                    <input id="not-aprvd" type="radio" name="payment_method_mortgage" data-toggle="collapse" data-target="#collapseExample_nt-ap" aria-expanded="false" aria-controls="collapseExample_nt-ap" value="Not Approved" <?php if(isset($offer['payment_method_mortgage']) && $offer['payment_method_mortgage'] == 'Not Approved'){echo 'checked="checked"';} ?>>
                                                    <div class="check-btn"></div>
                                                    <label for="all-case">Mortgage is not yet approved by the bank</label>
                                                </div>
                                                <div class="collapse gray-clr-bg" id="collapseExample_nt-ap">
                                                    <div class="card-block">
                                                        <h4 class="check-head">Enter your downpayment</h4>
                                                        <div class="range-slider">
                                                            <div class="rang-sld">
                                                                <?php
                                                                    $max = (80 * $property['price']) / 100;
                                                                    $steps = round((1 * $max) / 80);
                                                                ?>
                                                                <input class="range-slider__range" type="range" step="1" type="range" value="{{$dp_amount_percentage}}" min="0" max="100" id="payment_method_mortgage_dp_amount_not_approved" name="payment_method_mortgage_dp_amount_not_approved"/>
                                                                <span class="range-slider__value range-slider__value_custom dp_amount">{{$max}}</span>
                                                                <span class="range_percentage" id="amount_mortgage_not_approved" style="right: 50px;top:9px !important;">{{$amount_mortgage}}</span>
                                                            </div>
                                                            <span class="rmning-sec rmning-price" id="remaining_mortgage_not_approved">{{$remaining_mortgage}}</span>
                                                            <div class="center-bx">
                                                                <h4 class="check-head">Please Upload your mortage confirmation <br> certificate issued by your bank</h4>
                                                                <div class="buttons text-center">
                                                                    <div class="btn-round">
                                                                        <input type="file" name="mortgage_certificate_1"/> upload Certificate</div>
                                                                    <span class="right-btin close-btn" onclick=return mortgage();>Cancel</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="check-option">
                                                    <input id="aprvd" type="radio" name="payment_method_mortgage" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" value="Approved" <?php if(isset($offer['payment_method_mortgage']) && $offer['payment_method_mortgage'] == 'Approved'){echo 'checked="checked"';} ?>>
                                                    <div class="check-btn"></div>
                                                    <label for="mortgages">Mortgage is approved by the bank</label>
                                                </div>
                                            </div>
                                            <!--end mortgage option button-->
                                            <div class="collapse gray-clr-bg" id="collapseExample">
                                                <div class="card-block">
                                                    <h4 class="check-head">Enter your downpayment</h4>
                                                    <div class="range-slider">
                                                        <div class="rang-sld">
                                                            <input class="range-slider__range" type="range" step="1" type="range" value="{{$dp_amount_percentage}}" min="0" max="100" id="payment_method_mortgage_dp_amount_approved" name="payment_method_mortgage_dp_amount_approved"/>
                                                            <span class="range-slider__value range-slider__value_custom dp_amount">{{$max}}</span>
                                                            <span class="range_percentage" id="amount_mortgage_approved" style="right: 50px;top:9px !important;">{{$amount_mortgage}}</span>
                                                        </div>
                                                        <span class="rmning-sec rmning-price" id="remaining_mortgage_approved">{{$remaining_mortgage}}</span>
                                                        <div class="center-bx">
                                                            <h4 class="check-head">Please Upload your mortage confirmation <br> certificate issued by your bank</h4>
                                                            <div class="buttons text-center">
                                                                <div class="btn-round">
                                                                    <input type="file" name="mortgage_certificate_2"/> upload Certificate</div>
                                                                <span class="right-btin close-btn" onclick=return mortgage();>Cancel</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!--end of  Mortgage is not yet approved or not by the bank section-->
                                        <div class="buttons">
                                            <button class="btn-round" type="button" onclick="return step1_save(this.form);">
                                                Confirm payment method
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <!--end of working method-->
                                <p class="success-txt" id="step1_success">You've chosen the payment method (<b id='step1_success_text'>{{$payment_method}}, {{$dp_amount_percentage}}% down payment.</b>) <span class="" style="cursor: pointer;text-decoration: underline;margin-left: 2px;" onclick="return step1_cancel();">Cancel</span></p>
                            </div>
                        </div>
                        @elseif($property['property_deal'] == "RENT")
                        <?php
                            $payment_method = '';
                        ?>
                        @if(isset($offer['step_1_completed']) && $offer['step_1_completed'] == 1)
                        <?php
                            if(isset($offer['payment_method'])) {
                                $payment_method = $offer['payment_method'];
                            }
                        ?>
                        <div class="box-up-brd fill-correct" id="step1_box">
                        @else
                            @if(isset($offer['step_2_completed']) && $offer['step_2_completed'] == 1)
                            <div class="box-up-brd" id="step1_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step1_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="Choose your payment method">2. Choose your payment method</h3>
                                <div class="inqry-box">
                                    <form enctype="multipart/form-data" method="POST">
                                        <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                        <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                        <input type="hidden" name="step_1_completed" value="1"/>
                                        <div class="filling-bx">
                                            <div class="check-option">
                                                <input id="pay-card" type="radio" value="Credit / Debit Card" name="payment_method" <?php if(isset($offer['payment_method']) && $offer['payment_method'] == 'Credit / Debit Card'){echo 'checked="checked"';} ?>/>
                                                <div class="check-btn"></div>
                                                <label for="pay-card">Credit / Debit Card</label>
                                            </div>
                                            <div class="check-option">
                                                <input id="direct-pay" type="radio" value="Direct from Bank" name="payment_method"  data-toggle="collapse" data-target="#proof_bnk" aria-expanded="false" aria-controls="proof_bnk" <?php if(isset($offer['payment_method']) && $offer['payment_method'] == 'Direct from Bank'){echo 'checked="checked"';} ?>>
                                                <div class="check-btn"></div>
                                                <label for="direct-pay">Direct payment from bank</label>
                                            </div>
                                            <!--  Direct payment from bank section-->
                                            <div class="collapse gray-clr-bg" id="proof_bnk">
                                                <div class="card-block">
                                                    <h4 class="check-head">Please add a proof of income / funds, it can be your salary<br>confirmation or a bank statement (PDF or JPG format)</h4>
                                                    <div class="buttons text-center">
                                                        <div class="btn-round">
                                                            <input type="file" name="proof_of_income"/> upload proof of income</div>
                                                            <span class="right-btin close-btn" onclick=return mortgage();>Cancel</span>
                                                    </div>
                                                </div>
                                                <!-- End Direct payment from bank section-->
                                            </div>
                                            <div class="buttons">
                                                <button class="btn-round" type="button" onclick="return step1_save(this.form);">
                                                    Confirm payment method
                                                </button>
                                            </div>
                                        </div>
                                        <!--end of working method-->
                                    </form>
                                </div>
                                <!--end of inquiry method-->
                                <p class="success-txt" id="step1_success">You've chosen the payment method <strong>(<b id='step1_success_text'>{{$payment_method}}</b>)</strong>. <span class="" style="cursor: pointer;text-decoration: underline;margin-left: 2px;" onclick="return step1_cancel();">Cancel</span></p>
                            </div>
                        </div>
                        @endif
                        <!--End buying text section-->
                        @if($property['property_deal'] == "SALE")
                        @if(isset($offer['step_3_completed']) && $offer['step_3_completed'] == 1)
                        <?php
                            $name = $offer['customer_name'];
                            $phone = $offer['customer_phone'];
                            $email = $offer['customer_email'];
                        ?>
                        <div class="box-up-brd fill-correct" id="step3_box">
                        @else
                            <?php
                                $name = '';
                                $phone = '';
                                $email = '';
                            ?>
                            @if(isset($offer['step_1_completed']) && $offer['step_1_completed'] == 1)
                            <div class="box-up-brd" id="step3_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step3_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="Confirm your contact info">3. Confirm your contact info</h3>
                                <div class="inqry-box">
                                    <form enctype="multipart/form-data" method="POST">
                                        <div class="filling-bx">
                                            <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                            <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                            <input type="hidden" name="step_3_completed" value="1"/>
                                            <div class="form-group flt-left">
                                                <label for="customer_name">Your name</label>
                                                <input type="text" class="form-control" placeholder="Diego" name="customer_name" id="customer_name" value="{{$name}}">
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="customer_phone">Phone</label>
                                                <input type="tel" class="form-control" placeholder="+34 438 234 2340" name="customer_phone" value="{{$phone}}" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false; else return true;" id="customer_phone">
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="customer_email">Email</label>
                                                <input type="tel" class="form-control" placeholder="diegocarreras129@gmail.com" value="{{$email}}" name="customer_email" id="customer_email">
                                            </div>
                                            <!--end form-->
                                        </div>
                                        <div class="buttons">
                                            <button class="btn-round" type="button" onclick="return step3_save(this.form, 5);">
                                                Confirm info
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <p class="success-txt" id="step3_success"> You confirmed contact info
                                    <span class="bold">(<span id="name">{{$name}}</span>,
                                    <span id="numbrs">{{$phone}}</span>,
                                    <span id="mail-id">{{$email}}</span>)</span> <span class="right-btin close-btn" onclick="return step3_cancel(5);">Cancel</span></p>
                            </div>
                        </div>
                        @else
                        <!--end second renting  section-->
                        @if(isset($offer['step_3_completed']) && $offer['step_3_completed'] == 1)
                        <?php
                            $name = $offer['customer_name'];
                            $phone = $offer['customer_phone'];
                            $email = $offer['customer_email'];
                        ?>
                        <div class="box-up-brd fill-correct" id="step3_box">
                        @else
                            <?php
                                $name = '';
                                $phone = '';
                                $email = '';
                            ?>
                            @if(isset($offer['step_2_completed']) && $offer['step_2_completed'] == 1)
                            <div class="box-up-brd" id="step3_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step3_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="Confirm your contact info">3. Confirm your contact info</h3>
                                <div class="inqry-box">
                                    <form enctype="multipart/form-data" method="POST">
                                        <div class="filling-bx">
                                            <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                            <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                            <input type="hidden" name="step_3_completed" value="1"/>
                                            <div class="form-group flt-left">
                                                <label for="customer_name">Your name</label>
                                                <input type="text" class="form-control" placeholder="Diego" name="customer_name" id="customer_name" value="{{$name}}">
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="customer_phone">Phone</label>
                                                <input type="tel" class="form-control" placeholder="+34 438 234 2340" name="customer_phone" value="{{$phone}}" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false; else return true;" id="customer_phone">
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="customer_email">Email</label>
                                                <input type="tel" class="form-control" placeholder="diegocarreras129@gmail.com" value="{{$email}}" name="customer_email" id="customer_email">
                                            </div>
                                            <!--end form-->
                                        </div>
                                        <div class="buttons">
                                            <button class="btn-round" type="button" onclick="return step3_save(this.form, 4);">
                                                Confirm info
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <p class="success-txt" id="step3_success"> You confirmed contact info
                                    <span class="bold">(<span id="name">{{$name}}</span>,
                                    <span id="numbrs">{{$phone}}</span>,
                                    <span id="mail-id">{{$email}}</span>)</span> <span class="right-btin close-btn" onclick="return step3_cancel(4);">Cancel</span></p>
                            </div>
                        </div>
                        @endif

                        @if($property['property_deal'] == "RENT")
                        @if(isset($offer['step_4_completed']) && $offer['step_4_completed'] == 1)
                        <div class="box-up-brd fill-correct" id="step4_box">
                        @else
                            @if(isset($offer['step_3_completed']) && $offer['step_3_completed'] == 1)
                            <div class="box-up-brd" id="step4_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step4_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="Rental Period">4. Rental Period</h3>
                                <form enctype="multipart/form-data" method="POST">
                                    <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                    <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                    <input type="hidden" name="step_4_completed" value="1"/>
                                    <div class="inqry-box">
                                        <p>Please select your rental period.</p>
                                        <div class="filling-bx">
                                            <div class="form-group flt-left">
                                                <label for="rental_period">Period</label>
                                                <div class="left-form">
                                                    <select name="rental_period" id="rental_period" class="form-control">
                                                        <option value="1" @if(isset($offer) && $offer->rental_period == 1) selected @endif>1 Year</option>
                                                        <option value="2" @if(isset($offer) && $offer->rental_period == 2) selected @endif>3 Year</option>
                                                        <option value="3" @if(isset($offer) && $offer->rental_period == 3) selected @endif>&gt; 3 Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buttons select-btn">
                                        <button class="btn-round" type="button" onclick="return step4_save_1(this.form);">
                                            Confirm Period
                                        </button>
                                    </div>
                                    <p class="success-txt" id="step4_success"> You selected rental period @if(isset($offer['rental_period']) && $offer['rental_period'] == 1)
                                        1 Year
                                    @elseif(isset($offer['rental_period']) && $offer['rental_period'] == 2)
                                        3 Year
                                    @elseif(isset($offer['rental_period']) && $offer['rental_period'] == 3)
                                        &gt; 3 Year
                                    @endif. <span class="right-btin close-btn" onclick="return step4_cancel();">Cancel</span></p>
                                </form>
                            </div>
                        </div>
                        @elseif($property['property_deal'] == "SALE")
                        @endif
                        <!--End buying Date section-->
                        @if(isset($offer['step_5_completed']) && $offer['step_5_completed'] == 1)
                        <?php

                        ?>
                        <div class="box-up-brd fill-correct" id="step5_box">
                        @else
                            @if(isset($offer['step_4_completed']) && $offer['step_4_completed'] == 1)
                            <div class="box-up-brd" id="step5_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step5_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                @if($property['property_deal'] == "SALE")
                                <h3 class="check-head" data-title="View the engagement contract prototype">{{($property['property_deal'] == "RENT")?"5":"4"}}. View the engagement contract prototype</h3>
                                <div class="inqry-box">
                                    <p>You can download and view sample contract for your reference. both the engagement and acquisition contracts will be signed with your seller at Loquare headquarters</p>
                                @elseif($property['property_deal'] == "RENT")
                                <h3 class="check-head" data-title="View the rental agreement prototype">{{($property['property_deal'] == "RENT")?"5":"4"}}. View the rental agreement prototype</h3>
                                <div class="inqry-box">
                                    <p>You can download and view sample rental afreement for your refrence. the actual rental agreement will be signed with your renter at Loquare headquarters.</p>
                                @endif
                                    <form enctype="multipart/form-data" method="POST">
                                        <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                        <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                        <input type="hidden" name="step_5_completed" value="1"/>
                                        <div class="buttons">
                                            @if($property['property_deal'] == "SALE")
                                            <button class="btn-round" type="button" onclick="window.location.href='{{url('/download/contract/selling')}}';return step5_save(this.form);">
                                            @elseif($property['property_deal'] == "RENT")
                                            <button class="btn-round" type="button" onclick="window.location.href='{{url('/download/contract/rental')}}';return step5_save(this.form);">
                                            @endif
                                                View pre-contract
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                @if($property['property_deal'] == "SALE")
                                <p class="success-txt" id="step5_success">You downloaded pre-contract. Both the engagement and acquisition contracts will be signed with your seller at Loquare headquarters <a href="{{url('/download/contract/selling')}}" id="download_contract" class="right-btin close-btn">Re-download contract</a></p>
                                @elseif($property['property_deal'] == "RENT")
                                <p class="success-txt" id="step5_success">You can <a href="{{url('/download/contract/rental')}}" id="download_contract" class="right-btin close-btn">download</a> and view sample rental agreement for your refrence. The actual rental agreement will be signed with your renter at Loquare headquarters</p>
                                @endif
                            </div>
                        </div>
                        <!--End buying fifth section section-->
                        @if(isset($offer['step_6_completed']) && $offer['step_6_completed'] == 1)
                        <?php

                        ?>
                        <div class="box-up-brd fill-correct" id="step6_box">
                        @else
                            @if(isset($offer['step_5_completed']) && $offer['step_5_completed'] == 1)
                            <div class="box-up-brd" id="step6_box">
                            @else
                            <div class="box-up-brd div_disabled" id="step6_box">
                            @endif
                        @endif
                            <div class="check-box-wrp">
                                <h3 class="check-head" data-title="{{($property['property_deal'] == "RENT")?"6":"5"}}. Provide imformation of @if($property['property_deal'] == "SALE") buyer/s @elseif($property['property_deal'] == "RENT") renter/s  @endif">{{($property['property_deal'] == "RENT")?"6":"5"}}. Provide imformation of @if($property['property_deal'] == "SALE") buyer/s @elseif($property['property_deal'] == "RENT") renter/s  @endif</h3>
                                <div class="filling-bx in-gray">
                                    <div class="sec-nm-box">@if($property['property_deal'] == "SALE") BUYER @elseif($property['property_deal'] == "RENT") RENTER  @endif #1</div>
                                    <!--start form-->
                                    @if(isset($offer['first_name_1']) && $offer['first_name_1'] != '')
                                    <form id="box_1" enctype="multipart/form-data" method="POST" style="display:none;">
                                    @else
                                    <form id="box_1" enctype="multipart/form-data" method="POST">
                                    @endif
                                        <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                        <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                        <input type="hidden" name="step_6_completed" value="1"/>
                                        <div class="form-group flt-left">
                                            <label for="frst-nm">First name</label>
                                            <input type="text" class="form-control" id="first_name_1" placeholder="Diego" name="first_name_1" value="{{isset($offer['first_name_1'])?$offer['first_name_1']:''}}">
                                        </div>
                                        <div class="form-group flt-left">
                                            <label for="last-nm">Last name</label>
                                            <input type="text" class="form-control" id="last_name_1" placeholder="Sanchez" name="last_name_1" value="{{isset($offer['last_name_1'])?$offer['last_name_1']:''}}">
                                        </div>
                                        <div class="form-group flt-left file-box">
                                            <label for="Your-nm3">Passport</label>
                                            <div class="left-form">
                                                <div class="file-btn">
                                                    <div id="drop_zone" class="drop-zone passport_cst">
                                                        @if(isset($offer['photo_1']) && $offer['photo_1'] != '')
                                                            <img src="{{asset('/offer-storage/'.$offer['photo_1'])}}"/>
                                                        @else
                                                            <h3 class="title"><span>+</span>add picture</h3>
                                                            <div class="preview-container"></div>
                                                        @endif
                                                    </div>
                                                    <input id="file_input" accept="image/*" type="file" multiple="" name="photo_1">
                                                </div>
                                                <div class="buttons">
                                                    <button class="btn-round" type="button" onclick="return step6_save_1(this.form);">
                                                        Confirm info
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end form-->
                                    </form>
                                    @if(isset($offer['first_name_1']) && $offer['first_name_1'] != '')
                                    <p id="step6_success_buyer" style="display:block;">You confirmed @if($property['property_deal'] == "SALE") buyer's @elseif($property['property_deal'] == "RENT") renter's  @endif info <span class="right-btin close-btn" onclick="return step6_cancel_1();">Cancel</span></p>
                                    @else
                                    <p id="step6_success_buyer" style="display:none;">You confirmed @if($property['property_deal'] == "SALE") buyer's @elseif($property['property_deal'] == "RENT") renter's  @endif info <span class="right-btin close-btn" onclick="return step6_cancel_1();">Cancel</span></p>
                                    @endif
                                </div>
                                @if(isset($offer['first_name_2']) && $offer['first_name_2'] != '')
                                <div class="filling-bx in-gray co-buys">
                                @else
                                <div class="filling-bx in-gray hide co-buys">
                                @endif
                                    <div class="sec-nm-box">co-@if($property['property_deal'] == "SALE") buyer @elseif($property['property_deal'] == "RENT") renter  @endif</div>
                                    <!--start form-->

                                    @if(isset($offer['first_name_2']) && $offer['first_name_2'] != '')
                                    <form id="box_2" enctype="multipart/form-data" method="POST" class="hide">
                                    @else
                                    <form id="box_2" enctype="multipart/form-data" method="POST">
                                    @endif
                                        <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                        <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                        <input type="hidden" name="step_6_completed" value="1"/>
                                        <div class="form-group flt-left">
                                            <label for="frst-nm">First name</label>
                                            <input type="text" class="form-control" id="first_name_2" placeholder="Diego" name="first_name_2" value="{{isset($offer['first_name_2'])?$offer['first_name_2']:''}}">
                                        </div>
                                        <div class="form-group flt-left">
                                            <label for="last-nm">Last name</label>
                                            <input type="text" class="form-control" id="last_name_2" placeholder="Sanchez" name="last_name_2" value="{{isset($offer['last_name_2'])?$offer['last_name_2']:''}}">
                                        </div>
                                        <div class="form-group flt-left file-box">
                                            <label for="Your-nm">Passport</label>
                                            <div class="left-form">
                                                <div class="file-btn">
                                                    <div id="drop_zone_nw" class="drop-zone_nw passport_cst">
                                                        @if(isset($offer['photo_2']) && $offer['photo_2'] != '')
                                                            <img src="{{asset('/offer-storage/'.$offer['photo_2'])}}"/>
                                                        @else
                                                            <h3 class="title"><span>+</span>add picture</h3>
                                                            <div class="preview-container"></div>
                                                        @endif
                                                    </div>
                                                    <input id="file_input_nw" accept="image/*" type="file" multiple="" name="photo_2">
                                                </div>
                                                <div class="buttons">
                                                    <button class="btn-round" type="button" onclick="return step6_save_2(this.form);">
                                                        Confirm info
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end form-->
                                    </form>
                                    @if(isset($offer['first_name_2']) && $offer['first_name_2'] != '')
                                    <p id="step6_success_cobuyer" style="display:block;">You confirmed @if($property['property_deal'] == "SALE") buyer's @elseif($property['property_deal'] == "RENT") renter's  @endif info <span class="right-btin close-btn" onclick="$('#box_2').removeClass('hide');return step6_cancel_2();">Cancel</span></p>
                                    @else
                                    <p id="step6_success_cobuyer" style="display:none;">You confirmed @if($property['property_deal'] == "SALE") buyer's @elseif($property['property_deal'] == "RENT") renter's  @endif info <span class="right-btin close-btn" onclick="$('#box_2').removeClass('hide');return step6_cancel_2();">Cancel</span></p>
                                    @endif
                                </div>
                                @if(isset($offer['first_name_2']) && $offer['first_name_2'] != '')
                                <a class="add-sec-btn co-rlt">
                                    <p>- remove a co-@if($property['property_deal'] == "SALE") buyer @elseif($property['property_deal'] == "RENT") renter  @endif</p>
                                    <p onclick="">+ add a co-@if($property['property_deal'] == "SALE") buyer @elseif($property['property_deal'] == "RENT") renter  @endif</p>
                                </a>
                                @else
                                <a class="add-sec-btn co-rlt">
                                    <p>+ add a co-@if($property['property_deal'] == "SALE") buyer @elseif($property['property_deal'] == "RENT") renter  @endif</p>
                                    <p>- remove a co-@if($property['property_deal'] == "SALE") buyer @elseif($property['property_deal'] == "RENT") renter  @endif</p>
                                </a>
                                @endif
                            </div>
                        </div>
                        @if($property['property_deal'] == "SALE")
                        <form id="step7" action="{{url('save/offer')}}" enctype="multipart/form-data" method="POST">
                            @if(isset($offer['step_6_completed']) && $offer['step_6_completed'] == 1)
                                @if(isset($offer['step_7_completed']) && $offer['step_7_completed'] == 1)
                                    <div class="box-up-brd fill-correct" id="step7_box">
                                @else
                                    <div class="box-up-brd" id="step7_box">
                                @endif
                            @else
                                <div class="box-up-brd div_disabled" id="step7_box">
                            @endif
                                <div class="check-box-wrp">
                                    <h3 class="check-head" data-title="Suggest date and time to sign the contract">{{($property['property_deal'] == "RENT")?"7":"6"}}. Suggest date and time to sign the contract</h3>
                                    <p>Please suggest three time slots that works for you within few days to meet at motary office, review and sign the contract.</p>
                                    <div class="filling-bx inqry-box" style="display: block;">
                                        <!--start form-->
                                            {{csrf_field()}}
                                            <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                                            <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                                            <input type="hidden" name="step_7_completed" value="1"/>
                                            <input type="hidden" name="status" value="1"/>
                                            <div class="form-group flt-left">
                                                <label for="Your-nm">Date and time</label>
                                                <div class="left-form">
                                                    <div id="datetimepicker2" class="drop-icon">
                                                        <input type="text" name="signature_schedule_1" class="add-on form-control" data-format="dd/MM/yyyy hh:mm:ss" placeholder="- select date and time -" value="{{isset($offer['signature_schedule_1'])?$offer['signature_schedule_1']:''}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="Your-nm">Date and time</label>
                                                <div class="left-form">
                                                    <div id="datetimepicker3" class="drop-icon">
                                                        <input type="text" name="signature_schedule_2" class="add-on form-control" data-format="dd/MM/yyyy hh:mm:ss" placeholder="- select date and time -" value="{{isset($offer['signature_schedule_2'])?$offer['signature_schedule_2']:''}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group flt-left">
                                                <label for="Your-nm">Date and time</label>
                                                <div class="left-form">
                                                    <div id="datetimepicker4" class="drop-icon">
                                                        <input type="text" name="signature_schedule_3" class="add-on form-control" data-format="dd/MM/yyyy hh:mm:ss" placeholder="- select date and time -" value="{{isset($offer['signature_schedule_3'])?$offer['signature_schedule_3']:''}}"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--end form-->
                                    </div>
                                </div>
                            </div>
                            <!--end of seventh section-->
                            <div class="box-up-brd">
                                Once you added all the information above you can click "I'm ready to purchase" button to submit all the information and set up signing up date and time.
                            </div>
                            <!--end of last section-->
                            <div class="button-next">
                                @if(isset($offer['step_6_completed']) && $offer['step_6_completed'] == 1)
                                    <span style="cursor: pointer;" id="step7_submit" class="grn-full-btn" onclick="$('#step7').submit();">I'm ready to {{($property['property_deal'] == "RENT")?"rent":"purchase"}} <img src="{{asset('offer/images/angel-right.png')}}" alt=""></span>
                                @else
                                    <span style="cursor: pointer;display:none;" id="step7_submit" class="grn-full-btn" onclick="$('#step7').submit();">I'm ready to {{($property['property_deal'] == "RENT")?"rent":"purchase"}} <img src="{{asset('offer/images/angel-right.png')}}" alt=""></span>
                                @endif
                            </div>
                            @endif
                        </form>
                        @endif

                        @if($property['property_deal'] == "RENT" && !isset($offer_completed))
                        <form id="step7" action="{{url('save/offer')}}" enctype="multipart/form-data" method="POST">
                            {{csrf_field()}}
                            <input type="hidden" name="asset_id" value="{{$property_id}}"/>
                            <input type="hidden" name="id" class="offer_id" value="{{(isset($offer))?$offer['id']:''}}"/>
                            <input type="hidden" name="step_7_completed" value="1"/>
                            <input type="hidden" name="status" value="1"/>
                            <div class="button-next">
                                @if(isset($offer['step_6_completed']) && $offer['step_6_completed'] == 1)
                                    @if(isset($offer['step_7_completed']) && $offer['step_7_completed'] == 1)
                                    <span style="cursor: pointer;display:none;" id="step7_submit" class="grn-full-btn" onclick="$('#step7').submit();">I'm ready to {{($property['property_deal'] == "RENT")?"rent":"purchase"}} <img src="{{asset('offer/images/angel-right.png')}}" alt=""></span>
                                    @else
                                    <span style="cursor: pointer;" id="step7_submit" class="grn-full-btn" onclick="$('#step7').submit();">I'm ready to {{($property['property_deal'] == "RENT")?"rent":"purchase"}} <img src="{{asset('offer/images/angel-right.png')}}" alt=""></span>
                                    @endif
                                @else
                                    <span style="cursor: pointer;display:none;" id="step7_submit" class="grn-full-btn" onclick="$('#step7').submit();">I'm ready to {{($property['property_deal'] == "RENT")?"rent":"purchase"}} <img src="{{asset('offer/images/angel-right.png')}}" alt=""></span>
                                @endif
                            </div>
                        </form>
                        @endif

                    </div>
                </div>
                <!--end of white box-->
            </div>
            <!--end of col-->
            <div class="col-md-5 right-section-view">
                <div class="white-body no-pad" style="position: relative;">
                    <div class="img-rooms">
                        <?php foreach($property['images'] as $image) {?>
                            <div class="img-gallry layer-rms">
                                <img style="height: 281px !important;" src="<?php if(Storage::disk('s3')->exists("Properties/".$property['id']."/".$image['filename'])){ echo Storage::disk('s3')->url("Properties/".$property['id']."/".$image['filename']);}else{ echo asset('/storage/Property/'.$property['id'].'/'.$image['filename']);} ?>" alt="">
                                <div class="overlay">
                                    <h4><a href="{{url('property/detail')}}/{{$property_id}}" style="color:#FFF;"><?php echo  $property['direccion']; ?></a> <span>&euro; <?php echo (trim($property['property_deal']) == "SALE")?$property['price']:$property['price']."/mo"; ?></span></h4>
                                    <p>&euro; {{(trim($property['property_deal']) == "SALE")?$property['price']:$property['price']."/mo"}} | {{isset($property['rooms'])?$property['rooms']:''}} bed, {{isset($property['bathrooms'])?$property['bathrooms']:''}} baths, {{isset($property['sizem2'])?$property['sizem2']:''}} m2</p>
                                    <span class="icon-zoom" style="position:absolute;font-size:22px;bottom:0px;right:0px;cursor:pointer;color:#fff; width:40px;height:40px;display:inline-block;line-height:1;background: rgba(0,0,0,0.5);padding:9px 14px;"><i class="fa fa-map-marker"><!-- --></i></span>
                                </div>
                            </div>
                         <?php } ?>

                    </div>
                    <!-- map sec start-->
                    <div class="img-map" style="position: absolute;top:0;left:0;width:100%;z-index:99;display:none;">
                        <div class="street-view" style="background: #ccc;height: 281px !important;">
                            <div id="street-map"></div>
                        </div>
                        <span class="icon-zoom" style="z-index: 9;position:absolute;font-size:22px;bottom:0px;right:0px;cursor:pointer;color:#fff; width:40px;height:40px;display:inline-block;line-height:1;background: rgba(0,0,0,0.5);padding:5px 9px;"><i class="fa fa-image"><!-- --></i></span>
                    </div>
                     <!-- map sec start-->
                    <!--end of slider-->

					<?php if($property['property_deal'] =='RENT') { ?>
						 <div class="box-up-brd">
						   <div class="st-label"> Commission:</div>
							<ul class="chart_tooltip">
								<li class="green">LOQUARE</li>
								<li class="orange">STANDARD</li>
							</ul>
							<canvas id="commission_graph_offer" height="50%" width="100%"></canvas>
							<input type="hidden" id="loquare_commission" name="loquare_commission" />
							<input type="hidden" id="realestate_commission" name="realestate_commission" />
							<input type="hidden" id="property_price" name="property_price" value='{{$property["price"]}}' />
						</div>
					<?php } ?>
                </div>
            </div>
        </div>
        <!--end of col-->
    </div>
</div>
@if(isset($offer_completed) && $offer_completed == 1)
    <div class="pop-ups-all">
        <div class="done-form">
            <img src="{{asset('offer/img/check-mark.png')}}" alt="">
            <div class="appont-lst">
                Your offer was succesfully submitted to the owner. As soon as they respond you will get an e-mail with either confirmation of the price or counter-offer.
                We'll also reach out to you to confirm the date and time to see the apartment.
            </div>
            <div class="buttons text-center">
                <a href="{{url('property/detail')}}/{{$property_id}}" class="btn-round">Ok</a>
            </div>
        </div>
    </div>
@endif
<div class="loader" id="loader">
    <div class="line"></div>
    <div class="line"></div>
    <div class="line"></div>
    <div class="line"></div>
</div>

<!--modal start-->
<div class="modal fade" id="letusModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <form method="POST" action="{{url('/offer/send/query')}}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Let us know</h4>
        </div>
            {{csrf_field()}}
            <input type="hidden" name="asset_id" value="{{$property_id}}"/>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="comment">Post your query :</label>
                        <textarea name="query" class="form-control" rows="5" style="max-width: 100%;min-width: 100%;" id="comment"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default">Submit</button>
                <button type="button" class="btn btn-default cancel_btn" data-dismiss="modal">Cancel</button>
            </div>
      </div>
      </form>
    </div>
</div>
<!--modal end-->

@endsection
@section('script')
<script type="text/javascript">
</script>
<script src="{{ asset('offer/js/slick.js') }}" type="text/javascript"></script>
<script src="{{ asset('offer/js/bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('offer/js/theme_js.js') }}" type="text/javascript"></script>
<script src="{{ asset('offer/js/jquery.smartuploader.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('offer/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('offer/js/custom.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('offer/js/create-offer.js') }}"></script>
<script type="text/javascript">

$(document).ready(function(){
    //loadScript("googlemapfunction");
});
var latitude = "<?php echo $property['latitude']; ?>";
var longitude = "<?php echo $property['longitude']; ?>";
function googlemapfunction()
{
    panoramacenter =  new google.maps.LatLng(longitude,latitude);

    panorama = new google.maps.Map(document.getElementById('street-map'),
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
}
  $(document).ready(function(){
    var dateToday = new Date();
    $('#datetimepicker1').datetimepicker({
      format: 'dd/MM/yyyy hh:mm',
      language: 'en',
      pick12HourFormat: true,
      pickSeconds: false,
      startDate : dateToday
    });

    $('#datetimepicker2').datetimepicker({
      format: 'dd/MM/yyyy hh:mm',
      language: 'en',
      pick12HourFormat: true,
      pickSeconds: false,
      startDate: dateToday
    });

    $('#datetimepicker3').datetimepicker({
      format: 'dd/MM/yyyy hh:mm',
      language: 'en',
      pick12HourFormat: true,
      pickSeconds: false,
      startDate: dateToday
    });

    $('#datetimepicker4').datetimepicker({
      format: 'dd/MM/yyyy hh:mm',
      language: 'en',
      pick12HourFormat: true,
      pickSeconds: false,
      startDate: dateToday
    });

  });
</script>

<script type="text/javascript">
  $('#datetimepicker_hd').datetimepicker({
    format: 'dd/MM/yyyy hh:mm',
    language: 'en',
    pick12HourFormat: true,
    pickSeconds: false,
  });
</script>

<script type="text/javascript">
  $('.img-rooms').slick();
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#file_input").withDropZone("#drop_zone", {
      action: {
        name: "image",
        params: {
          preview: true,
        }
      },
    });

    $("#file_input_nw").withDropZone("#drop_zone_nw", {
      action: {
        name: "image",
        params: {
          preview: true,
        }
      },
    });

    jQuery( ".ad-dte" ).click(function() {
      jQuery( this ).toggleClass( "highlight" );
      jQuery("#datetimepicker_hd").toggleClass( "hide");
    });

    jQuery( ".co-rlt" ).click(function() {
      jQuery( this ).toggleClass("highlight");
      jQuery(".co-buys").toggleClass( "hide");
    });

    jQuery( ".close-btn" ).click(function() {
      jQuery(".box-up-brd  .collapse").removeClass( "in");
    });

  });

function saveData(form)
{
    var data = new FormData(form);
    var token = '<?php echo csrf_token() ?>';
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type: "POST",
        url: "{{url('save/offer')}}",
        data: data,
        processData: false,
        contentType: false,
        success: function( data ) {
            $('.offer_id').val(data.offer_id);
        }
    });
}

function saveDpAmount(amount, id)
{
    var token = '<?php echo csrf_token() ?>';
    $.ajax({
        headers: { 'X-CSRF-TOKEN': token },
        type: "POST",
        url: "{{url('save/offer/dpamount')}}",
        data: {'payment_method_mortgage_dp_amount':amount, 'id' : id},
        success: function( data ) {

        }
    });
}

</script>

<script>
function goBack() {
    window.history.back();
}
<!-- map script-->
$(document).ready(function(){
  $('.icon-zoom').click(function(){
    $('.img-map').toggleClass('show');
    loadScript("googlemapfunction");
  })

  var val = ($('#customer_offer_price').val() - $('#customer_offer_price').attr('min')) / ($('#customer_offer_price').attr('max') - $('#customer_offer_price').attr('min'));
  $('#customer_offer_price').css('background-image',
      '-webkit-gradient(linear, left top, right top, '
      + 'color-stop(' + val + ', #00AB8A), '
      + 'color-stop(' + val + ', #C5C5C5)'
      + ')'
  );

  var val = ($('#payment_method_mortgage_dp_amount_not_approved').val() - $('#payment_method_mortgage_dp_amount_not_approved').attr('min')) / ($('#payment_method_mortgage_dp_amount_not_approved').attr('max') - $('#payment_method_mortgage_dp_amount_not_approved').attr('min'));
  $('#payment_method_mortgage_dp_amount_not_approved').css('background-image',
      '-webkit-gradient(linear, left top, right top, '
      + 'color-stop(' + val + ', #00AB8A), '
      + 'color-stop(' + val + ', #C5C5C5)'
      + ')'
  );

  var val = ($('#payment_method_mortgage_dp_amount_approved').val() - $('#payment_method_mortgage_dp_amount_approved').attr('min')) / ($('#payment_method_mortgage_dp_amount_approved').attr('max') - $('#payment_method_mortgage_dp_amount_approved').attr('min'));
  $('#payment_method_mortgage_dp_amount_approved').css('background-image',
      '-webkit-gradient(linear, left top, right top, '
      + 'color-stop(' + val + ', #00AB8A), '
      + 'color-stop(' + val + ', #C5C5C5)'
      + ')'
  );
});

</script>
@endsection