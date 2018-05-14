@extends('layouts.app')
@section('title', 'Loquare | Edit Property')
@section('content')
    <link rel='stylesheet' href='https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v2.1.1/mapbox-gl-geocoder.css' type='text/css' />
    <style type="text/css">
        .popup_map
        {
            position: fixed;
            top:0;
            bottom:0;
            left:0;
            right:0;
            z-index: 99;
        }
        .close_map
        {
            position: absolute;
            right: 30px;
            top:30px;
            z-index: 999;
            float: right;
        }
        #property_location
        {
            position: absolute;
            height: 100%;
            width: 100%;
        }
        .add_location
        {
            margin-top: 27px;
            width: 100%;
            padding: 12px;
            border-radius: 0;
            border: 1px solid #CCC;
            background-color: #00AB8A;
            color: #FFF;
        }
        #how_to_add
        {
            display: inline-block;
            position: relative;
            float: right;
            padding: 6px;
            background-color: #00AB8A;
            color: #FFF;
        }

        .processbar {
            top: 50%;
            left: 50%;
            height: 20px;
            width: 200px;
            margin-top: 0px;
            border-radius: 20px;
            background-image: -webkit-linear-gradient(-45deg, #00ab8a 25%, rgba(255, 154, 26, 0) 25%, rgba(255, 154, 26, 0) 50%, #00ab8a 50%, #00ab8a 75%, rgba(255, 154, 26, 0) 75%);
            background-image: -moz-linear-gradient(-45deg, #00ab8a 25%, rgba(255, 154, 26, 0) 25%, rgba(255, 154, 26, 0) 50%, #00ab8a 50%, #00ab8a 75%, rgba(255, 154, 26, 0) 75%);
            background-image: -o-linear-gradient(-45deg, #00ab8a 25%, rgba(255, 154, 26, 0) 25%, rgba(255, 154, 26, 0) 50%, #00ab8a 50%, #00ab8a 75%, rgba(255, 154, 26, 0) 75%);
            background-image: linear-gradient(-45deg, #00ab8a 25%, rgba(255, 154, 26, 0) 25%, rgba(255, 154, 26, 0) 50%, #00ab8a 50%, #00ab8a 75%, rgba(255, 154, 26, 0) 75%);
            background-color: #d3d3d3;
            background-size: 50px 50px;
            border: 1px solid #00ab8a;
            border-bottom-color: #00ab8a;
            -webkit-box-shadow: inset 0 10px 0 rgba(255, 255, 255, 0.2);
            box-shadow: inset 0 10px 0 rgba(255, 255, 255, 0.2);
            -webkit-animation: move 2s linear infinite;
            -moz-animation: move 2s linear infinite;
            -ms-animation: move 2s linear infinite;
            animation: move 2s linear infinite;
            margin: 30px auto;
        }


        @-webkit-keyframes move {
            0%   { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        @-moz-keyframes move {
            0%   { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        @-ms-keyframes move {
        0%   { background-position: 0 0; }
        100% { background-position: 50px 50px; }
        }

        @-webkit-keyframes move {
            0%   { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }
        .add_property_loader
        {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999999;
            background-color: rgba(255,255,255,0.9);
            padding-top: 10%;
            text-align: center;
            display: none;
        }
    </style>

    <main>

        <div class="page">
            <div class="container">
                <div class="page__title">Edit Property</div>

                <?php if($success != "") { ?>
                <div class="alert alert-success">
                    <strong>Success!</strong> <?php echo $success; ?>
                </div>
                <?php } if($error != "") { ?>
                <div class="alert alert-danger">
                    <strong>Error!</strong> <?php echo $error; ?>
                </div>
                <?php } ?>

                <div class="page__cols2">

                    <div class="col" id="sidebar"  >
                        <div class='inside filterSlide'>
                            <div class="steps ">
                                <div class="steps__item" id='property'>Property details</div>
                                <div class="steps__item" id='about'>Tell us about the deal</div>
                                <div class="steps__item" id='document'>Documentation</div>
                                <div class="steps__item" id='contact'>Your contact info</div>
                                <div class="steps__item <?php echo ($property['status'] == 1)?"complete":""; ?>" id='publish'>Publish listing</div>
                            </div>

                            <div class="help">
                                <div class="help__title">Need help adding a property?</div>
                                <div class="help__desc">Call us now</div>
                                <a href="tel:+343404919" class="help__phone">+34 340 4919</a>
                                <div class="help__desc">Or send us a message using the form:</div>
                                <div class="help__form">
                                    <form action="#" id="addproperty_help">
                                        <div class="help__cols">
                                            <div class="col">
                                                <input type="text" class="st-field" placeholder="Your Name" name="fullname" id="fullname">
                                            </div>
                                            <div class="col">
                                                <input type="tel" class="st-field" placeholder="Your Phone" name="phon_no" id="phon_no">
                                            </div>
                                        </div>
                                        <div class="help__row">
                                            <input type="email" class="st-field" placeholder="Your Email" name="email" id="email">
                                        </div>
                                        <div class="help__row">
                                            <textarea class="st-field" name="message" id="message"  placeholder="Please describe your problem here and we will do our best to help you within 24 hours"></textarea>
                                        </div>
                                        <button type="button" id="add_property_help" class="help__submit">submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id='content' class="main_content">
                    <?php   if($property !=''){?> 
						<form action="{{ url('/property/update') }}" method="post" enctype="multipart/form-data" id="add_property_form">
                            {{csrf_field()}}
                            <input type='hidden' name='property_id' value='<?php echo $property['id']; ?>'>
                            <div class="col">
                                <div class="add-property">
                                    <div class="add-property__section" id='slideToproperty'>
                                        <div class="add-property__title">Property details</div>
                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <label for="comunidad_autonoma" class="st-label"><span class="fild-error comunidad_autonoma-error"></span>Comunidad Aut&oacute;noma</label>
                                                <input name="comunidad_autonoma" id="comunidad_autonoma" class="st-field" type="text" value="<?php echo $property['comunidad_autonoma']; ?>">
                                            </div>
                                            <div class="col">
                                                <label for="cops" class="st-label"><span class="fild-error cops-error"></span>Zipcode</label>
                                                <input name="cops" id="cops" class="st-field" type="text" value="<?php echo $property['cops']; ?>">
                                            </div>
                                        </div>
                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <label for="provincia" class="st-label"><span class="fild-error provincia-error"></span>Provincia</label>
                                                <input name="provincia" id="provincia" class="st-field" type="text" value="<?php echo $property['provincia']; ?>">
                                            </div>
                                            <div class="col">
                                                <label for="localidad" class="st-label"><span class="fild-error localidad-error"></span>City</label>
                                                <input name="localidad" id="localidad" class="st-field" type="text" value="<?php echo $property['localidad']; ?>">
                                            </div>
                                        </div>

                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <div class="st-label"><span class="fild-error dist_id-error"></span>District</div>
                                                <select class="custom-select" name="dist_id" id="dist_id" style="width:100%;">
                                                    <option value="">-- Select District --</option>
                                                    <?php if($districts != false){ foreach($districts as $district) { ?>
                                                    <option value="<?php echo $district['id']; ?>" <?php echo ($district['id'] == $property['dist_id'])?"selected='selected'":""; ?>><?php echo $district['dist_name']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <div class="st-label"><span class="fild-error hood-error"></span>Hood</div>
                                                <select class="custom-select" name="hood" id="hood" style="width:100%;">
                                                    <option value="">-- Select Hood --</option>
                                                    <?php if($hoods != false){ foreach($hoods as $hood) { ?>
                                                    <option value="<?php echo $hood['id']; ?>" <?php echo ($hood['id'] == $property['hoods'])?"selected='selected'":""; ?>><?php echo $hood['hood']; ?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="add-property__cols">
                                            <div class="col">
                                                <label for="direccion" class="st-label"><span class="fild-error direccion-error"></span>Street address</label>
                                                <input name="direccion" id="direccion" class="st-field hidden" type="text" value="<?php echo $property['direccion']; ?>">
                                                <div id='geocoder' class='geocoder'></div>
                                            </div>
                                            <div class="col"> <span class="fild-error map-error"></span>
                                                <button class="add_location hidden" type="button"> <i class="fa fa-map-marker"></i> Update Location</button>
                                                <input name="latitude" id="latitude" class="hidden" type="text" value="<?php echo $property['latitude']; ?> ">
                                                <input name="longitude" id="longitude" class="hidden" type="text" value="<?php echo $property['longitude']; ?>" >

                                                <div class="hidden popup_map" >
                                                    <button class="close_map" type="button"> close </button>
                                                    <div id="property_location"></div>
                                                    <div id='how_to_add' class='hidden'>
                                                        <p>Search Your location with search box, then on map click on your property location for exact location.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-property__cols3">

                                            <div class="col">
                                                <div class="st-label"><span class="fild-error property_type-error"></span>Property type</div>
                                                <?php if($property_types != false){ foreach($property_types as $type){ ?>
                                                <div class="radio">
                                                    <input type="radio" onclick="$('.sub_property_options').hide();$('#sub_option_{{$type['id']}}').show();" name="property_type" id="property_type_<?php echo $type['id']; ?>" value="<?php echo $type['id']; ?>" <?php echo ($property['property_type'] == $type['id'])?"checked":"" ?>>
                                                    <label for="property_type_<?php echo $type['id']; ?>"><span></span><?php echo $type['property_type_name']; ?></label>
                                                </div>
                                                <?php $sub_options = App\properties\ProperttypesModel::where('parent', $type['id'])->pluck('property_type_name', 'id'); ?>
                                                    <div class="radio sub_property_options" style="<?php echo ($property['property_type'] == $type['id'])?"":"display: none;" ?>margin-left: 15px;" id="sub_option_{{$type['id']}}">
                                                        <?php foreach($sub_options as $id => $name){ ?>
                                                            <input type="radio" name="property_sub_type" id="property_type_<?php echo $id; ?>" value="<?php echo $id; ?>" <?php echo ($property['property_sub_type'] == $id)?"checked":"" ?>>
                                                            <label for="property_type_<?php echo $id; ?>" style="padding-bottom: 7px;"><span></span><?php echo $name; ?></label><br>
                                                        <?php } ?>
                                                    </div>
                                                <?php }} ?>
                                            </div>

                                            <div class="col">
                                                <div class="st-label"><span class="fild-error rooms-error"></span>Number or bedrooms</div>
                                                <div class="add-property__innercols">
                                                    <div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_0" value="0" <?php echo ($property['rooms'] == 0 )?"checked":"" ?>>
                                                            <label for="room_0"><span></span>0 (estudio)</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_1" value="1"  <?php echo ($property['rooms'] == 1 )?"checked":"" ?>>
                                                            <label for="room_1"><span></span>1</label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_2" value="2"  <?php echo ($property['rooms'] == 2 )?"checked":"" ?>>
                                                            <label for="room_2"><span></span>2</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_3" value="3"  <?php echo ($property['rooms'] == 3 )?"checked":"" ?>>
                                                            <label for="room_3"><span></span>3</label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_4" value="4"  <?php echo ($property['rooms'] == 4 )?"checked":"" ?>>
                                                            <label for="room_4"><span></span>4</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="rooms" id="room_5" value="5"  <?php echo ($property['rooms'] == 5 )?"checked":"" ?>>
                                                            <label for="room_5"><span></span>5+</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="st-label"><span class="fild-error bathrooms-error"></span>Number or bathrooms</div>
                                                <div class="add-property__innercols">
                                                    <div>
                                                        <div class="radio">
                                                            <input type="radio" name="bathrooms" id="bathroom_1" value="1" <?php echo ($property['bathrooms'] == 1 )?"checked":"" ?>>
                                                            <label for="bathroom_1"><span></span>1</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="bathrooms" id="bathroom_2" value="2" <?php echo ($property['bathrooms'] == 2 )?"checked":"" ?>>
                                                            <label for="bathroom_2"><span></span>2</label>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="radio">
                                                            <input type="radio" name="bathrooms" id="bathroom_3" value="3" <?php echo ($property['bathrooms'] == 3 )?"checked":"" ?>>
                                                            <label for="bathroom_3"><span></span>3</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="bathrooms" id="bathroom_4" value="4" <?php echo ($property['bathrooms'] == 4 )?"checked":"" ?>>
                                                            <label for="bathroom_4"><span></span>4+</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-property__cols2 add-property__cols2--small">
                                            <div class="col">
                                                <label for="sizem2" class="st-label"><span class="fild-error sizem2-error"></span>Area</label>
                                                <div class="field-wrap-with-text">
                                                    <input name="sizem2" id="sizem2" class="st-field st-field--sm" type="text" data-mask="000000" value="<?php echo $property['sizem2']; ?>"> m<sup>2</sup>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <label for="construction" class="st-label"><span class="fild-error construction-error"></span>Year of construction</label>
                                                <input name="construction" id="construction" class="st-field st-field--sm" type="text" data-mask="0000" placeholder="<?php echo date('Y'); ?>" value="<?php echo $property['construction']; ?>">
                                            </div>
                                            <div class="col">
                                                <label for="usability" class="st-label"><span class="fild-error usability-error"></span>Property Status</label>
                                                <div class="switcher" id="property_status">
                                                    <input type="checkbox" id="usability" name="usability" <?php echo ($property['usability'] == 1)?"checked":"" ?>  onchange="favouriteSpaceShowHide();">
                                                    <label for="usability">
                                                        <span class="switcher__control"></span>
                                                        <span class="switcher__text">
                                                                <div class="switcher__text-inner">
                                                                    <div>UN USED</div>
                                                                    <div>USED</div>
                                                                </div>
                                                            </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="add-property__cols2" id="favourite_space_box" style="display: none;">
                                            <div class="col">
                                                <label for="favourite_space" class="st-label"><span class="fild-error favourite_space-error"></span>What is your favourite space?</label>
                                                <textarea name="favourite_space" id="favourite_space" class="st-field">{{$property['favourite_space']}}</textarea>
                                            </div>
                                            <div class="col">
                                                <label for="about_hood" class="st-label"><span class="fild-error about_hood-error"></span>tell us about the hood</label>
                                                <textarea name="about_hood" id="about_hood" class="st-field">{{$property['about_hood']}}</textarea>
                                            </div>
                                        </div>

                                        <div class="add-property__row">
                                            <div class="st-label"><span class="fild-error property_features-error"></span>Property features</div>
                                            <div class="add-property__features">
                                                <div class="add-property__features-col">
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs"  name="elevetor" id="elevetor" value="1" <?php echo ($property['elevetor'] == 1)?"checked":"" ?>>
                                                        <label for="elevetor">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-elevator.svg') }} ">
                                                            </div>
                                                            Elevator
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="doorman" id="doorman" value="1" <?php echo ($property['doorman'] == 1)?"checked":"" ?>>
                                                        <label for="doorman">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-doorman.svg') }}">
                                                            </div>
                                                            Doorman
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small" id="furnished_opt">
                                                        <input type="checkbox" onclick="furnishedOptionsShowHide()" class="feature_inputs" name="furnished" id="furnished" value="1" <?php echo ($property['furnished'] == 1)?"checked":"" ?>>
                                                        <label for="furnished">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-furniture.svg') }}"></div>
                                                            Furnished
                                                        </label>
                                                    </div>
                                                    <div style="margin-left: 15px;<?php echo ($property['furnished'] == 1)?"":"display: none;" ?>" id="furnished_options">
                                                        <div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="furnished_kitchen" id="furnished_kitchen" value="1" <?php echo ($property['furnished_kitchen'] == 1)?"checked":"" ?>>
                                                            <label for="furnished_kitchen">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-furniture.svg') }}"></div>-->
                                                                Kitchen
                                                            </label>
                                                        </div><div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="furnished_all" id="furnished_all" value="1">
                                                            <label for="furnished_all">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-furniture.svg') }}" <?php echo ($property['furnished_all'] == 1)?"checked":"" ?>></div>-->
                                                                All
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="dishwasher" id="dishwasher" value="1" <?php echo ($property['dishwasher'] == 1)?"checked":"" ?>>
                                                        <label for="dishwasher">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dishwasher.svg') }}">
                                                            </div>
                                                            Dishwasher
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="heating" id="heating" value="1"  <?php echo ($property['heating'] == 1)?"checked":"" ?>>
                                                        <label for="heating">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-heating.svg') }}">
                                                            </div>
                                                            Heating
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small" id="floor_opt">
                                                        <input type="checkbox" class="feature_inputs" name="floor" id="floor" value="1" onclick="floorOptionsShowHide();" <?php echo ($property['floor'] == 1)?"checked":"" ?>>
                                                        <label for="floor">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/floor.svg') }}"></div>
                                                            Floor
                                                        </label>
                                                    </div>
                                                    <div style="margin-left: 15px;<?php echo ($property['floor'] == 1)?"":"display: none;" ?>" id="floor_options">
                                                        <div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="floor_hardwood" id="floor_hardwood" value="1" <?php echo ($property['floor_hardwood'] == 1)?"checked":"" ?>>
                                                            <label for="floor_hardwood">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}"></div>-->
                                                                Hardwood
                                                            </label>
                                                        </div>
                                                        <div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="floor_ceramic" id="floor_ceramic" value="1" <?php echo ($property['floor_ceramic'] == 1)?"checked":"" ?>>
                                                            <label for="floor_ceramic">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}"></div>-->
                                                                Ceramic
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="add-property__features-col">
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="outdoor_space" id="outdoor_space" value="1" <?php echo ($property['outdoor_space'] == 1)?"checked":"" ?>>
                                                        <label for="outdoor_space">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-outdoor.svg') }}">
                                                            </div>
                                                            Outdoor space
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="gym" id="gym" value="1"  <?php echo ($property['gym'] == 1)?"checked":"" ?>>
                                                        <label for="gym">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-gym.svg') }}">
                                                            </div>
                                                            Gym
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="pool" id="pool" value="1"  <?php echo ($property['pool'] == 1)?"checked":"" ?>>
                                                        <label for="pool">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-pool.svg') }}">
                                                            </div>
                                                            Pool
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="pets" id="pets" value="1"  <?php echo ($property['pets'] == 1)?"checked":"" ?>>
                                                        <label for="pets">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-pets.svg')}}">
                                                            </div>
                                                            Pets
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="dogs" id="dogs" value="1" <?php echo ($property['dogs'] == 1)?"checked":"" ?>>
                                                        <label for="dogs">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}"></div>
                                                            Dogs
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="floor_natural_light" id="floor_natural_light" value="1" <?php echo ($property['floor_natural_light'] == 1)?"checked":"" ?>>
                                                        <label for="floor_natural_light">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/nature_light.svg') }}"></div>
                                                            Natural Light
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="add-property__features-col">
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="laundry" id="laundry" value="1"  <?php echo ($property['laundry'] == 1)?"checked":"" ?>>
                                                        <label for="laundry">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-laundry.svg') }}" ></div>
                                                            Laundry
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="central_ac" id="central_ac" value="1"  <?php echo ($property['central_ac'] == 1)?"checked":"" ?>>
                                                        <label for="central_ac">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-ac.svg') }}" ></div>
                                                            Central a/c
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="cats" id="cats" value="1" <?php echo ($property['cats'] == 1)?"checked":"" ?>>
                                                        <label for="cats">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-cat.svg') }}"></div>
                                                            Cats
                                                        </label>
                                                    </div>
                                                    <div class="checkbox checkbox--small" id="cellings_opt">
                                                        <input type="checkbox" class="feature_inputs" name="cellings" id="cellings" value="1" onclick="cellingsOptionsShowHide();" <?php echo ($property['cellings'] == 1)?"checked":"" ?>>
                                                        <label for="cellings">
                                                            <span></span>
                                                            <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/celling.svg') }}"></div>
                                                            Cellings
                                                        </label>
                                                    </div>
                                                    <div style="margin-left: 15px;<?php echo ($property['cellings'] == 1)?"":"display: none;" ?>" id="cellings_options">
                                                        <div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="cellings_high" id="cellings_high" value="1" <?php echo ($property['cellings_high'] == 1)?"checked":"" ?>>
                                                            <label for="cellings_high">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}"></div>-->
                                                                High Cellings
                                                            </label>
                                                        </div>
                                                        <div class="checkbox checkbox--small">
                                                            <input type="checkbox" class="feature_inputs" name="cellings_other" id="cellings_other" value="1" <?php echo ($property['cellings_other'] == 1)?"checked":"" ?>>
                                                            <label for="cellings_other">
                                                                <span></span>
                                                                <!--<div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}"></div>-->
                                                                Other
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="checkbox checkbox--small">
                                                        <input type="checkbox" class="feature_inputs" name="others" id="others" value="1" <?php echo ($property['others'] == 1)?"checked":"" ?>>
                                                        <label for="others"><span></span>Other</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-property__row ">
                                            <label for="discription" class="st-label"><span class="fild-error discription-error"></span>
                                                Description
                                            </label>
                                            <textarea name="discription" id="discription" class="st-field"><?php echo $property['discription']; ?></textarea>
                                        </div>
                                        <div class="add-property__row">
                                            <div class="st-label"><span class="fild-error property_image-error"></span>Images
                                                <div class="checkbox checkbox--small checkbox-custom-class">
                                                    <input type="checkbox" class="feature_inputs" name="images_help_needed" id="images_help_needed" value="1" <?php echo ($property['images_help_needed'] == 1)?"checked":"" ?>>
                                                    <label for="images_help_needed"><span></span>Help me to improve photos.</label>
                                                </div>
                                            </div>
                                            <div class="add-property__files">
                                                <div class="file-input" id="file-list">
                                                     <?php if(!empty($images)) { ?>
                                                     <?php foreach($images as $image){ ?>
                                                        <button data-name="propertyImages" type="button" class="file-input__item propertyimages delete_image_popup" data-mfp-src="#delete-image-file"  data-image-id='<?php echo $image['id'];?>' data-id="<?php echo $image['id']; ?>" style="background-image: url('<?php echo  $image['filename']; ?>')"></button>
                                                     <?php } } ?>
                                                    <label for="property_image">add Picture</label>
                                                   		<input  name="property_image[]" id="property_image" multiple data-name="propertyImages"  accept="image/*" type="file" class="js-file-multiple" data-filelist="#file-list">
												</div>
											 </div>
                                        </div>
                                    </div>


                                    <div class="add-property__section" id='slideToabout'>
                                        <div class="add-property__title">Tell us about the deal</div>
                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <div class="add-property__cols3bad">
                                                    <div class="col">
                                                        <div class="st-label"> <span class="fild-error property_for-error"></span> Property type</div>
                                                        <div class="radio">
                                                            <input type="radio" name="property_for" id="residential" value="REALESTATE" <?php echo ($property['property_for'] == 'REALESTATE')?"checked":"" ?>  >
                                                            <label for="residential"><span></span>Residential</label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="st-label"><span class="fild-error property_deal-error"></span> Type of deal:</div>
                                                        <div class="radio">
                                                            <input class="js-toggle-add-property-fields" type="radio" id="rent" name="property_deal" data-type="rent" value="RENT" <?php echo ($property['property_deal'] == 'RENT')?"checked":"" ?>>
                                                            <label for="rent"><span></span>I’m renting</label>
                                                        </div>
                                                        <div class="radio">
                                                            <input class="js-toggle-add-property-fields" type="radio" id="sale" name="property_deal" data-type="sale" value="SALE" <?php echo ($property['property_deal'] == 'SALE')?"checked":"" ?>>
                                                            <label for="sale"><span></span>I’m selling</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="add-property__only-sale">
                                                    <div class="st-label"> Commission:</div>
                                                    <ul class="chart_tooltip">
                                                        <li class="green">LOQUARE</li>
                                                        <li class="orange">REAL ESTATE AGENT</li>
                                                    </ul>
                                                    <canvas id="commission_graph" height="100%" width="100%"></canvas>
                                                    <input type="hidden" id="loquare_commission" name="loquare_commission" value="<?php echo ($commission != false)?$commission->loquare_commission:0; ?>"/>
                                                    <input type="hidden" id="realestate_commission" name="realestate_commission"  value="<?php echo ($commission != false)?$commission->realestate_commission:0; ?>"/>
                                                </div>
                                                <div class="add-property__only-rent sell-guarantee">
                                                    <div class="st-label"><span class="fild-error price_sale-error"></span> Guarantee</div>
                                                    <div class="amount-field amount-field--full">
                                                        <input class="amount-field__input js-format" id="guarantee" name="guarantee" type="text" placeholder="1,150"  value="<?php echo $property['guarantee']; ?>" >
                                                        <div class="amount-field__currency">€</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="add-property__cols">
                                            <div class="add-property__only-sale">
                                                <div class="add-property__row">
                                                    <div class="st-label"><span class="fild-error price_sale-error"></span> Price
                                                        <div class="checkbox checkbox--small checkbox-custom-class">
                                                            <input type="checkbox" class="feature_inputs" name="price_help_needed" id="price_help_needed" value="1" <?php echo ($property['price_help_needed'] == 1)?"checked":"" ?>>
                                                            <label for="price_help_needed"><span></span>Help me to decide price.</label>
                                                        </div>
                                                    </div>
                                                    <div class="add-property__amount">
                                                        <div class="amount-field amount-field--full">
                                                            <input class="amount-field__input js-format" id="price_sale" name="price_sale" type="text" placeholder="1,150"  value="<?php echo ($property['property_deal'] == 'SALE')?$property['price']:""?>">
                                                            <div class="amount-field__currency">€</div>
                                                        </div>
                                                        <span class="add-property__amount-text" >
                                                        Your refund will be €<span id='total_refund'>23,500</span>
                                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="add-property__only-rent">
                                                <div class="add-property__cols2">
                                                    <div class="col">
                                                        <div class="st-label"><span class="fild-error price_rent-error"></span>Rent</div>
                                                        <div class="add-property__amount">
                                                            <div class="amount-field amount-field--full">
                                                                <input class="amount-field__input js-format" id="price_rent" name="price_rent" type="text" placeholder="1,150"  value="<?php echo ($property['property_deal'] == 'RENT')?$property['price']:""?>">
                                                                <div class="amount-field__currency">€</div>
                                                            </div>
                                                            <span class="add-property__amount-textsm">
                                                            /month
                                                        </span>
                                                        </div>
                                                    </div>
                                                    <div class="col lease_duration_div">
                                                        <div class="st-label"><span class="fild-error lease_duration-error"></span>Lease duration</div>
                                                        <div class="select-wrapper">
                                                            <select class="custom-select" style="width: 160px;" name="lease_duration" id="lease_duration">
                                                                <option  value="6_month">6 month</option>
                                                                <option  value="1_year">1 year</option>
                                                                <option  value="3_year">3 years</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="add-property__section" id='slideTodocument'>
                                        <div class="add-property__title">Documentation
                                            <div class="checkbox checkbox--small checkbox-custom-class">
                                                <input type="checkbox" class="feature_inputs" name="documentation_help_needed" id="documentation_help_needed" value="1" <?php echo ($property['documentation_help_needed'] == 1)?"checked":"" ?>>
                                                <label for="documentation_help_needed"><span></span>Help me to get documents.</label>
                                            </div>
                                        </div>
                                        <div class="add-property__files">
                                             <div class="file-input"  id='energy-certificate'>
                                               
                                                 <?php  if($energy_certificate != false) { ?>
                                                <?php if(strpos($energy_certificate['filename'], ".doc") !== false ||    strpos($energy_certificate['filename'], '.odt') !== false ||  strpos($energy_certificate['filename'], '.docx') !== false) { ?>
                                                <button  type="button" class="file-input__item energy_certificate_preview delete_energy_popup energyCertificate"  data-name="energyCertificate"  data-image-id='<?php if(!empty($energy_certificate['id'])){ echo $energy_certificate['id']; }?>'  data-id="1"   data-mfp-src="#delete-energy-certificate"   style="background-image: url('{{ asset('/frontend/images') }}/docIcon.png')"></button>
                                                <?php } else if(strpos($energy_certificate['filename'], '.pdf' ) !==false){  ?>
                                                <button  type="button" class="file-input__item energy_certificate_preview delete_energy_popup energyCertificate"  data-name="energyCertificate"  data-image-id='<?php if(!empty($energy_certificate['id'])){ echo $energy_certificate['id']; }?>'  data-id="1"   data-mfp-src="#delete-energy-certificate"   style="background-image: url('{{ asset('/frontend/images') }}/pdfIcon.svg')"></button>
                                                <?php } else if(!empty($energy_certificate['filename'])) {?>
                                                <button  type="button" class="file-input__item energy_certificate_preview delete_energy_popup energyCertificate"  data-name="energyCertificate"  data-image-id='<?php if(!empty($energy_certificate['id'])){ echo $energy_certificate['id']; }?>'  data-id="1"   data-mfp-src="#delete-energy-certificate"   style="background-image: url(<?php if(!empty($energy_certificate['filename'])){ echo $energy_certificate['filename']; } ?>)"></button>
                                                <?php }else{} ?>
                                                <?php }  ?>

                                                <label for="energy_certificate"><span class="fild-error energy_certificate-error"></span>add Energy Certificate</label>
                                                <input name="energy_certificate" data-name="energyCertificate" data-filelist="#energy-certificate" id="energy_certificate" type="file" class="js-file-one">
                                                <div class="file-input__preview"></div>
                                            </div>

                                            <div class="file-input" id='owner-certificate'>
                                                <?php if($owner_certificate != false) { ?>
                                                 <?php if(strpos($owner_certificate['filename'], ".doc") !== false ||    strpos($owner_certificate['filename'], '.odt') !== false ||  strpos($owner_certificate['filename'], '.docx') !== false) {?>
                                                <button  type="button" class="file-input__item owner_certificate_preview delete_owner_popup ownerCertificate" data-name="ownerCertificate"  data-mfp-src="#delete-owner-certificate"    data-image-id='<?php if(!empty($owner_certificate['id'])){ echo $owner_certificate['id']; }?>'  data-id="1"  style="background-image: url('{{ asset('/frontend/images') }}/docIcon.png')"></button>
                                                <?php }else if(strpos($owner_certificate['filename'], ".pdf") !== false ){  ?>
                                                <button  type="button" class="file-input__item owner_certificate_preview delete_owner_popup ownerCertificate" data-name="ownerCertificate"  data-mfp-src="#delete-owner-certificate"    data-image-id='<?php if(!empty($owner_certificate['id'])){ echo $owner_certificate['id']; }?>'  data-id="1" style="background-image: url('{{ asset('/frontend/images') }}/pdfIcon.svg')"></button>
                                                <?php }else if(!empty($owner_certificate['filename'])){ ?>
                                                <button  type="button" class="file-input__item owner_certificate_preview delete_owner_popup ownerCertificate" data-name="ownerCertificate"  data-mfp-src="#delete-owner-certificate"    data-image-id='<?php if(!empty($owner_certificate['id'])){ echo $owner_certificate['id']; }?>'  data-id="1" style="background-image: url(<?php if(!empty($owner_certificate['filename'])){ echo $owner_certificate['filename']; }?>)"></button>
                                                <?php }else{} ?>
                                                <?php } ?>

                                                <label for="owner_certificate"><span class="fild-error owner_certificate-error"></span>add Certificate of Ownership</label>
                                                <input name="owner_certificate" data-name="ownerCertificate" data-filelist="#owner-certificate" id="owner_certificate" type="file" class="js-file-one">
                                                <div class="file-input__preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-property__section" id='slideTocontact'>
                                        <div class="add-property__title">Your contact info</div>
                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <label for="contact_name" class="st-label"><span class="fild-error contact_name-error"></span>Name</label>
                                                <input name="contact_name" id="contact_name" class="st-field" type="text" placeholder="Your Name" value="<?php echo $contact['contact_name']; ?>">
                                            </div>
                                            <div class="col">
                                                <label for="contact_phone" class="st-label"><span class="fild-error contact_phone-error"></span>Phone number</label>
                                                <input name="contact_phone" id="contact_phone" data-mask="00 000-000-0000" class="st-field" type="tel"
                                                       placeholder="Your Phone" value="<?php echo $contact['contact_phone']; ?>">
                                            </div>
                                        </div>
                                        <div class="add-property__cols2">
                                            <div class="col">
                                                <label for="contact_email" class="st-label"><span class="fild-error contact_email-error"></span>Email</label>
                                                <input name="contact_email" id="contact_email" class="st-field" type="email" placeholder="Your Email" value="<?php echo $contact['contact_email']; ?>">
                                            </div>
                                            <div class="col">
                                                <label for="property_soon" class="st-label"><span class="fild-error duration-error"></span>How soon you want to sell the apartment</label>
                                                <select class="custom-select" name="duration" id="duration" style="width: 100%;">
                                                    <option value="1" <?php echo ($contact['duration'] == 1)?"selected":"" ?>>Withing 1 month</option>
                                                    <option value="2" <?php echo ($contact['duration'] == 2)?"selected":"" ?>>Withing 3 month</option>
                                                    <option value="3" <?php echo ($contact['duration'] == 3)?"selected":"" ?>>Withing 6 month</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-property__section" id='slideTopublish'>
                                        <div class="add-property__title">Publish Listing</div>
                                        <div class="add-property__actions">
                                            <div>
                                                <button class="add-property__submit" type="button">publish listing </button>
                                                <button class="add-property__save" type="button">save for later</button>
                                            </div>
                                            <div>
                                                <button class="add-property__delete" type="button">delete listing</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
					</div>
                </div>
            </div>

            <div class="contact-us">
                <div class="container">
                    <form action="#" id="contact-us-form">
                        <div class="form">
                            <div class="form__inner">
                                <div class="contact-us__title">Got questions? Contact us today</div>
                                <div class="form__row form__row--three">
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <input type="text" id="contact-name" name="contact-name"
                                                   placeholder="John Smith" required>
                                            <label for="contact-name">Name</label>
                                        </div>
                                    </div>
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <input type="email" id="contact-email" name="contact-email"
                                                   placeholder="example@gmail.com" required>
                                            <label for="contact-email">Email</label>
                                        </div>
                                    </div>
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <input type="tel" id="contact-phone" name="contact-phone"
                                                   placeholder="+11-111-111-1111" required>
                                            <label for="contact-phone">Phone</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__row">
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <textarea name="contact-message" id="contact-message" required></textarea>
                                            <label for="contact-message">Message</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__btns">
                                    <button type="submit" class="form__submit">send message</button>
                                </div>
                            </div>

                            <div class="form__reaction form__reaction--success">
                                <div class="form__reaction-inner">
                                    <div class="form__reaction-title">Congratulations!</div>
                                    <div class="form__reaction-img">
                                        <img src="{{ asset('frontend/assets/icons/form-success.svg ') }}" alt="">
                                    </div>
                                    <div class="form__reaction__desc">
                                        Your message was <br> successfully sent
                                    </div>
                                </div>
                            </div>

                            <div class="form__reaction form__reaction--fail">
                                <div class="form__reaction-inner">
                                    <div class="form__reaction-title">Failure!</div>
                                    <div class="form__reaction-img">
                                        <img src="{{ asset('frontend/assets/icons/form-fail.svg ') }}" alt="">
                                    </div>
                                    <div class="form__reaction__desc">
                                        Sorry, something went wrong. <br>
                                        Please try again.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>



    <script>
        function favouriteSpaceShowHide()
        {
            if ($("#property_status input:checkbox:checked").length > 0)
            {
                $("#favourite_space_box").show();
            }
            else
            {
                $("#favourite_space_box").hide();
            }
        }

        function furnishedOptionsShowHide()
        {
            if ($("#furnished_opt input:checkbox:checked").length > 0)
            {
                $("#furnished_options").show();
            }
            else
            {
                $("#furnished_options").hide();
                $('#furnished_options input:checkbox').removeAttr('checked');
            }
        }

        function floorOptionsShowHide()
        {
            if ($("#floor_opt input:checkbox:checked").length > 0)
            {
                $("#floor_options").show();
            }
            else
            {
                $("#floor_options").hide();
                $('#floor_options input:checkbox').removeAttr('checked');
            }
        }

        function cellingsOptionsShowHide()
        {
            if ($("#cellings_opt input:checkbox:checked").length > 0)
            {
                $("#cellings_options").show();
            }
            else
            {
                $("#cellings_options").hide();
                $('#cellings_options input:checkbox').removeAttr('checked');
            }
        }
    </script>
@endsection