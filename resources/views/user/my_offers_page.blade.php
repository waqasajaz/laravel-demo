<?php if($total > 0) { ?>

    <?php foreach($offers as $offer) { ?>
<div class="my-properties" id="offer-<?php echo $offer->id; ?>" style="">
    <input type="hidden" name="offer_id" id="offer_id_<?php echo $offer->id; ?>" value="<?php echo $offer->id; ?>">
    <div class="my-properties__left">
        <?php
            $image = $offer->property->image->filename;
            $image = (Storage::disk("s3")->exists("Properties/".$offer->property->id."/".$image))?(Storage::disk('s3')->url("Properties/".$offer->property->id."/thumbs/".$image)):asset('/storage/Property/'.$offer->property->id.'/thumbs/'.$image);
        ?>
         <div class="my-properties__img lazyload" data-sizes="auto" data-bgset="<?php echo $image; ?> 1x, <?php echo $image; ?> 2x" style='background-image: url({{ asset("/frontend/images/thumb-loquare.png") }});'></div>
    </div>
    <div class="my-properties__center">
        <a href="{{url('/')}}/property/detail/<?php echo $offer->property->id; ?>" class="my-properties__title my-offer__title">{{$offer->property->direccion}}</a>
        <div class="my-properties__top">
            <div class="my-properties__info">ID number: <?php    echo $offer->property->ref; ?></div>
            <div class="my-properties__info">
                <?php
                    if($offer->property->property_deal == 'SALE'){ echo "New "; }else{ echo "Used "; }
                    echo $offer->property->property_type;
                ?>
            </div>
            <div class="my-properties__info">Published: <?php echo date('d/m/Y', strtotime($offer->property->created_at));?></div>
        </div>
        <div class="my-properties__price">
            <div class="my-properties__info">Price Listed: &euro;<?php echo (trim($offer->property->property_deal) == "SALE")?$offer->property->price:$offer->property->price."/mo"; ?></div>
            <div class="my-properties__info" style="color: #FF9700;">Price Offered:
                @if($offer->step_2_completed == 0)
                    -
                @else
                    @if($offer->step_2_negotiate_flag == 0)
                        <span class="my-properties__price" style="color: #FF9700;">&euro;{{$offer->owner_offer_price}}<?php echo (trim($offer->property->property_deal) == "SALE")?'':"/mo"; ?></span>
                    @else
                        <span class="my-properties__price" style="color: #FF9700;">&euro;{{$offer->customer_offer_price}}<?php echo (trim($offer->property->property_deal) == "SALE")?'':"/mo"; ?></span>
                    @endif
                @endif
            </div>

        </div>
        <div class="my-properties__desc">
            <div><?php   echo $offer->property->rooms; ?> bed, <?php echo  $offer->property->bathrooms; ?> baths, <?php echo $offer->property->sizem2; ?> m<sup>2</sup></div>
        </div>
        <div class="my-properties__equip">
            <?php
            $features = array(
                    "furnished" => array(
                            "name" => "Furnished",
                            "icon" => asset('/frontend/assets/icons/icon-25x25-furniture.svg')
                    ),
                    "heating" => array(
                            "name" => "Heating",
                            "icon" =>asset('/frontend/assets/icons/icon-25x25-heating.svg')
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
            <?php foreach($features as $feature => $value){ if($offer->property->$feature == 1) { ?>
            <?php if($value['icon'] != "") { ?>
            <div class="my-properties__equip-item">
                <img src="<?php echo $value['icon']; ?>"/>
            </div>
            <?php } } } ?>
        </div>
    </div>
    <div class="my-properties__right">
        <div class="my-properties__actions">
            <b>
                Status :
                @if($offer->step_7_completed == 0)
                    <span class="label label-primary">In Progress</span>
                @elseif($offer->accept_status == 0)
                    <span class="label label-warning">Being Reviewed</span>
                @elseif($offer->accept_status == 1)
                    <span class="label label-success">Accepted</span>
                @elseif($offer->accept_status == 2)
                    <span class="label label-danger">Rejected</span>
                @endif
            </b>
            @if($offer->accept_status != 1)
                <a href="javascript:void(0)" data-id="<?php echo $offer->id; ?>" data-mfp-src="#delete-offer" class="my-properties__action js-popup-open delete_offer_popup">
                    <span class="icon icon--trash"></span>
                    Cancel Offer
                </a>
            @endif

            <a href="{{url('create-offer')}}/{{$offer->property->id}}" title="Edit Offer" class="my-properties__action">
                <span class="icon icon--edit"></span>
                offer
            </a>
        </div>
    </div>
</div>
    <?php } ?>

<?php  } ?>