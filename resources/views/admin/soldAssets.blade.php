@extends('admin.master')
@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-building"></i> Sold assets
        </h1>
    </section>
    <section class="content">

        <div class="row">
            <div ckass="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Assets ID</th>
                                    <th>Image</th>
                                    <th>Direccion</th>
                                    <th>Hood</th>
                                    <th>Cops</th>
                                    <th>District</th>
                                    <th>Baths</th>
                                    <th>Rooms</th>
                                    <th>Features</th>
                                    <th>Price</th>
                                    <th>Sold price</th>
                                    <th>Asset created</th>
                                    <th>Asset sold</th>
                                    <th>Sold duration</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($offers->count() > 0) { foreach ($offers as $offer) { ?>
                                <tr>
                                    <td><?php echo $offer->id; ?></td>
                                    <td><?php echo $offer->asset_id ?></td>
                                    <td><img src="<?php echo Storage::disk('s3')->url("Properties/".$offer->asset_id."/thumbs/".$offer->property->image->filename); ?>" class="img-thumbnail" width="200px"></td>
                                    <td><?php echo $offer->property->direccion; ?></td>
                                    <td><?php echo $offer->property->hood->hood; ?></td>
                                    <td><?php echo $offer->property->cops; ?></td>
                                    <td><?php echo $offer->property->district->dist_name; ?></td>
                                    <td><?php echo $offer->property->bathrooms; ?></td>
                                    <td><?php echo $offer->property->rooms; ?></td>
                                    <td>
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
                                        <?php foreach($features as $feature => $value){ if($offer->property->$feature == 1) { ?>
                                        <?php if($value['icon'] != "") { ?>
                                        <div class="my-properties__equip-item">
                                            <img src="<?php echo $value['icon']; ?>" title="{{$value['name']}}"/>
                                        </div>
                                        <?php } } } ?>
                                    </td>
                                    <td>&euro;<?php echo $offer->property->price; ?></td>
                                    <td>&euro;<?php echo $offer->customer_offer_price; ?></td>
                                    <td><?php echo $offer->property->created_at; ?></td>
                                    <td><?php echo $offer->updated_at; ?></td>
                                    <td><?php echo Loquare::dateDifference($offer->updated_at, $offer->property->created_at); ?></td>
                                </tr>
                                <?php } } ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <?php echo $offers->links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')

@stop