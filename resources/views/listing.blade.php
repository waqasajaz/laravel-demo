@extends('layouts.app')
@section('title', 'Loquare | Properties')
@section('content')
<main>

    <div class="page page--left-white">
        <div class="container">
            <div class="page__left">
                <div class="filter">
                    <div class="search-field">
                        <input class="search-field__input" type="text" placeholder="City area or street address" aria-describedby="find_address" id="mapbox_search" value='<?php if($searchdata['search'] != ""){ echo $searchdata['search'];} ?>'>
                        <span class="reset-search hidden" title="Clear Search"><i class="fa fa-close"></i></span>
                        <button class="search-field__btn" type="button" id="find_address">Find</button>
                    </div>
			  
                    <div rel="searchbox-list" class="search-list hidden" id="searchresults">
                        <ul id="searchlist"></ul>
                    </div>

                    <div class="filter__map">
                        <div id="retailer_map"></div>
                        <i class="fa fa-arrows-alt full_screenmap"></i>
                    </div>

                    <div class="hidden">
                        <div class="filter__actions sale_rent_option">
                            <div class="filter-big__items">
                                <a href="#" class="filter-big__item filter_for <?php echo (strtolower($searchdata['type']) == "")?"active":""; ?>" data-type="">
                                    <span>All</span>
                                </a>
                                <a href="#" class="filter-big__item filter_for <?php echo (strtolower($searchdata['type']) == "rent")?"active":""; ?>"  data-type="RENT">
                                    <span>Rent</span>
                                </a>
                                <a href="#" class="filter-big__item filter_for <?php echo (strtolower($searchdata['type']) == "sale")?"active":""; ?>" data-type="SALE">
                                    <span>Sale</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="filter__section filter__section--row">
                        <div class="filter__label">Type:</div>
                        <div class="filter__actions slideToTop">
                            <?php foreach($property_types as $ptype){  ?>
                                <div class="checkbox">
                                    <input type="checkbox" name="property_type[]"  value="<?=$ptype['id']?>" id="property_type-<?=$ptype['id']?>" class="property_type_filter">
                                    <label for="property_type-<?=$ptype['id']?>"><span></span><?=$ptype['property_type_name']?></label>
                                    <?php $sub_options = App\properties\ProperttypesModel::where('parent', $ptype['id'])->where('status', 1)->pluck('property_type_name', 'id'); ?>

                                    <?php if(count($sub_options) > 0){ ?>
                                    <div class="sub-options">
                                        <?php foreach($sub_options as $id => $name){ ?>
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="property_type_filter" name="property_type[]" id="property_type-<?php echo $id; ?>" value="<?php echo $id; ?>">
                                            <label for="property_type-<?php echo $id; ?>">
                                                <span></span>
                                                <?php echo $name; ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="filter__section filter__section--row-center">
                        <div class="filter__label">Budget:</div>
                        <div class="filter__between slideToTop range-inputs">
                            <div class="filter__between-item">
                                from
                                <input type="text" id="minprice" name="minprice" data-group="min" class="range-input text-input" style="width:102px;" pattern="^[0-9]+$">
                                <div class="option-group option-group-min">
                                    <div class="droplist-option min"></div>
                                </div>
                            </div>
                            <div class="filter__between-item">
                                to
                                <input type="text" id="maxprice_input" name="maxprice" data-group="max" class="range-input text-input" style="width:102px;" pattern="^[0-9]+$">

                                <div class="option-group option-group-max">
                                    <div class="droplist-option max"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter__section filter__section--row-center">
                        <div class="filter__label">Size(m<sup>2</sup>):</div>
                        <div class="filter__between slideToTop">
                            <div class="filter__between-item">
                                from
                                <input type="text" name="minsize" id="minsize" data-group="minsize" class="range-input text-input" style="width: 102px" placeholder="- min size -" pattern="^[0-9]+$">
                                <div class="option-group option-group-minsize">
                                    <div class="droplist-option minsize">
                                        <div class="data-option" data-value="">All</div>
                                        <div class="data-option" data-value="50">50</div>
                                        <div class="data-option" data-value="100">100</div>
                                        <div class="data-option" data-value="150">150</div>
                                        <div class="data-option" data-value="200">200</div>
                                        <div class="data-option" data-value="250">250</div>
                                        <div class="data-option" data-value="300">300</div>
                                        <div class="data-option" data-value="350">350</div>
                                        <div class="data-option" data-value="400">400</div>
                                        <div class="data-option" data-value="450">450</div>
                                        <div class="data-option" data-value="500">500</div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter__between-item">
                                to
                                <input type="text" name="maxsize" id="maxsize" data-group="maxsize" class="range-input text-input" style="width: 102px" placeholder="- max size -" pattern="^[0-9]+$">
                                <div class="option-group option-group-maxsize">
                                    <div class="droplist-option maxsize">
                                        <div class="data-option" data-value="">All</div>
                                        <div class="data-option" data-value="50">50</div>
                                        <div class="data-option" data-value="100">100</div>
                                        <div class="data-option" data-value="150">150</div>
                                        <div class="data-option" data-value="200">200</div>
                                        <div class="data-option" data-value="250">250</div>
                                        <div class="data-option" data-value="300">300</div>
                                        <div class="data-option" data-value="350">350</div>
                                        <div class="data-option" data-value="400">400</div>
                                        <div class="data-option" data-value="450">450</div>
                                        <div class="data-option" data-value="500">500</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="filter__section filter__section--row">
                        <div class="filter__label">Beds:</div>
                        <div class="filter__actions filter__actions--cols slideToTop">
                            <div class="checkbox">
                                <input type="checkbox" name="rooms[]" id="room-1" value="1" class="room_filter"/>
                                <label for="room-1"><span></span>1-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="rooms[]" id="room-2" value="2" class="room_filter"/>
                                <label for="room-2"><span></span>2-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="rooms[]" id="room-3" value="3" class="room_filter"/>
                                <label for="room-3"><span></span>3-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="rooms[]" id="room-4" value="4" class="room_filter"/>
                                <label for="room-4"><span></span>4-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="rooms[]" id="room-5" value="5" class="room_filter"/>
                                <label for="room-5"><span></span>5-BR+</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter__section filter__section--row">
                        <div class="filter__label">Bathrooms:</div>
                        <div class="filter__actions filter__actions--cols slideToTop">
                            <div class="checkbox">
                                <input type="checkbox" name="bath[]" id="bath-1" value="1" class="bath_filter"/>
                                <label for="bath-1"><span></span>1-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="bath[]" id="bath-2" value="2" class="bath_filter"/>
                                <label for="bath-2"><span></span>2-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="bath[]" id="bath-3" value="3" class="bath_filter"/>
                                <label for="bath-3"><span></span>3-BR</label>
                            </div>
                            <div class="checkbox">
                                <input type="checkbox" name="bath[]" id="bath-4" value="4" class="bath_filter"/>
                                <label for="bath-4"><span></span>4-BR</label>
                            </div>
                        </div>
                    </div>


                    <div class="filter__section filter__section--extra">
                        <div class="filter__label">Extra Features:</div>
                        <div class="filter__actions">
                            <div class="filter__toggle-box slideToTop">
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_evevator" value="elevetor">
                                    <label for="feature_evevator">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-elevator.svg') }} "/></div>
                                        Elevator
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_outdoot" value="outdoor_space">
                                    <label for="feature_outdoot">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-outdoor.svg') }}" /></div>
                                        Outdoor
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_doorman" value="doorman">
                                    <label for="feature_doorman">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-doorman.svg') }}" /></div>
                                        Doorman
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_gym" value="gym">
                                    <label for="feature_gym">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-gym.svg') }}" /></div>
                                        Gym
                                    </label>
                                </div>
                                <div class="checkbox have-options">
                                    <input type="checkbox" class="features" id="feature_furnished" value="furnished">
                                    <label for="feature_furnished">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-furniture.svg') }}" /></div>
                                        Furnished
                                    </label>
                                    <div style="margin-left: 15px; " id="furnished_options" class="sub-options">
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="furnished_kitchen" id="furnished_kitchen" value="furnished_kitchen">
                                            <label for="furnished_kitchen">
                                                <span></span>
                                                Kitchen
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="furnished_all" id="furnished_all" value="furnished_all">
                                            <label for="furnished_all">
                                                <span></span>
                                                All
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_pool" value="pool">
                                    <label for="feature_pool">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-pool.svg') }}" /></div>
                                        Pool
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_dishwasher" value="dishwasher">
                                    <label for="feature_dishwasher">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dishwasher.svg') }}" /></div>
                                        Dishwasher
                                    </label>
                                </div>

                                <div class="checkbox have-options">
                                    <input type="checkbox" class="features" id="feature_floor" value="floor">
                                    <label for="feature_floor">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/floor.svg') }}" /></div>
                                        Floor
                                    </label>
                                    <div style="margin-left: 15px;" id="floor_options" class="sub-options">
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="floor_hardwood" id="floor_hardwood" value="floor_hardwood">
                                            <label for="floor_hardwood">
                                                <span></span>
                                                Hardwood
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="floor_ceramic" id="floor_ceramic" value="floor_ceramic">
                                            <label for="floor_ceramic">
                                                <span></span>
                                                Ceramic
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_pets" value="pets">
                                    <label for="feature_pets">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-pets.svg') }}" /></div>
                                        Pets
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_heating" value="heating">
                                    <label for="feature_heating">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-heating.svg') }}" /></div>
                                        Heating
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_dogs" value="dogs">
                                    <label for="feature_dogs">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-dogs.svg') }}" /></div>
                                        Dogs
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_laundry" value="laundry">
                                    <label for="feature_laundry">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-laundry.svg') }}" /></div>
                                        Laundry
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_cat" value="cats">
                                    <label for="feature_cat">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-cat.svg') }}" /></div>
                                        Cat
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_central_ac" value="central_ac">
                                    <label for="feature_central_ac">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/icon-25x25-ac.svg') }}" /></div>
                                        Central a/c
                                    </label>
                                </div>

                                <div class="checkbox have-options" id="cellings_opt">
                                    <input type="checkbox" class="features" name="cellings" id="feature_cellings" value="cellings" >
                                    <label for="feature_cellings">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/celling.svg') }}" /></div>
                                        Cellings
                                    </label>

                                    <div style="margin-left: 15px;" id="cellings_options" class="sub-options">
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="cellings_high" id="cellings_high" value="cellings_high">
                                            <label for="cellings_high">
                                                <span></span>
                                                High Cellings
                                            </label>
                                        </div>
                                        <div class="checkbox checkbox--small">
                                            <input type="checkbox" class="features" name="cellings_other" id="cellings_other" value="cellings_other">
                                            <label for="cellings_other">
                                                <span></span>
                                                Other
                                            </label>
                                        </div>
                                    </div>

                                </div>

                                <div class="checkbox have-options" id="floor_nature_light_opt">
                                    <input type="checkbox" class="features" name="floor_nature_light" id="feature_floor_nature_light" value="floor_nature_light" >
                                    <label for="feature_floor_nature_light">
                                        <span></span>
                                        <div class="checkbox__icon"><img src="{{ asset('frontend/assets/icons/nature_light.svg') }}" /></div>
                                        Nature Light
                                    </label>
                                </div>


                                <div class="checkbox">
                                    <input type="checkbox" class="features" id="feature_other" value="others">
                                    <label for="feature_other"><span></span>Other</label>
                                </div>
                                <button type="button" class="filter__toggle-box-btn" data-see="see more extra features" data-hide="hide more extra features"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page__right">
                <div class="sorting">
                    <div class="sorting__item">
                        <select name="filter_for" id="filter_for" class="custom-select" style="width: 100px">
                            <option class="" value="">ALL</option>
                            <option class="" value="RENT" <?php echo (strtolower($searchdata['type']) == "rent" || $type == "rent")?"selected":""; ?>>Rent</option>
                            <option class="" value="SALE" <?php echo (strtolower($searchdata['type']) == "sale" || $type == "sale")?"selected":""; ?>>Sale</option>
                        </select>
                    </div>
                </div>

                <div class="sorting">
                    <div class="sorting__label">Sort By:</div>
                    <div class="sorting__item">
                        <select name="sort_filter" id="sort_filter" class="custom-select" style="width: 200px">
                            <option value="">Sort</option>
                            <option value="price-ASC">Price - Low to High</option>
                            <option value="price-DESC">Price - High to Low</option>
                            <option value="construction-DESC">New First</option>
                            <option value="construction-ASC">Old First</option>
                        </select>
                    </div>
                    <div class="pull-left" style="margin-left: 10px;"><span id="results"> Searching ... </span> </div>
                </div>

                <div class="save_wishlist_block">
                    <button class="save_wishlist"> Save Wishlist</button>
                </div>
                <div class="grid grid--two" id="filteredResults" style="clear:both;"></div>
                <div style="text-align: center">
                    <ul class="pagination pagination-sm listing-pagination" id="pages"></ul>
                </div>
            </div>
        </div>
        <div>

            <div class="contact-us contact-us--small">
                <div class="container">
                    <form class="form" name="contact_us_form" id="contact_us_form" action="{{url('/contact_us')}}" method="post">
                        {{csrf_field()}}
                        <div class="form">
                            <div class="form__inner">
                                <div class="contact-us__title">Got questions? Contact us today</div>
                                <div class="form__row form__row--three">
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <input type="email" id="contact_email" name="contact_email" placeholder="example@gmail.com" required>
                                            <label for="contact-email">Email</label>
                                        </div>
                                    </div>
                                    <div class="form__field">
                                        <div class="field field--contact">
                                            <input type="tel" id="contact_phone" name="contact_phone" placeholder="+11-111-111-1111" required>
                                            <label for="contact-phone">Phone</label>
                                        </div>
                                    </div>
                                    <div class="form__field">
                                        <button type="button" id="request_contact" class="form__submit form__submit--large">send contact request</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form__reaction form__reaction--success">
                                <div class="form__reaction-inner">
                                    <div class="form__reaction-title">Congratulations!</div>
                                    <div class="form__reaction-img">
                                        <img src="{{ asset('frontend/assets/icons/form-success.svg') }}" alt="">
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
                                        <img src="{{ asset('frontend/assets/icons/form-fail.svg') }}" alt="">
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
    </div>
</main>
@endsection