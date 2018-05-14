@extends('layouts.app')
@section('title', 'Loquare | '.$property["direccion"])
@section('content')
    <main>

        <div class="hidden">
            <input type="hidden" value="<?php echo $property['id']; ?>" id="property_id">
            <input type="hidden" value="<?php echo $property['direccion']; ?>" id="direccion">
            <input type="hidden" value="<?php echo $property['provincia']; ?>" id="provincia">
            <input type="hidden" value="<?php echo $property['rooms']; ?>" id="rooms">
            <input type="hidden" value="<?php echo $property['property_deal']; ?>" id="property_deal">
            <input type="hidden" value="<?php echo $property['bathrooms']; ?>" id="bathrooms">
            <input type="hidden" value="<?php echo $property['price']; ?>" id="price">
            <input type="hidden" value="<?php echo $property['comunidad_autonoma']; ?>" id="comunidad_autonoma">
            <input type="hidden" value="<?php echo $property['cops']; ?>" id="cops">
            <input type="hidden" value="<?php echo $property['latitude']; ?>" id="latitude">
            <input type="hidden" value="<?php echo $property['longitude']; ?>" id="longitude">
            <input type="hidden" id="shcedules" value="<?php
                $dates = [];
                if($schedules->schedules)
                {
                    foreach($schedules->schedules as $date){
                        $dates[] = date('j-n-Y', strtotime($date->scheduled_dates));
                    }
                }
                echo trim(implode(",",$dates));
            ?>" />
        </div>


        <div class="page page--nopad">
            <div class="big-slider">
                <div class="slider_loader"></div>
                <?php foreach($property['images'] as $image) {?>
                <div class="big-slider__item loaded"
                     style="background-image: url(<?php if (Storage::disk('s3')->exists("Properties/" . $property['id'] . "/" . $image['filename'])) {
                         echo Storage::disk('s3')->url("Properties/" . $property['id'] . "/" . $image['filename']);
                     } else {
                         echo asset('/storage/Property/' . $property['id'] . '/' . $image['filename']);
                     }
                     ?>)">
                </div>
                <?php } ?>
            </div>

            <div class="residental">
                <div class="residental__left">
                    <div class="residental__content">
                        <div class="residental__title">
                            <?php echo $property['direccion']; ?>
                            <div class="primery_description">
                                <span> <?php echo $property['property_type']; ?>, </span>
                                <ul class="services">
                                    <li>
                                        <?php echo $property['rooms']; ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512 512"
                                             style="enable-background:new 0 0 512 512;" xml:space="preserve" width="22px" height="22px">
                                            <g>
                                                <g>
                                                    <path d="M8.828,311.908c0,68.025,41.221,126.416,100.046,151.572V512h35.31v-37.933c9.548,1.722,19.381,2.622,29.425,2.622    h164.782c10.045,0,19.877-0.902,29.425-2.622V512h35.31v-48.52c58.825-25.156,100.046-83.548,100.046-151.572H8.828z"
                                                          fill="#5d6872"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <polygon
                                                            points="344.275,229.517 308.966,229.517 308.964,229.517 308.964,276.598 308.966,276.598 344.275,276.598     344.276,276.598 344.276,229.517   "
                                                            fill="#5d6872"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <rect x="238.345" y="229.517" width="35.31" height="47.081" fill="#5d6872"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <rect x="167.724" y="229.517" width="35.31" height="47.081" fill="#5d6872"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M459.521,54.469C429.594,19.344,386.576,0,338.391,0c-55.165,0-100.046,44.881-100.046,100.046v2.226    c-30.459,7.839-52.966,35.489-52.966,68.395c0,13.001,10.539,23.54,23.54,23.54h94.161c13.001,0,23.54-10.539,23.54-23.54    c0-32.906-22.507-60.556-52.966-68.395v-2.226c0-35.695,29.04-64.736,64.736-64.736c76.23,0,129.471,60.501,129.471,147.126    v94.161h35.31v-94.161C503.172,132.955,487.67,87.508,459.521,54.469z"
                                                          fill="#5d6872"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </li>
                                    <li>
                                        <?php echo $property['bathrooms']; ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                             viewBox="0 0 469.333 469.333" style="enable-background:new 0 0 469.333 469.333;" xml:space="preserve" width="22px" height="22px">
                                            <g transform="matrix(1, 0, 0, 1, 0, 75)">
                                                <g>
                                                    <g>
                                                        <path d="M128,245.333c35.307,0,64-28.693,64-64c0-35.307-28.693-64-64-64c-35.307,0-64,28.693-64,64     C64,216.64,92.693,245.333,128,245.333z"
                                                              fill="#5d6872"/>
                                                        <path d="M384,117.333H213.333v149.333H42.667v-192H0v320h42.667v-64h384v64h42.667v-192     C469.333,155.52,431.147,117.333,384,117.333z"
                                                              fill="#5d6872"/>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="residental__id">ID number: <?php echo $property['ref']; ?></div>
                        <div class="residental__price">
                            &euro; <?php echo (trim($property['property_deal']) == "SALE") ? $property['price'] : $property['price'] . "/mo"; ?>
                            <span class="size"> - <?php echo $property['sizem2']; ?>m<sup>2</sup></span>

                            <span class="per_meter">&euro;<?php echo number_format(($property['price'] / $property['sizem2']), 2); ?>/m<sup>2</sup></span>

                            <?php if(isset($offer) && !empty($offer)) { ?>
                            <span class="offer_tag">OFFER : &euro;{{$offer}}{{(trim($property['property_deal']) == "RENT")?"/mo":""}}</span>
                            <?php } ?>
                            <?php if(trim($property['property_deal']) == "RENT" && $property['guarantee'] > 0) { ?>
                            <span class="guarantee_tag">GUARANTEE : &euro;<?php echo $property['guarantee']; ?></span>
                            <?php } ?>
                        </div>
                        <div class="residental__subtitle">Description</div>
                        <div class="residental__desc">
                            <?php echo $property['discription']; ?>
                        </div>
                        <div class="residental__subtitle">Features &amp; Characteristics</div>
                        <div class="residental__features">
                            <?php
                            $features = array(
                                    "furnished" => array(
                                            "name" => "Furnished",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-furniture.svg')
                                    ),
                                    "heating" => array(
                                            "name" => "Heating",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-heating.svg')
                                    ),
                                    "elevetor" => array(
                                            "name" => "Elevator",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-elevator.svg')
                                    ),
                                    "outdoor_space" => array(
                                            "name" => "Outdoor",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-outdoor.svg')
                                    ),
                                    "dishwasher" => array(
                                            "name" => "Dishwasher",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-dishwasher.svg')
                                    ),
                                    "central_ac" => array(
                                            "name" => "Central a/c",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-ac.svg')
                                    ),
                                    "pool" => array(
                                            "name" => "Pool",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-pool.svg')
                                    ),
                                    "doorman" => array(
                                            "name" => "Doorman",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-doorman.svg')
                                    ),
                                    "gym" => array(
                                            "name" => "Gym",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-gym.svg')
                                    ),
                                    "pets" => array(
                                            "name" => "Pets",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-pets.svg')
                                    ),
                                    "dogs" => array(
                                            "name" => "Dogs",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-dogs.svg')
                                    ),
                                    "laundry" => array(
                                            "name" => "Laundry",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-laundry.svg')
                                    ),
                                    "cats" => array(
                                            "name" => "Cat",
                                            "icon" => asset('/frontend/assets/icons/icon-25x25-cat.svg')
                                    ),
                                    "others" => array(
                                            "name" => "Other",
                                            "icon" => ""
                                    )
                            );
                            ?>
                            <?php foreach($features as $feature => $value){ if($property[$feature] == 1) { ?>
                            <div class="residental__feature">
                                <?php if($value['icon'] != "") { ?>
                                <div class="residental__feature-icon">
                                    <img src="<?php echo $value['icon']; ?>"/>
                                </div>
                                <?php } ?>
                                <?php echo $value['name']; ?>
                            </div>
                            <?php }} ?>
                        </div>
                        <?php if($property['property_deal'] == "RENT") { ?>
                        <div class="save_upto">
                            <div class="refund_content">
                                <div class="residental__subtitle">
                                    Rent with us and save - <span class="savings">&euro;<?php
                                    if($property['price'] > 1000)
                                    {
                                        echo $property['price']*0.5;
                                    }
                                    else
                                    {
                                        echo (($property['price']/1000)*0.5)*$property['price'];
                                    }
                                    ?></span>
                                </div>
                                <a href="javascript:void(0)" class="purple">Learn more about renting process <i class="fa fa-caret-right"></i></a>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="residental__right">
                    <div class="visit">
                        <div class="visit__title">Get more info / Schedule Visit</div>
                        <form id="visitor_feedback" action="{{ url('/visitor/feedback') }}" method="post">
                            {{csrf_field()}}
                            <h3>Select Date and time</h3>
                            <div style="text-align: center;">
                                <br>
                                <label for="userName-2">Select schedule day </label>
                                <input type="text" name="schedule_date" id="search-to-date" required/>
                                <br>
                                <label for="userName-2">Select time </label>
                                <input type="text" name="timepicker" class="timepicker"/>

                            </div>
                            <h3>About YourSelf</h3>

                            <div>
                                <label for="userName-2">CONTACT INFO</label>
                                <input type="hidden" value="<?php echo (trim($property['property_deal']) == "SALE") ? $property['price'] : $property['price'] . "/mo"; ?>"
                                       name="visitor_price_with_unit">
                                <input type="hidden" name='visitor_property_id' value="<?php echo $property['id']; ?>">
                                <input type="hidden" value="<?php echo $property['direccion']; ?>" name="visitor_direccion">
                                <div class="visit__row">
                                    <div class="visit__field">
                                        <input type="text" name="name" id="feedback_name" placeholder="Your name" data-msg-required="Please enter your name" required>
                                    </div>
                                </div>
                                <div class="visit__row">
                                    <div class="visit__field">
                                        <input type="text" name="phoneno" id="phoneno" placeholder="Your phone i.e +3412345678/3412345678" required
                                               data-msg-required="Please enter your phone" pattern="[\d{8,10}|[+]{1}\d{8,9}" maxlength="10">
                                    </div>
                                </div>
                                <div class="visit__row">
                                    <div class="visit__field">
                                        <input type="email" name="email" placeholder="Your email" id="feedback_email" required data-msg-required="Please enter your email">
                                    </div>
                                </div>
                                <div class="visit__row">
                                    <div class="visit__field">
                                        <textarea name="get_message" id="feedback_message" required
                                                  placeholder="Tell briefly about yourself, let the owner/realtor know when you want to view the apartment"
                                                  data-msg-required="Please tell briefly about yourself, let the owner/realtor know when you want to view the apartment"></textarea>
                                    </div>
                                </div>

                            </div>


                        </form>
                        <div class="visit__footer">
                            <a href="{{url('create-offer')}}/{{$property['id']}}" class="visit__make">make offer</a>
                            <a href="#save-to-collection" class="visit__save js-popup-open save_to_collection" data-id="<?php echo $property['id']; ?>">save listing</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($property['property_deal'] == "SALE") {?>
            <div class="income">
                <div class="container">
                    <div class="income__cols2">
                        <div class="col">
                            <div class="income__title" data-title="Potential Income">Potential Income</div>
                            <div class="income__big">€<?php echo $property['potential_income']; ?></div>
                        </div>
                        <div class="col">
                            <div class="income__title" data-title="Historical Evolution of Prices">Historical Evolution of Prices</div>
                            <table class="income__table">
                                <tbody>
                                <?php if($historical_prices != false){ foreach($historical_prices as $price){?>
                                <tr>
                                    <td><?php echo date('m/d/Y', strtotime($price->created_at)); ?></td>
                                    <td><b>€<?php echo $price->price; ?></b></td>
                                    <td>
                                        <?php echo Loquare::dateDifference(date('Y-m-d H:i:s'), $price->created_at) . " ago"; ?>
                                    </td>
                                </tr>
                                <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="income__cols2">
                        <div class="col">
                            <div class="income__title" data-title="Estimated Costs">Estimated Costs</div>
                            <table class="income__table income__table--big">
                                <tbody>
                                <tr>
                                    <td>
                                        <div class="income__big">€<?php echo $property['estimated_cost']; ?></div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="income__table income__table--big closing_costs_table">
                                <thead>
                                <tr>
                                    <th class="income__title" width="35%" data-title="Closing Costs">Closing Costs</th>
                                    <th colspan="2" class="income__title" data-title="Down Payment">Down Payment</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td title="Closing Costs">
                                        <div class="income__big" id='closing_cost_mortage'>€<?php echo $property['closing_cost_mortage']; ?></div>
                                    </td>
                                    <td colspan="2" title="Down Payment">
                                        <div class="income__big" id='mortage_value'><?php echo "€" . $property['mortage']; ?></div>
                                        <select name="mortage_percentage" id="mortage_percentage" class="custom-select pull-right" style="width:130px;">
                                            <option value="10" selected>10%</option>
                                            <option value="20">20%</option>
                                            <option value="30" selected>30%</option>
                                            <option value="40">40%</option>
                                            <option value="50">50%</option>
                                            <option value="60">60%</option>
                                            <option value="70">70%</option>
                                            <option value="80">80%</option>
                                            <option value="90">90%</option>
                                            <option value="100">100%</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <table class="income__table income__table--big closing_costs_table">
                                <thead>
                                <tr>
                                    <th class="income__title" width="35%">Loan Program</th>
                                    <th class="income__title" width="10%"></th>
                                    <th class="income__title" width="35%">Interest Rate</th>
                                    <th class="income__title" width="5%"></th>
                                    <th class="income__title" width="35%">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>

                                    <td colspan="2" align='left'>
                                        <input class="st-field st-field--sm" list="duration_list" name="loan_duration" id="loan_duration" style="width:130px;" value='7' placeholder='select'>
                                        <datalist id="duration_list">
                                            <?php for($duration = 1;$duration <= 30;$duration++) { ?>
                                            <option value="<?php echo $duration; ?>"><?php echo $duration; ?> year</option>
                                            <?php } ?>
                                        </datalist>
                                    </td>
                                    <td colspan="2" align='left'>
                                        <input class="st-field st-field--sm" list="cost_mortage_list" name="cost_mortage" id="cost_mortage" style="width:130px;" value='5'
                                               placeholder='select'>
                                        <datalist id="cost_mortage_list">
                                            <option value="1">1%</option>
                                            <option value="2">2%</option>
                                            <option value="3">3%</option>
                                            <option value="4">4%</option>
                                            <option value="5">5%</option>
                                            <option value="6">6%</option>
                                            <option value="7">7%</option>
                                            <option value="8">8%</option>
                                            <option value="9">9%</option>
                                            <option value="10">10%</option>
                                        </datalist>
                                    </td>

                                    <td>
                                        <div class="loan_program_total_circle">
                                            <div class="income__big" id='total_price_display'></div>
                                        </div>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col">
                            <div class="income__title">Price Graph</div>
                            <canvas id="price_graph" width="800" height="450"></canvas>
                        </div>
                    </div>

                </div>
            </div>
            <?php } ?>

            <div class="infosection">
                <div class="container">
                    <div id="service_tab">
                        @include("property/servicetabs-static")
                        @include("property/servicetabs-fixed")
                    </div>
                </div>
                <div class="container infosection__inner js-tab__content active" id="target1">
                    <div class="infosection__withmap">
                        <div class="col">
                            <div class="infosection__subtitle pull-left">Route Builder</div>
                            <div class="bus_metro">
                                <select name="get_stop" id="get_stop" class="pull-right custom-select" style="width:100px">
                                    <option value="52f2ab2ebcbc57f1066b8b4f,4bf58dd8d48988d1fe931735,4bf58dd8d48988d1fd931735" data-category="Bus Stop,Bus Station, Metro Statio">All</option>
                                    <option value="4bf58dd8d48988d1fd931735" data-category="Metro Statio">Metro</option>
                                    <option value="52f2ab2ebcbc57f1066b8b4f,4bf58dd8d48988d1fe931735" data-category="Bus Stop,Bus Station">Bus</option>
                                </select>

                            </div>
                            <div class="infosection__list-item">
                                Enter an address or place a pin on a map to build
                                an optimal route to your new apartment
                            </div>

                            <div class="infosection__list1">
                                <div class="infosection__list-item infosection__list-item--full">
                                    <div class="infosection__field">
                                        <label for="start">from</label>
                                        <input type="text" id="start" value="<?php echo $property['direccion'] . ", " . $property['cops']; ?>">
                                        <div class="infosection__field-right">
                                            <button class="infosection__btn-change hidden" disabled="disabled" id="transport-change"></button>
                                        </div>
                                    </div>
                                    <div class="infosection__field">
                                        <label for="end">end</label>
                                        <input type="text" id="end" value="">
                                        <div class="infosection__field-right">
                                            <button class="infosection__btn" id="transport-button">GO</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="transport">
                                    <ol class="transport__list" id="transport-info"></ol>
                                </div>
                            </div>

                            <div class="infosection__list"></div>

                        </div>
                        <div class="col">
                            <div class="infosection__map">
                                <div id="transportation-map"></div>
                                <div class="map-loader">
                                    <div class="data-loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container infosection__inner js-tab__content" id="target2">
                    <div class="infosection__charts">

                        <div class="infosection__charts-item">
                            <div class="infosection__chart-title">Age</div>
                            <canvas class="infosection__chart js-chart" id="age_chart" width="170px" height="170px"></canvas>
                            <div class="infosection__chart-legend">
                                <div>
                                    <div class="infosection__chart-label green"><?php echo $age['below16']['value']; ?>% - Below 16 y.o.</div>
                                    <div class="infosection__chart-label violet"><?php echo $age['between16_64']['value']; ?>% - 16 - 64 y.o.</div>
                                    <div class="infosection__chart-label orange"><?php echo $age['older75']['value']; ?>% - 75+ y.o.</div>
                                </div>
                            </div>
                        </div>

                        <div class="infosection__charts-item">
                            <div class="infosection__chart-title">Nationality</div>
                            <canvas class="infosection__chart js-chart" id="nationality_chart" width="170px" height="170px"></canvas>
                            <div class="infosection__chart-legend">
                                <div>
                                    <div class="infosection__chart-label green"><?php echo $national['spanish']['value']; ?>% - White (Spanish)</div>
                                    <div class="infosection__chart-label violet"><?php echo $national['other']['value']; ?>% - Other</div>
                                </div>
                            </div>
                        </div>

                        <div class="infosection__charts-item">
                            <div class="infosection__chart-title">Sex</div>
                            <canvas class="infosection__chart js-chart" id="sex_chart" width="170px" height="170px"></canvas>
                            <div class="infosection__chart-legend">
                                <div>
                                    <div class="infosection__chart-label green"><?php echo $sex['female']['value']; ?>% - Female</div>
                                    <div class="infosection__chart-label violet"><?php echo $sex['male']['value']; ?>% - Male</div>
                                </div>
                            </div>
                        </div>

                        <div class="infosection__charts-item">
                            <div class="infosection__chart-title">Economic Index</div>
                            <canvas class="infosection__chart js-chart" id="economy_chart" width="170px" height="170px"></canvas>
                            <div class="infosection__chart-legend">
                                <div>
                                    <div class="infosection__chart-label green"><?php echo $indexes['index_5']['value']; ?>% - Index 5</div>
                                    <div class="infosection__chart-label violet"><?php echo $indexes['index_4']['value']; ?>% - Index 4</div>
                                    <div class="infosection__chart-label orange"><?php echo $indexes['index_3']['value']; ?>% - Index 3</div>
                                    <div class="infosection__chart-label syne"><?php echo $indexes['index_2']['value']; ?>% - Index 2</div>
                                    <div class="infosection__chart-label yellow"><?php echo $indexes['index_1']['value']; ?>% - Index 1</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="container infosection__inner js-tab__content" id="target3">
                    <div class="infosection__withmap">
                        <div class="col">
                            <div class="infosection__subtitle">
                                Average Pricing
                            </div>
                            <div class="infosection__checks infosection__checks--small pull-left">
                                <div class="radio">
                                    <input type="radio" id="average_price_studio" name="checks" class="filter_average_price" value="0">
                                    <label for="average_price_studio"><span></span>Studio</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="average_price_bathroom3" name="checks" class="filter_average_price" value="3">
                                    <label for="average_price_bathroom3"><span></span>3-Bedrooms</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="average_price_bathroom1" name="checks" class="filter_average_price" value="1">
                                    <label for="average_price_bathroom1"><span></span>1-Bedroom</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="average_price_bathroom4" name="checks" class="filter_average_price" value="4">
                                    <label for="average_price_bathroom4"><span></span>4-Bedrooms</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="average_price_bathroom2" name="checks" class="filter_average_price" value="2">
                                    <label for="average_price_bathroom2"><span></span>2-Bedrooms</label>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="average_price_bathroom5" name="checks" class="filter_average_price" value="5">
                                    <label for="average_price_bathroom5"><span></span>5-Bedrooms+</label>
                                </div>
                            </div>

                            <div class="infosection__chartstacked">
                                <canvas class="js-chartstacked" id="average_price_chart" width="400px" height="240px"></canvas>
                            </div>
                            <div class="infosection__desc">
                                Loquare insight: real estate has been growing over the last 2 years. Average growth in price
                                was 8,7% (regular inflation growth is 3,5%). We anticipate this apartment to grow in price
                                for the next year.
                                <br>
                                <br>
                                Our conclusion: good timing for rental
                            </div>
                        </div>
                        <div class="col">
                            <div class="infosection__map">
                                <div id="averageprice-map"></div>
                                <div class="map-loader">
                                    <div class="data-loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container infosection__inner js-tab__content" id="target4">
                    <div class="infosection__withmap">
                        <div class="col">
                            <div class="infosection__subtitle pull-left">Schools</div>

                            <div class="form-inline pull-right">
                                <div class="form-group">
                                    <select class="custom-select" id="searchrange_school" style="margin-left:10px;">
                                        <option value="150">150 Meter</option>
                                        <option value="200">200 Meter</option>
                                        <option value="300">300 Meter</option>
                                        <option value="400">400 Meter</option>
                                        <option value="500" selected>500 Meter</option>
                                        <option value="600">600 Meter</option>
                                        <option value="700">700 Meter</option>
                                        <option value="800">800 Meter</option>
                                        <option value="900">900 Meter</option>
                                        <option value="1000">1 Kilometer</option>
                                        <option value="2000">2 Kilometer</option>
                                        <option value="3000">3 Kilometer</option>
                                        <option value="4000">4 Kilometer</option>
                                        <option value="5000">5 Kilometer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="infosection__checks" style="width: 100%;">
                                <div class="radio">
                                    <input type="radio" id="c1" name="checks" value="specialtyschools,highschools,preschools,collegeuniv,highschools,education" checked class="schooltype">
                                    <label for="c1"><span></span><img src="{{ asset('frontend/images/primery.png')  }}"> Show all</label>
                                    <ul class="scholllist"></ul>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="c2" name="checks" value="highschools" class="schooltype">
                                    <label for="c2"><span></span><img src="{{ asset('frontend/images/schools.png')  }}"> High schools</label>
                                    <ul class="scholllist"></ul>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="c3" name="checks" value="preschools" class="schooltype">
                                    <label for="c3"><span></span><img src="{{ asset('frontend/images/primery.png')  }}"> Pre-schools</label>
                                    <ul class="scholllist"></ul>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="c4" name="checks" value="collegeuniv" class="schooltype">
                                    <label for="c4"><span></span><img src="{{ asset('frontend/images/univercity.png')  }}"> Universities</label>
                                    <ul class="scholllist"></ul>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="c5" name="checks" value="highschools" class="schooltype">
                                    <label for="c5"><span></span><img src="{{ asset('frontend/images/schools.png')  }}"> Middle schools</label>
                                    <ul class="scholllist"></ul>
                                </div>
                                <div class="radio">
                                    <input type="radio" id="c6" name="checks" value="education" class="schooltype">
                                    <label for="c6"><span></span><img src="{{ asset('frontend/images/primery.png')  }}"> Other</label>
                                    <ul class="scholllist"></ul>
                                </div>
                            </div>

                            <ul style="list-style:none; font-size: 15px;">
                                <li class="orange" style="display: inline;margin-right: 10px;"><i class="fa fa-circle"></i> Nearest</li>
                                <li class="purple" style="display: inline;margin-right: 10px;"><i class="fa fa-circle"></i> Faraway</li>
                            </ul>

                            <div class="school_list"></div>

                        </div>
                        <div class="col">
                            <div class="infosection__map">
                                <div id="school-map"></div>
                                <div class="map-loader">
                                    <div class="data-loader"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container infosection__inner js-tab__content" id="target5">
                    <div class="infosection__withmap">
                        <div class="col">
                            <div class="infosection__subtitle">Other Businesses</div>
                            <div class="infosection__desc">Choose the certain type of recreation to get more details</div>

                            <div class="form-inline">
                                <div class="form-group">
                                    <label for="searchrange">Range</label>
                                    <select class="custom-select" id="searchrange">
                                        <option value="150">150 Meter</option>
                                        <option value="200">200 Meter</option>
                                        <option value="300">300 Meter</option>
                                        <option value="400">400 Meter</option>
                                        <option value="500" selected>500 Meter</option>
                                        <option value="600">600 Meter</option>
                                        <option value="700">700 Meter</option>
                                        <option value="800">800 Meter</option>
                                        <option value="900">900 Meter</option>
                                        <option value="1000">1 Kilometer</option>
                                        <option value="2000">2 Kilometer</option>
                                        <option value="3000">3 Kilometer</option>
                                        <option value="4000">4 Kilometer</option>
                                        <option value="5000">5 Kilometer</option>
                                    </select>
                                </div>
                            </div>


                            <div class="infosection__btns">
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="restaurants" type="button" id="restaurants">

                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                            <path d="M8.1 13.34l2.83-2.83L3.91 3.5c-1.56 1.56-1.56 4.09 0 5.66l4.19 4.18zm6.78-1.81c1.53.71 3.68.21 5.27-1.38 1.91-1.91 2.28-4.65.81-6.12-1.46-1.46-4.2-1.1-6.12.81-1.59 1.59-2.09 3.74-1.38 5.27L3.7 19.87l1.41 1.41L12 14.41l6.88 6.88 1.41-1.41L13.41 13l1.47-1.47z"
                                                  fill="#5D6872" fill-rule="nonzero"></path>
                                        </svg>

                                    </div>

                                    Restaurants
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="Pets" type="button" id="pets">
                                    <div class="icon">
                                        <svg width="23px" height="23px" viewBox="0 0 23 23" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-370.000000, -968.000000)">
                                                    <g transform="translate(135.000000, 140.000000)">
                                                        <g transform="translate(234.000000, 827.000000)">
                                                            <rect fill="#D8D8D8" fill-rule="evenodd" opacity="0" x="0" y="0"
                                                                  width="25" height="25"></rect>
                                                            <path d="M23.7500354,8.87630208 C24.1298349,9.89315365 24.0741318,11.1895391 23.6009547,12.3445104 C22.9747438,13.8704766 21.7331031,14.8958333 20.5114677,14.8958333 C20.2102516,14.8958333 19.9201761,14.8328229 19.6493271,14.7087786 C18.3023896,14.0925703 17.8601787,12.0731823 18.6207359,10.0115677 C19.2352672,8.34724219 20.4539677,7.22916667 21.6533818,7.22916667 C21.9344729,7.22916667 22.2103531,7.2891224 22.4732359,7.40741667 C23.0460797,7.6656875 23.487512,8.17372396 23.7500354,8.87630208 Z M6.37928543,10.0115677 C7.13984272,12.0731823 6.69763178,14.0925703 5.35069428,14.7087786 C5.07978543,14.8328229 4.78970991,14.8958333 4.48855366,14.8958333 C3.26691824,14.8958333 2.02527761,13.8704766 1.39906668,12.3445104 C0.925829698,11.1895391 0.870126573,9.89315365 1.24992605,8.87630208 C1.51244949,8.17372396 1.95382189,7.6656875 2.52672553,7.40741667 C2.78960834,7.2891224 3.06554845,7.22916667 3.34663959,7.22916667 C4.54605366,7.22916667 5.76475418,8.34724219 6.37928543,10.0115677 Z M9.04680626,9.78078906 C7.39901199,9.70909375 5.92353803,7.90413281 5.68778803,5.67139583 C5.54433751,4.32170312 5.8981422,2.97590365 6.63432189,2.07141667 C7.14199897,1.44778125 7.78318386,1.08127865 8.4885172,1.0115599 C8.60465522,1.00047917 8.83291824,1 8.83291824,1 C10.4317177,1.06953906 11.6216682,2.75494792 11.8601734,5.01320052 C12.0162021,6.47855208 11.8104599,7.90305469 11.0694886,8.81293229 C10.6102672,9.37679167 10.0261031,9.7083151 9.38000678,9.77174479 C9.27003803,9.78258594 9.15791303,9.78564062 9.04680626,9.78078906 Z M12.4999807,11.5416667 C16.3333141,11.5416667 20.1666474,16.0981823 20.1666474,20.4738125 C20.1666474,21.7794219 19.5118662,22.8295755 18.8688844,23.259987 C18.0747854,23.7913229 17.5151188,24 16.3511031,24 C14.9678688,24 14.5946578,23.5161615 13.947124,23.0892839 C13.4788583,22.7807005 13.0743818,22.5139844 12.5000406,22.5139844 C11.9256995,22.5139844 11.5212229,22.7807005 11.0528974,23.0892839 C10.4053636,23.5161615 10.0320927,24 8.64891824,24 C7.48484272,24 6.92517605,23.7913229 6.13107709,23.259987 C5.48809532,22.8295755 4.83331407,21.7794219 4.83331407,20.4738125 C4.83331407,16.0981823 8.66664741,11.5416667 12.4999807,11.5416667 Z M15.6313948,9.76066406 C14.9852984,9.69723438 14.4011344,9.36571094 13.9418531,8.80185156 C13.2009417,7.89197396 12.9952594,6.46753125 13.1512281,5.00211979 C13.3896734,2.74392708 14.5796839,1.06959896 16.1784833,1.0000599 C16.1784833,1.0000599 16.4304651,1.00760677 16.5466031,1.0187474 C17.2519964,1.08846615 17.8693427,1.43682031 18.3770198,2.06045573 C19.1131396,2.96488281 19.4670641,4.31074219 19.3236136,5.6604349 C19.0879234,7.89305208 17.6123896,9.69807292 15.9645953,9.76970833 C15.8535484,9.7745599 15.7413636,9.7715651 15.6313948,9.76066406 Z"
                                                                  fill="#5D6872" fill-rule="nonzero"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Pets
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="coffee" type="button" id="coffee_tea">
                                    <div class="icon">
                                        <svg width="22px" height="25px" viewBox="0 0 22 25" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-155.000000, -1593.000000)">
                                                    <g transform="translate(153.000000, 1593.000000)">
                                                        <rect x="0" y="1" width="25" height="25"></rect>
                                                        <path d="M12.7365027,7.50820732 C12.7084157,7.52701933 12.6814473,7.54744996 12.6557342,7.56939557 C12.5333577,7.66974431 12.4109812,7.77498811 12.3032899,7.88512697 L12.2812621,7.90960227 C12.1858084,8.02218865 12.0976973,8.1396701 12.0120338,8.25959908 L11.9973486,8.28162685 C11.9581881,8.3501577 11.9214752,8.41868854 11.8847622,8.49456198 L11.865182,8.53616999 C11.8331465,8.59667346 11.8061329,8.65970516 11.7844135,8.72462981 C11.7599382,8.79805572 11.7379104,8.87148162 11.7207777,8.94490753 L11.6938548,9.05504639 C11.6869694,9.09064387 11.6820668,9.12659637 11.6791697,9.16273772 L11.6791697,9.18966055 C11.6584794,9.34234134 11.6584794,9.49711542 11.6791697,9.64979622 L11.6791697,9.69140423 C11.6864637,9.74907894 11.6970874,9.8062835 11.7109876,9.86273134 L11.7109876,9.89210171 C11.7256727,9.94349984 11.7403579,9.99489797 11.7525956,10.0267159 C11.7857887,10.1287531 11.8292511,10.2271584 11.8823147,10.3204195 L11.9092375,10.3693701 C11.9608349,10.454677 12.0180713,10.5364433 12.0805646,10.6141231 L12.1001449,10.6361509 L12.1246202,10.6630737 C12.1745037,10.7183897 12.227627,10.7706957 12.2837096,10.8197156 L12.3081849,10.8392959 C12.352749,10.8768762 12.3993206,10.9120092 12.4476941,10.9445397 C12.5250835,10.9973446 12.6069862,11.04321 12.6924472,11.0816014 L12.7218175,11.093839 C12.7675414,11.1138415 12.8150778,11.1294138 12.8637743,11.1403421 L12.8760119,11.1403421 C12.9757611,11.1609846 13.0795445,11.1368281 13.1599254,11.0742588 C13.2359251,11.0147682 13.2830799,10.925797 13.2896445,10.8295058 L13.2896445,10.7316046 C13.2896445,10.7022342 13.2896445,10.6630737 13.2896445,10.6165706 L13.3092248,10.4183207 L13.3361476,10.2665738 C13.3532631,10.1890049 13.3787016,10.11351 13.412021,10.041401 C13.4242587,10.0193733 13.4364963,9.9973455 13.4462865,9.97776526 L13.4634192,9.95573749 L13.490342,9.92147207 L13.5368451,9.874969 L13.5368451,9.874969 L13.5368451,9.874969 L13.6004809,9.82846592 L13.615166,9.82846592 L13.6983821,9.77462026 C13.7379049,9.75381491 13.7763028,9.73093952 13.813416,9.70608941 C13.866536,9.6852238 13.9173828,9.65898028 13.9651628,9.62776845 L14.0508264,9.58371291 L14.1903356,9.510287 L14.4742491,9.35364507 C14.7365058,9.19121703 14.9718454,8.98890762 15.1717952,8.75400018 C15.3829465,8.50664038 15.5441894,8.22072523 15.6466161,7.9120498 C15.7417927,7.62772388 15.7816706,7.32780897 15.7640975,7.0284914 C15.7493153,6.77625992 15.6930852,6.528186 15.5976655,6.29423235 C15.4674218,5.97200853 15.2627811,5.68517596 15.0004681,5.45717703 C14.93309,5.39841523 14.8610599,5.34521122 14.7850855,5.29808757 L14.7532676,5.27850733 C14.7143014,5.25325395 14.6733789,5.2311558 14.6308911,5.21242402 C14.5360469,5.17479658 14.4294135,5.18106913 14.339635,5.22955673 C14.2478422,5.28035975 14.1846232,5.37067254 14.1683079,5.47430974 L14.1511751,5.57465848 C14.1511751,5.59913378 14.1511751,5.63095168 14.1340424,5.66766463 L14.1120147,5.77780349 C14.0701534,5.99318561 14.0120629,6.20509336 13.93824,6.4117138 C13.9032077,6.50873214 13.8589495,6.60216621 13.8060734,6.69073224 C13.761633,6.76335079 13.7090816,6.8306822 13.6494315,6.89142971 C13.5875021,6.95392891 13.5212555,7.01199694 13.4511815,7.06520436 C13.3728605,7.12394508 13.2847495,7.18513334 13.1868483,7.24632159 L13.0375489,7.3197475 L12.9983884,7.3442228 L12.9372002,7.38338328 L12.8074811,7.47149437 L12.7365027,7.50820732 Z M12.9151724,3.19321162 C12.9992544,3.59619962 13.0058993,4.01150506 12.9347526,4.41697671 C12.8536658,4.8599843 12.6874277,5.28306028 12.4452466,5.66276957 C12.2173725,6.02599397 11.9341189,6.35136405 11.6057438,6.62709646 L11.2435093,6.90366737 L11.0648396,7.035834 L10.9449106,7.12149755 C10.8865758,7.17485151 10.822556,7.22163522 10.7540033,7.26100677 C10.7041447,7.30548764 10.6518499,7.34716006 10.5973613,7.38583081 C10.5655434,7.4176487 10.5288305,7.44946659 10.4921175,7.47883696 L10.4651947,7.50086473 C10.4413489,7.52833415 10.41602,7.55448011 10.3893213,7.57918569 C10.3893213,7.57918569 10.3746361,7.59387087 10.3672935,7.603661 L10.3036577,7.68442949 C10.2941164,7.70456799 10.283495,7.72417676 10.2718398,7.74317022 L10.2522596,7.77743564 L10.2522596,7.80191094 C10.2522596,7.83372883 10.2326793,7.86309919 10.2179942,7.89736462 C10.1812885,8.01757889 10.1582987,8.14155939 10.1494633,8.26694167 L10.1494633,8.49211445 L10.1396732,8.7882656 C10.1396732,8.85924397 10.1396732,8.92043223 10.1543584,8.96448777 L10.1714911,9.09910193 C10.1841209,9.20146887 10.1522097,9.30433332 10.0838596,9.38157823 C10.0155096,9.45882313 9.91729476,9.50301893 9.81415167,9.50294441 L9.78722884,9.50294441 C9.72356239,9.49680445 9.66052208,9.48534258 9.59876902,9.46867899 L9.56205607,9.46867899 C9.43580145,9.43518937 9.31290449,9.39012715 9.19492654,9.33406483 C9.12106386,9.29882179 9.04916433,9.25960386 8.97954389,9.21658338 L8.94527846,9.19700314 C8.86018013,9.14330364 8.77844341,9.0844532 8.70052545,9.02078097 L8.6564699,8.98651554 L8.63444213,8.9669353 C8.5351873,8.87516602 8.44198574,8.77705911 8.35542369,8.67323168 L8.31136815,8.61449096 C8.22284613,8.50081065 8.14578193,8.37865569 8.08130031,8.24980896 C8.05682501,8.20330589 8.02745465,8.1396701 7.99808429,8.07113926 L7.98095158,8.02953125 C7.95218845,7.95760485 7.92767101,7.88405252 7.90752567,7.80925353 L7.89039296,7.74806528 C7.83200166,7.5458732 7.80072611,7.33682081 7.79738681,7.12639261 L7.79738681,7.07499448 C7.7948605,7.02852572 7.7948605,6.98195402 7.79738681,6.93548526 L7.81207199,6.77884333 C7.81207199,6.67359953 7.8365473,6.56835573 7.85612754,6.4606644 C7.87171141,6.3700289 7.89463036,6.2808087 7.92465838,6.19388362 L7.94423862,6.13024783 C7.97850405,6.0225565 8.015217,5.91976024 8.05437748,5.81696397 L8.06661513,5.79004114 C8.15717375,5.60647637 8.25997002,5.42291161 8.36766134,5.24668944 L8.38724158,5.21731908 C8.51940821,5.0410969 8.66381249,4.86976979 8.81311183,4.70578527 C8.84214531,4.67210691 8.87320497,4.64022989 8.90611798,4.61033159 L8.9966766,4.52466804 C9.04807473,4.47571743 9.10436792,4.42676683 9.16066112,4.3753687 L9.25856233,4.28725761 L9.2805901,4.26767737 L9.49107769,4.09879779 C9.62079679,3.98865893 9.73583071,3.8809676 9.83862698,3.77817133 C9.93114316,3.68388076 10.0170125,3.58329097 10.0956176,3.47712512 C10.1728154,3.37058373 10.2376815,3.25563114 10.2889725,3.1344709 C10.3486751,2.99150931 10.3945712,2.8431666 10.4260342,2.69146794 C10.4890365,2.37832814 10.5283086,2.06087884 10.5435157,1.74182623 C10.5435157,1.68798057 10.5435157,1.63168737 10.5435157,1.58028924 C10.5435157,1.52889111 10.5435157,1.47504544 10.5435157,1.43588496 L10.5435157,1.29637574 C10.5459454,1.18394392 10.6004566,1.07901738 10.6910519,1.01238915 C10.7816471,0.945760907 10.8980497,0.924988869 11.0060989,0.956169045 C11.0655885,0.971814914 11.1237053,0.992278609 11.1798735,1.0173573 L11.2263766,1.03693754 C11.3375683,1.08557336 11.4447727,1.14285817 11.547003,1.20826465 C11.948876,1.46593461 12.2842969,1.81477236 12.5260151,2.22643721 C12.7083503,2.52493446 12.8398577,2.85163523 12.9151724,3.19321162 Z M23.5154256,17.0902879 C23.8166973,15.8791791 23.5435789,14.5968058 22.7749565,13.6135617 C22.006334,12.6303177 20.8278152,12.0557149 19.5797971,12.0557184 L3.25966587,12.0557184 C2.96681788,12.0552723 2.68671615,12.1754702 2.48527274,12.3880277 C2.28382934,12.6005852 2.17883276,12.8867325 2.19499025,13.1791347 C2.323258,15.1235401 2.65633437,17.0489841 3.1886875,18.923488 C3.58873565,20.3300562 4.37671646,21.5955754 5.46244303,22.5752031 L4.05756071,22.5752031 C3.40399752,22.5752031 2.87417987,23.1050207 2.87417987,23.7585839 C2.87417987,24.4121471 3.40399752,24.9419647 4.05756071,24.9419647 L17.3672298,24.9419647 C18.020793,24.9419647 18.5506106,24.4121471 18.5506106,23.7585839 C18.5506106,23.1050207 18.020793,22.5752031 17.3672298,22.5752031 L15.9598999,22.5752031 C16.7110908,21.8970381 17.3229512,21.0790074 17.7612821,20.1668334 L19.5797971,20.1668334 C21.442535,20.1668386 23.0657604,18.8979367 23.5154256,17.0902879 Z M21.1315312,16.4979856 C20.9594842,17.215642 20.3177882,17.7217115 19.5797971,17.7217507 L18.5444918,17.7217507 C18.7938499,16.6671078 18.9826186,15.5990527 19.1098713,14.5228288 L19.5797971,14.5228288 C20.0719575,14.5225249 20.5368113,14.7489886 20.8399276,15.1367289 C21.1430439,15.5244691 21.2506145,16.0302392 21.1315312,16.5077758 L21.1315312,16.4979856 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Coffee/tea
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="fitness" type="button" id="fitness">
                                    <div class="icon">
                                        <svg width="25px" height="23px" viewBox="0 0 25 23" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-356.000000, -1594.000000)">
                                                    <g transform="translate(356.000000, 1592.000000)">
                                                        <rect x="0" y="0" width="25" height="25"></rect>
                                                        <path d="M16.3460966,5.84615385 C17.4146444,5.84615385 18.2692308,4.99138304 18.2692308,3.92307692 C18.2692308,2.85477081 17.4146444,2 16.3460966,2 C15.2776633,2 14.4230769,2.85477081 14.4230769,3.92307692 C14.4230769,4.99138304 15.2776633,5.84615385 16.3460966,5.84615385 Z M19.7114818,13.5384615 C16.8028931,13.5384615 14.4230769,15.9182777 14.4230769,18.8269231 C14.4230769,21.7355684 16.8028931,24.1153846 19.7114818,24.1153846 C22.6201838,24.1153846 25,21.7355684 25,18.8269231 C25,15.9182777 22.6201838,13.5384615 19.7114818,13.5384615 Z M19.711487,22.1923077 C17.8363744,22.1923077 16.3461538,20.7020872 16.3461538,18.8269231 C16.3461538,16.951759 17.8363744,15.4615385 19.711487,15.4615385 C21.5867026,15.4615385 23.0769231,16.951759 23.0769231,18.8269231 C23.0769231,20.7020872 21.5867026,22.1923077 19.711487,22.1923077 Z M15.8171207,11.0658687 L20.1923077,11.0658687 L20.1923077,9.23435229 L16.9085319,9.23435229 L14.9766036,5.79258962 C14.6638232,5.25841643 14.0910528,4.88461538 13.4659942,4.88461538 C12.9972979,4.88461538 12.5286016,5.09821598 12.2160444,5.41878858 L8.21312475,9.37171601 C7.90056755,9.69228861 7.69230769,10.1730044 7.69230769,10.6537202 C7.69230769,11.3480366 8.20581445,11.8822671 8.72663151,12.2027824 L12.1565575,14.3931615 L12.1565575,19.3076923 L13.9422798,19.3076923 L13.9422798,12.897385 L11.7473481,11.0658687 L14.1429503,8.51702746 L15.8171207,11.0658687 Z M5.28846154,13.5384615 C2.37981619,13.5384615 0,15.9182777 0,18.8269231 C0,21.7355684 2.37981619,24.1153846 5.28846154,24.1153846 C8.19710689,24.1153846 10.5769231,21.7355684 10.5769231,18.8269231 C10.5769231,15.9182777 8.19716355,13.5384615 5.28846154,13.5384615 Z M5.28846154,22.1923077 C3.41350348,22.1923077 1.92307692,20.7020872 1.92307692,18.8269231 C1.92307692,16.951759 3.41350348,15.4615385 5.28846154,15.4615385 C7.16341959,15.4615385 8.65384615,16.951759 8.65384615,18.8269231 C8.65384615,20.7020872 7.16341959,22.1923077 5.28846154,22.1923077 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Fitness
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="grocery" type="button" id="groceries">
                                    <div class="icon">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-153.000000, -1659.000000)">
                                                    <g transform="translate(152.000000, 1659.000000)">
                                                        <rect fill="#D8D8D8" fill-rule="evenodd" opacity="0" x="0" y="0"
                                                              width="25" height="25"></rect>
                                                        <path d="M8.68858791,10.3932292 C8.15958791,10.3932292 7.73025458,9.96389583 7.73025458,9.43489583 L7.73025458,5.69739583 C7.73025458,2.9695 9.94975458,0.75 12.6771712,0.75 C15.4055462,0.75 17.6250462,2.9695 17.6245671,5.69739583 L17.6245671,9.43489583 C17.6245671,9.96389583 17.1952337,10.3932292 16.6662337,10.3932292 C16.1367546,10.3932292 15.7079004,9.96389583 15.7079004,9.43489583 L15.7079004,5.69739583 C15.7079004,4.02654167 14.3480254,2.66666667 12.6771712,2.66666667 C11.0063171,2.66666667 9.64692125,4.02654167 9.64692125,5.69739583 L9.64692125,9.43489583 C9.64692125,9.96389583 9.21758791,10.3932292 8.68858791,10.3932292 Z M23.6702129,8.23985417 C23.9351921,8.23985417 24.0981087,8.4478125 24.0353379,8.705125 L20.6830879,22.3599375 C20.4799212,23.1275625 19.6730046,23.75 18.8785462,23.75 L6.42021291,23.75 C5.62671291,23.75 4.81883791,23.1275625 4.61662958,22.3599375 L1.26342125,8.705125 C1.20065041,8.44829167 1.36404625,8.23985417 1.62854625,8.23985417 L6.77240041,8.23985417 L6.77240041,9.43489583 C6.77240041,10.4919375 7.63202541,11.3515625 8.68906708,11.3515625 C9.74610875,11.3515625 10.6057337,10.4919375 10.6057337,9.43489583 L10.6057337,8.23985417 L14.7500462,8.23985417 L14.7500462,9.43489583 C14.7500462,10.4919375 15.6096712,11.3515625 16.6667129,11.3515625 C17.7237546,11.3515625 18.5833796,10.4919375 18.5833796,9.43489583 L18.5833796,8.23985417 L23.6702129,8.23985417 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Groceries
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="Beauty" type="button" id="salon">
                                    <div class="icon">
                                        <svg width="21px" height="23px" viewBox="0 0 21 23" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-358.000000, -1661.000000)">
                                                    <g transform="translate(356.000000, 1660.000000)">
                                                        <rect x="0" y="0" width="25" height="25"></rect>
                                                        <path d="M20.0772398,5.29540402 C20.7826791,3.61830357 20.9943533,2.00968973 19.7950429,1 L12.1585591,10.8571429 L11.2590895,12.0379464 C11.2590895,12.0379464 9.79527915,14.0920826 9.28381711,15.0498683 C8.77235507,16.007654 8.26089303,17.0691964 7.83765064,17.8221897 C7.46622736,18.4829777 7.28459555,18.603625 7.07880268,18.4150558 C7.05199239,18.3847143 7.0244403,18.3550915 6.99651733,18.3259308 C6.99455689,18.3235179 6.99264944,18.3213103 6.990689,18.318846 C6.9301274,18.2432746 6.84498087,18.1647254 6.74510457,18.0895134 C6.24911408,17.6747433 5.6320004,17.4285714 4.96290865,17.4285714 C3.32657983,17.4285714 2,18.899596 2,20.7142857 C2,22.5289754 3.32657983,24 4.96290865,24 C6.30246975,24 7.43385367,23.014029 7.80040234,21.6609308 L7.8001904,21.6631384 C7.8001904,21.6631384 8.50780208,19.5334821 9.53072616,17.9933036 C10.5536502,16.453125 12.1585591,15.7857143 12.1585591,15.7857143 L13.8516346,14.1428571 C13.8516346,14.1428571 19.3717475,6.97250446 20.0772398,5.29540402 Z M4.96290865,22.3571429 C4.14598938,22.3571429 3.48367969,21.6202701 3.48367969,20.7142857 C3.48367969,19.8083013 4.14598938,19.0714286 4.96290865,19.0714286 C5.77988091,19.0714286 6.44213762,19.8083013 6.44213762,20.7142857 C6.44213762,21.6202701 5.77988091,22.3571429 4.96290865,22.3571429 Z M12.1730769,14.1428571 C11.7049034,14.1428571 11.3253205,13.7751138 11.3253205,13.3214286 C11.3253205,12.8677946 11.7049034,12.5 12.1730769,12.5 C12.6413034,12.5 13.0208333,12.8677946 13.0208333,13.3214286 C13.0208333,13.7751138 12.6413034,14.1428571 12.1730769,14.1428571 Z M9.59165865,12.9751451 C8.00047286,10.8720312 4.78291937,6.54910937 4.2555619,5.29540402 C3.5501226,3.61830357 3.3385014,2.00968973 4.53775881,1 L11.6363941,10.1628304 L11.4805659,10.3639777 L11.4784995,10.3666473 L11.4764861,10.369317 L10.5770165,11.5501205 L10.5690688,11.5605424 L10.561492,11.571221 C10.5279526,11.6182478 10.0780589,12.2505424 9.59165865,12.9751451 Z M19.369893,17.4285714 C21.0062219,17.4285714 22.3461538,18.899596 22.3461538,20.7142857 C22.3461538,22.5289754 21.0062219,24 19.369893,24 C18.0303319,24 16.898948,23.014029 16.5324523,21.6609308 L16.5326643,21.6631384 C16.5326643,21.6631384 15.8250526,19.5334821 14.8021815,17.9933036 C14.233072,17.1365022 13.4842912,16.5501562 12.931501,16.1982254 L14.4515282,14.7232991 L14.4946049,14.6814576 L14.5310584,14.6340201 C14.5664522,14.5880714 14.6190131,14.5197388 14.6864098,14.431846 C14.8293097,14.661692 14.9552015,14.8742366 15.0489846,15.0498683 C15.5604466,16.007654 16.0719087,17.0691964 16.495151,17.8221897 C16.8665743,18.4829777 17.0482061,18.603625 17.253999,18.4150558 C17.2808093,18.3847656 17.3084144,18.3550915 17.3362844,18.3259308 C17.3382448,18.3235179 17.3400993,18.3213103 17.3420597,18.318846 C17.4026213,18.2432746 17.4877678,18.1647254 17.5876441,18.0895134 C18.0836876,17.6747433 18.7008013,17.4285714 19.369893,17.4285714 Z M19.369893,22.3571429 C20.1868123,22.3571429 20.849122,21.6202701 20.849122,20.7142857 C20.849122,19.8083013 20.1868123,19.0714286 19.369893,19.0714286 C18.5528678,19.0714286 17.8906641,19.8083013 17.8906641,20.7142857 C17.8906641,21.6202701 18.5528678,22.3571429 19.369893,22.3571429 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Beauty/Spa
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="nightlife" type="button" id="nightlife">
                                    <div class="icon">
                                        <svg width="25px" height="22px" viewBox="0 0 25 22" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-153.000000, -1725.000000)">
                                                    <g transform="translate(153.000000, 1723.000000)">
                                                        <rect fill="#D8D8D8" fill-rule="evenodd" opacity="0" x="0" y="0"
                                                              width="25" height="25"></rect>
                                                        <path d="M11.3333516,13.9166484 L11.3333516,21.0625 L5.5,21.0625 L5.5,23.25 L19.5,23.25 L19.5,21.0625 L13.6666484,21.0625 L13.6666484,13.9166484 L25,2.25 L23,2.25 L2,2.25 L0,2.25 L11.3333516,13.9166484 Z M7.30770479,6.25 L5,4.25 L20,4.25 L17.6922952,6.25 L7.30770479,6.25 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Nightlife
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="banks" type="button" id="bank">
                                    <div class="icon">
                                        <svg width="25px" height="16px" viewBox="0 0 25 16" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-356.000000, -1729.000000)">
                                                    <g transform="translate(356.000000, 1725.000000)">
                                                        <rect x="0" y="0" width="25" height="25"></rect>
                                                        <path d="M0.708176644,4 C0.314595703,4 0,4.31923873 0,4.7128312 L0,14.6876559 C0,15.0788615 0.314595703,15.3958056 0.708176644,15.3958056 C1.10175758,15.3958056 1.421004,15.0788615 1.421004,14.6876559 L1.421004,5.4209771 L19.6840821,5.4209771 C20.07528,5.4209771 20.3969095,5.10639292 20.3969095,4.7128312 C20.3969095,4.31923873 20.0777399,4 19.6840821,4 L0.708176644,4 L0.708176644,4 Z M3.04698526,6.33880477 C2.65336589,6.33880477 2.33884705,6.65574505 2.33884705,7.04695451 L2.33884705,17.0264337 C2.33884705,17.4200185 2.65336589,17.7346065 3.04698526,17.7346065 C3.44060464,17.7346065 3.75981262,17.4200185 3.75981262,17.0264337 L3.75981262,7.75978187 L22.0229292,7.75978187 C22.4165101,7.75978187 22.7357565,7.4405393 22.7357565,7.04695451 C22.7357565,6.65574505 22.4165101,6.33880477 22.0229292,6.33880477 L3.04698526,6.33880477 Z M5.31130561,8.60303288 C4.9176478,8.60303288 4.59840138,8.92227161 4.59840138,9.31586024 L4.59840138,19.2906887 C4.59840138,19.6818905 4.9176478,19.9988615 5.31130561,19.9988615 L24.2872111,19.9988615 C24.6807536,19.9988615 25,19.6818905 25,19.2906887 L25,9.31586024 C25,8.92227161 24.6807536,8.60303288 24.2872111,8.60303288 L5.31130561,8.60303288 Z M8.74026425,10.0193631 L20.858214,10.0193631 C21.1396398,11.3814145 22.2169523,12.4563402 23.578996,12.7402028 L23.578996,15.8477165 C22.2145693,16.1315752 21.1396398,17.2086763 20.858214,18.5778844 L8.74026425,18.5778844 C8.4587232,17.2086763 7.38848284,16.1315752 6.02409453,15.8477165 L6.02409453,12.7402028 C7.38609983,12.458727 8.4587232,11.3838014 8.74026425,10.0193631 Z M14.8015645,10.6949233 C12.8097144,10.6949233 11.1955329,12.3115493 11.1955329,14.3009626 C11.1955329,16.2927589 12.8097144,17.906975 14.8015645,17.906975 C16.7909547,17.906975 18.4028685,16.2927589 18.4028685,14.3009626 C18.4028685,12.3115493 16.7909547,10.6949233 14.8015645,10.6949233 L14.8015645,10.6949233 Z M14.4101744,11.7757911 L15.0298722,11.7757911 L15.0298722,12.3348759 C15.4496662,12.3468025 15.7351277,12.4396668 15.9569782,12.5398531 L15.7659147,13.2573581 C15.6085207,13.1810211 15.3107983,13.0383788 14.8528376,13.0383788 C14.4401157,13.0383788 14.3030158,13.2205983 14.3030158,13.3971139 C14.3030158,13.5998696 14.5278643,13.741947 15.0718055,13.9375459 C15.8255668,14.2047082 16.1200222,14.5529774 16.1200222,15.1302384 C16.1271328,15.6955767 15.7340515,16.175707 15.0065033,16.2949766 L15.0065033,16.9472332 L14.377581,16.9472332 L14.377581,16.3462382 C13.9529825,16.3247681 13.5422977,16.2049334 13.3013446,16.0713465 L13.4924082,15.330588 C13.7571913,15.4784845 14.1279799,15.6101073 14.5406249,15.6101073 C14.9079543,15.6101073 15.1602843,15.4644555 15.1602843,15.2187634 C15.1602843,14.9778411 14.9547689,14.8217885 14.4800888,14.6643562 C13.8002392,14.4353528 13.3386272,14.1173094 13.3386272,13.5042687 C13.3386272,12.9413172 13.7327078,12.5032355 14.4101744,12.376813 L14.4101744,11.7757911 L14.4101744,11.7757911 Z"
                                                              fill="#5D6872" fill-rule="nonzero"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Banks
                                </button>
                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="amusementparks" type="button" id="park">
                                    <div class="icon">
                                        <svg width="25px" height="23px" viewBox="0 0 25 23" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-369.000000, -836.000000)">
                                                    <g transform="translate(135.000000, 140.000000)">
                                                        <g transform="translate(234.000000, 695.000000)">
                                                            <rect fill="#D8D8D8" fill-rule="evenodd" opacity="0" x="0" y="0"
                                                                  width="25" height="25"></rect>
                                                            <path d="M23.2911111,17.0436111 L13.5311111,17.0436111 L13.5311111,16.1886111 L23.2913889,16.1886111 L23.2913889,17.0436111 L23.2911111,17.0436111 Z M24.1455556,17.8086111 L25,17.8086111 L25,21.7805556 L25,22.6883333 L25,23.2502778 L0,23.2502778 L0,21.7805556 L8.10305556,21.7805556 C8.26,21.3797222 8.34611111,20.8925 8.34611111,20.4541667 C8.34611111,20.1194444 8.36805556,19.215 8.39638889,18.1788889 L5.95916667,15.4061111 C5.91416667,15.4072222 5.87027778,15.4125 5.82416667,15.4125 C3.18027778,15.4125 1.03666667,13.3958333 1.03666667,10.9083333 C1.03666667,10.4344444 1.11583333,9.97861111 1.26027778,9.54944444 C0.683888889,8.89722222 0.336388889,8.05944444 0.336388889,7.14527778 C0.336388889,5.06694444 2.12805556,3.38194444 4.3375,3.38194444 C4.67222222,3.38194444 4.99555556,3.42472222 5.30583333,3.4975 C5.65833333,2.06777778 7.01777778,1 8.645,1 C10.2288889,1 11.5580556,2.01055556 11.9522222,3.38277778 C11.9577778,3.38277778 11.9638889,3.38194444 11.9697222,3.38194444 C13.8058333,3.38194444 15.2941667,4.78222222 15.2941667,6.50888889 C15.2941667,7.23361111 15.0305556,7.89944444 14.59,8.42916667 C14.7652778,8.89638889 14.8644444,9.39694444 14.8644444,9.92083333 C14.8644444,12.4080556 12.7208333,14.4244444 10.0772222,14.4244444 C10.0461111,14.4244444 10.0158333,14.4205556 9.98444444,14.4194444 C10.0283333,16.0605556 10.1188889,19.5891667 10.1188889,20.4541667 C10.1188889,20.9425 10.1975,21.4075 10.3630556,21.7805556 L11.9108333,21.7805556 L11.9108333,17.8086111 L12.7652778,17.8086111 L12.7652778,19.4275 L24.1455556,19.4275 L24.1455556,17.8086111 Z M8.43861111,16.7161111 C8.46194444,15.9244444 8.48583333,15.1747222 8.50222222,14.6425 C8.04916667,14.9305556 7.53916667,15.1416667 6.99416667,15.2713889 L8.43861111,16.7161111 Z M14.385,20.2819444 L14.385,21.7805556 L22.4372222,21.7805556 L22.4372222,20.2819444 L14.385,20.2819444 Z M12.7661111,21.7805556 L13.5311111,21.7805556 L13.5311111,20.2819444 L12.7661111,20.2819444 L12.7661111,21.7805556 Z M24.1455556,21.7805556 L24.1455556,20.2819444 L23.2911111,20.2819444 L23.2911111,21.7805556 L24.1455556,21.7805556 Z M13.5311111,18.6625 L23.2913889,18.6625 L23.2913889,17.8086111 L13.5311111,17.8086111 L13.5311111,18.6625 Z M21.8641667,6.54833333 C23.0505556,6.54833333 24.0130556,5.58583333 24.0130556,4.39944444 C24.0130556,3.21222222 23.0508333,2.25027778 21.8641667,2.25027778 C20.6766667,2.25027778 19.7152778,3.21222222 19.7152778,4.39916667 C19.7152778,5.58583333 20.6766667,6.54833333 21.8641667,6.54833333 Z"
                                                                  fill="#5D6872" fill-rule="nonzero"></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    Parks
                                </button>

                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="pharmacy" type="button" id="pharmacy">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 488.9 488.9"
                                             xml:space="preserve" width="25px" height="23px">
                                            <g>
                                                <g>
                                                    <path d="M412.759,89.1h-133.7c-5.4,0-10.1,4.7-10.1,10.1v63c0,7.4,7.8,12.4,14.4,8.9l133.7-62.9    C426.759,103.4,423.259,89.1,412.759,89.1z"
                                                          fill="#5d6872"/>
                                                    <path d="M220.059,162.1v-63c0-5.4-4.7-10.1-10.1-10.1h-133.7c-10.9,0-14,14.8-4.3,19l133.7,63.3    C212.259,174.6,220.059,169.5,220.059,162.1z"
                                                          fill="#5d6872"/>
                                                    <path d="M270.159,187c-5.4-0.8-10.1,3.1-10.9,8.5s3.1,10.1,8.5,10.9c32.3,4.3,47,15.9,47,22.1c0,8.5-23.7,21.4-60.2,23.7v-183l0,0    c14.8-4.3,25.6-17.9,25.6-33.8c-0.4-19.4-16.3-35.4-35.7-35.4c-19.4,0-35.4,15.9-35.4,35.4c0,16.3,10.9,29.9,25.6,33.8v183    c-36.5-1.9-60.2-14.8-60.2-23.7c0-6.6,14.8-17.9,47-22.1c5.4-0.8,8.9-5.4,8.5-10.9c-0.8-5.4-5.4-8.9-10.9-8.5    c-39.2,5.4-64.1,21.4-64.1,41.6c0,16.3,15.9,28.4,37.7,35.7c-14,7.8-22.5,18.7-22.5,31.5c0,16.3,14,29.9,36.1,37.3    c-8.9,7.8-14.4,18.3-14.4,29.5c0,13.6,7.8,25.6,19.4,33.4c-12,7.8-19.4,20.2-19.4,33.4c0,14.8,8.9,28.4,23.7,36.1    c4.7,2.7,10.5,0.8,13.2-3.9c2.7-4.7,0.8-10.5-3.9-13.2c-8.5-4.7-13.6-11.7-13.6-19c0-10.5,10.1-19.8,23.7-22.9v72.7    c0,5.4,4.3,9.7,9.7,9.7s9.7-4.3,9.7-9.7v-72.7c13.6,3.1,23.7,12,23.7,22.9c0,7.4-5.1,14.4-13.6,19c-4.7,2.7-6.6,8.5-3.9,13.2    c1.9,3.1,6.4,7.2,13.2,3.9c14.8-8.2,23.7-21.4,23.7-36.1c0-13.6-7.8-25.6-19.4-33.4c12-7.8,19.4-20.2,19.4-33.4    c0-11.7-5.4-21.8-14.4-29.5c21.8-7.4,36.1-21,36.1-37.3c0-12.8-8.5-23.7-22.5-31.5c21.8-7,37.7-19,37.7-35.7    C334.259,208.4,309.759,192.4,270.159,187z M234.759,385.9c-13.6-3.1-23.7-12-23.7-22.9c0-10.5,10.1-19.8,23.7-22.9V385.9z     M234.759,319.1c-26.4-1.9-45.1-13.2-45.1-23.3c0-10.1,18.7-21,45.1-23.3L234.759,319.1L234.759,319.1z M254.259,386.3v-45.8    c13.6,3.1,23.7,12,23.7,22.9C277.959,373.9,267.859,383.2,254.259,386.3z M299.259,295.8c0,10.1-18.7,21.4-45.1,23.3v-46.6    C280.659,274.8,299.259,285.7,299.259,295.8z"
                                                          fill="#5d6872"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    PHARMACIES
                                </button>

                                <button class="infosection__iconbtn search_category" data-color="#7530B2" category="parking" type="button" id="parking">
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512"
                                             style="enable-background:new 0 0 512 512;" xml:space="preserve" width="25px" height="23px">
                                            <g>
                                                <g>
                                                    <path d="M271,75.5h-60v119h30v-30h30c24.813,0,45-19.963,45-44.5S295.813,75.5,271,75.5z M271,134.5h-30v-29h30    c8.271,0,15,6.505,15,14.5S279.271,134.5,271,134.5z"
                                                          fill="#5d6872"/>
                                                </g>
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M331,0H181c-24.813,0-45,20.187-45,45v180c0,24.813,20.187,45,45,45h60v30h-90v90h90v122h30V390h90v-90h-90v-30h60    c24.813,0,45-20.187,45-45V45C376,20.187,355.813,0,331,0z M331,330v30H181v-30H331z M346,225c0,8.271-6.729,15-15,15H181    c-8.271,0-15-6.729-15-15V45c0-8.271,6.729-15,15-15h150c8.271,0,15,6.729,15,15V225z"
                                                          fill="#5d6872"/>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    PARKINGS
                                </button>

                            </div>
                        </div>
                        <div class="col">
                            <div class="infosection__map">
                                <div class="service-view" style="background: #ccc; display: inline-block; width: 100%;height: 100%;">
                                    <div id="service-map"></div>
                                    <div class="map-loader">
                                        <div class="data-loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="infosection__inner js-tab__content" id="target6">
                    <div class="infosection__mapf">
                        <div id="satelite-map"></div>
                    </div>
                </div>
                <?php if($sold->count() > 0) {  ?>
                <div class="infosection__inner js-tab__content" id="target7">
                    <div id="sold_duration">
                        <div class="loan_program_total_circle" style="width:175px; height: 175px; margin: auto;">
                            <div class="income__big" id="total_price_display">&euro;<?php echo $sold[0]->customer_offer_price; ?></div>
                        </div>
                    </div>
                    <p style="margin: 10px 0; text-align: center;"> <?php echo Loquare::dateDifference($sold[0]->updated_at, $property['created_at']); ?> </p>
                </div>
                <?php } ?>
            </div>
            <div class="full-width-cols">
                <div class="col">
                    <div class="page__title page__title--center">Building Street View</div>
                    <div class="street-view" style="background: #ccc;">
                        <div id="street-map"></div>
                    </div>
                </div>
                <div class="col">
                    <div class="page__title page__title--center">More About the Area</div>
                    <div class="about-area" style="background: #000;">
                        <div class="about-area__title">
                            <?php
                            if (!empty($hoods_data['hood'])) {
                                echo $hoods_data['hood'] . ',<br>';
                            }
                            if (!empty($district['dist_name'])) {
                                echo $district['dist_name'], ',<br>';
                            }
                            if (!empty($property['direccion'])) {
                                echo $property['direccion'];
                            }
                            ?>

                        </div>
                        <div class="about-area__top">
                            Often desribed as: <br>
                            Old • Touristic • Mysterious • Medievil
                        </div>
                        <div class="about-area__desc">
                            <?php
                            if (!empty($hoods_data['description'])) {
                                echo $hoods_data['description'];
                            }
                            ?>
                        </div>
                        <div class="about-area__btns">
                            <?php
                            if(!empty($hoods_data['id']))
                            { ?>
                            <a href="{{ url('/neighbour/'.$hoods_data['id']) }}" class="about-area__more">learn more</a>
                            <?php }else{ ?>
                            <a href="#" class="about-area__more">learn more</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page__title page__title--center">Flats Nearby</div>
            <div class="nearby">
                <div class="nearby__slider">
                    <?php if($nearby_flats){ foreach($nearby_flats as $flat) { ?>
                    <div class="nearby__item">
                        <div class="card card--nearby" title="<?php echo $flat->direccion; ?>">
                            <a href="{{ url('/')  }}/property/detail/<?php echo $flat->id; ?>" class="card__link"></a>
                            <div class="card__top lazyload" data-sizes="auto"
                                 data-bgset="<?php if (Storage::disk('s3')->exists("Properties/" . $flat->id . "/" . optional($flat->image)->filename)) {
                                     echo Storage::disk('s3')->url("Properties/" . $flat->id . "/thumbs/" . optional($flat->image)->filename);
                                 } else {
                                     echo asset('/storage/Properties/' . $flat->id . "/thumbs/" . optional($flat->image)->filename) . ' 1x,'; ?>  <?php echo asset('/storage/Properties/' . $flat->id . "/thumbs/" . optional($flat->image)->filename) . ' 2x';
                                 }
                                 ?>"></div>
                            <div class="card__bottom">
                                <div class="card__title"><?php echo substr($flat['direccion'], 0, 30) . ".."; ?></div>
                                <div class="card__footer">
                                    <div class="card__desc">
                                        <?php echo $flat['rooms']; ?> bed,
                                        <?php echo $flat['bathrooms']; ?> baths
                                        <?php echo $flat['sizem2']; ?> m
                                    </div>
                                    <div class="card__price">€<?php echo ($flat['property_deal'] == "SALE") ? $flat['price'] : $flat['price'] . "/mo"; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } }?>
                </div>
            </div>


            <div class="page__title page__title--center">Compare Property
                <select class="custom-select" id="compare_distance" style="width: 220px">
                    <option value="">--- Select Distance ---</option>
                    <option value="500">500 Meter</option>
                    <option value="1000">1 Kilometer</option>
                    <option value="2500">2.5 Kilometer</option>
                    <option value="5000">5 Kilometer</option>
                </select>
            </div>
            <div class="nearby">
                <div class="compare__slider"></div>
            </div>

            @include('short_contactus')
        </div>
    </main>

    <script type="text/javascript" src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
    <script type="text/javascript" src="http://www.chartjs.org/samples/latest/utils.js"></script>

    <script type="text/javascript">

        var chartoptions = {
            responsive: true,
            legend: {
                display: false,
                position: 'bottom',
            },
            title: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            cutoutPercentage: 75,
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var datalabel = data.labels[tooltipItem.index];
                        var currentValue = dataset.data[tooltipItem.index];

                        return datalabel + ":" + currentValue + "%";
                    }
                }
            }
        };

                <?php
                $chartdata = [];
                $datalabel = [];

                foreach ($age as $agedata) {
                    $chartdata[] = $agedata['value'];
                    $datalabel[] = $agedata['title'];
                }
                ?>

        var ageconfig = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?php echo implode(", ", $chartdata); ?>],
                            backgroundColor: [
                                "#00AB8A",
                                "#7530B2",
                                "#EC644B"
                            ],
                            label: 'Age Data'
                        }],
                        labels: [<?php echo implode(", ", $datalabel); ?>]
                    },
                    options: chartoptions
                };

                <?php
                $chartdata = [];
                $datalabel = [];

                foreach ($national as $nationality) {
                    $chartdata[] = $nationality['value'];
                    $datalabel[] = $nationality['title'];
                }
                ?>

        var nationalityconfig = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?php echo implode(", ", $chartdata); ?>],
                            backgroundColor: [
                                "#00AB8A",
                                "#7530B2"
                            ],
                            label: 'Nationality Data'
                        }],
                        labels: [<?php echo implode(", ", $datalabel); ?>]
                    },
                    options: chartoptions
                };

                <?php
                $chartdata = [];
                $datalabel = [];

                foreach ($sex as $malefemale) {
                    $chartdata[] = $malefemale['value'];
                    $datalabel[] = $malefemale['title'];
                }
                ?>

        var sexconfig = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?php echo implode(", ", $chartdata); ?>],
                            backgroundColor: [
                                "#00AB8A",
                                "#7530B2"
                            ],
                            label: 'Sex Data'
                        }],
                        labels: [<?php echo implode(", ", $datalabel); ?>]
                    },
                    options: chartoptions
                };

                <?php
                $chartdata = [];
                $datalabel = [];

                foreach ($indexes as $economy) {
                    $chartdata[] = $economy['value'];
                    $datalabel[] = $economy['title'];
                }
                ?>

        var economyconfig = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [<?php echo implode(", ", $chartdata); ?>],
                            backgroundColor: [
                                "#00AB8A",
                                "#7530B2",
                                "#EC644B",
                                "#5D8AA9",
                                "#FFFF00",
                            ],
                            label: 'Economy Data'
                        }],
                        labels: [<?php echo implode(", ", $datalabel); ?>]
                    },
                    options: chartoptions
                };


        window.onload = function () {
            var ctx = document.getElementById("age_chart").getContext("2d");
            window.agechart = new Chart(ctx, ageconfig);

            var ctx = document.getElementById("sex_chart").getContext("2d");
            window.sexchart = new Chart(ctx, sexconfig);

            var ctx = document.getElementById("nationality_chart").getContext("2d");
            window.nationalitychatt = new Chart(ctx, nationalityconfig);

            var ctx = document.getElementById("economy_chart").getContext("2d");
            window.economychart = new Chart(ctx, economyconfig);
        };

    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            setTimeout(function () {
                $(".page_loader").fadeOut(500)
            }, 1000);
        });
    </script>

    <script type="text/javascript">
        if (document.getElementById("price_graph")) {
            <?php
                    foreach ($prices as $price) {
                        $price_created_at[] = $price->created_at;
                    }

                    function date_sorting($a, $b)
                    {
                        return strtotime($a) - strtotime($b);
                    }
                    usort($price_created_at, "date_sorting");
                    ?>
                    historical_cost_label = [
                <?php if($prices != false){
                        foreach($price_created_at as $created_at)  {
                        ?>
                        '<?php echo date('d F Y', strtotime($created_at)); ?>',
                <?php }} ?>
            ];
            historical_cost = [
                <?php if($prices != false){  $prices = array_reverse($prices);
                        foreach($prices as $price){
                        $price_values[] = $price->price;  ?>
                        '<?php echo $price->price; ?>',
                <?php }} ?>
            ];

            new Chart(document.getElementById("price_graph"), {
                type: 'line',
                data: {
                    labels: historical_cost_label,
                    datasets: [{
                        data: historical_cost,
                        label: "Price",
                        borderColor: "#00AB8A",
                        fill: false
                    }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Price Graph'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: <?php $stepvalue = (max($price_values) - min($price_values)) / 2; echo round($stepvalue); ?>
                            }
                        }]
                    }
                }
            });
        }
    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#mortage_percentage').change(function () {
                calculate_mortage();
                calculate_loan_program();
            });
        });

        function calculate_mortage() {
            var price = $("#price").val();
            var mortage_percentage = $('#mortage_percentage').val();
            var mortage = ( price * mortage_percentage) / 100;
            var closing_cost_mortage = (mortage * 1.5) / 100;
            $("#mortage_value").text("€ " + mortage);
            $("#closing_cost_mortage").text("€" + closing_cost_mortage);
        }

        calculate_loan_program();
        $(document).ready(function () {
            $('#loan_duration').change(function () {
                calculate_loan_program();
            });

            $("#loan_duration, #cost_mortage").on("input", function () {
                if ($(this).val() != "") {
                    calculate_loan_program();
                }
            });

            $("#loan_duration, #cost_mortage").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

        });

        function calculate_loan_program() {
            var price = $("#mortage_value").text().trim();
            var interest = ($("#cost_mortage").val() != undefined ) ? $("#cost_mortage").val().trim() : '';
            var loan_duration_year = ($("#loan_duration").val() != undefined ) ? $("#loan_duration").val().trim() : '';

            if (price == "" || interest == "" || loan_duration_year == "") {
                return;
            }
            price = price.slice(1);

            if (isNaN(price) || isNaN(interest) || isNaN(loan_duration_year)) {
                return true;
            }

            var loan_duration_month = loan_duration_year * 12;

            if (interest != '') {
                var power_value = 1 - (Math.pow((1 + (interest / 12) / 100), -loan_duration_month));
                var total = (price * ((interest / 12) / 100) / power_value).toFixed(2);
            }
            else {
                var total = price;
            }
            if (!isNaN(total)) {
                $("#total_price_display").text("€" + total);
            }

            $.ajax({
                url: basepath + "/log/morgatelog",
                type: "post",
                data: {
                    "price": price,
                    "interest": interest,
                    "year": loan_duration_year,
                    "total": total,
                    "percentage": $('#mortage_percentage').val() + "%",
                    "property": $("#property_id").val(),
                    "_token": $("input[name='_token']").val()
                },
                success: function (res) {
                    res = jQuery.parseJSON(res);
                }
            });

            adjust_circel();
        }

        function adjust_circel() {
            circlewidth = $(".loan_program_total_circle").width();
            $(".loan_program_total_circle").height(circlewidth + "px");
        }

    </script>
    <style type="text/css">
        .steps {
            display: none !important;
        }
    </style>
@endsection