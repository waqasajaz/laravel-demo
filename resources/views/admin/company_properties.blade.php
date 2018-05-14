@extends('admin.master')

@section('content')
    <!-- content   -->
    <!-- Content Header (Page header) -->

    <style type="text/css">
        .list_features
        {
            display: block;
            text-align: center;
            margin: 0 7px 10px;
        }
    </style>

    <section class="content-header">
        <h1>
            <i class="fa fa-industry"></i> Assets of <?php echo $company->company_name; ?>
        </h1>
    </section>
    <section  class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-aqua-active">
                        <h3 class="widget-user-username"><?php echo $company->company_name; ?></h3>
                        <h5 class="widget-user-desc"><a href="<?php echo $company->company_website; ?>" target="_blank" style="color: #ffffff;"><?php echo $company->company_website; ?></a></h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" style="height: 90px; width: 90px;" src="{{ Storage::disk("s3")->url("company/".$company->id."/".$company->logo[0]['filename']) }}" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">CONTACT</h5>
                                    <span class="description-text"><?php echo $company->company_phone; ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">ADDRESS</h5>
                                    <span class="description-text"><?php echo $company->company_address; ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="description-block">
                                    <h5 class="description-header">EMAIL</h5>
                                    <span class="description-text"><?php echo $company->company_email; ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="description-block">
                                    <a class="btn btn-app btn-sm">
                                        <span class="badge bg-green"><?php echo $company->total; ?></span>
                                        <i class="fa fa-building-o"></i> Total Property
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <div class="form-inline">
                            <div class="form-group">
                                <label class="">Type : </label>
                                <select class="form-control" id="publish_status">
                                    <option value="">--- ALL ---</option>
                                    <option value="published" <?php echo $publish_status == "published"?"selected":""; ?>>Published</option>
                                    <option value="unpublished" <?php echo $publish_status == "unpublished"?"selected":""; ?> >Unpublished</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="company_list" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>&nbsp;</th>
                                    <th>Property</th>
                                    <th>Hood</th>
                                    <th>District</th>
                                    <th>Address</th>
                                    <th>Features</th>
                                    <th>Bedrooms - Rooms</th>
                                    <th>Size (m<sup>2</sup>)</th>
                                    <th>Price</th>
                                    <th>Agent</th>
                                    <th>Contact name</th>
                                    <th>Contact Email</th>
                                    <th>Contact phone</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(!$properties->isEmpty()) { foreach($properties as $property) { ?>
                                <tr>
                                    <td><?php echo $property->id; ?></td>
                                    <td>
                                        <a class="property_images" href="{{ url('/property/'.$property->id.'/images')  }}">
                                        <img src="<?php
                                        if(Storage::disk('s3')->exists("Properties/".$property->id."/thumbs/".$property->filename))
                                        { echo Storage::disk('s3')->url("Properties/".$property->id."/thumbs/".$property->filename);}
                                        elseif(Storage::disk('s3')->exists("Properties/".$property->id."/".$property->filename))
                                        { echo Storage::disk('s3')->url("Properties/".$property->id."/".$property->filename);}
                                        else{ echo asset('/storage/Property/'.$property->id.'/'.$property->filename);} ?>" style="width: 200px;"/>
                                        </a>
                                    </td>
                                    <td><?php echo $property->direccion; ?></td>
                                    <td><?php echo $property->hood ?></td>
                                    <td><?php echo $property->dist_name ?></td>
                                    <td><?php echo $property->localidad.", ".$property->cops; ?></td>
                                    <td>
                                        <?php                             $features = array(
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
                                        ) ?>
                                        <?php foreach($features as $feature => $value){ if($property->$feature == 1) { ?>
                                        <div class="list_features">
                                            <?php if($value['icon'] != "") { ?>
                                            <div class="residental__feature-icon">
                                                <img src="<?php echo $value['icon']; ?>"/>
                                            </div>
                                            <?php } ?>
                                            <?php echo $value['name']; ?>
                                        </div>
                                        <?php }} ?>
                                    </td>
                                    <td>
                                        <?php echo $property->bathrooms; ?> bathrooms<br/>
                                        <?php echo $property->rooms; ?> rooms
                                    </td>
                                    <td><?php echo $property->sizem2; ?></td>
                                    <td>&euro;<?php echo $property->price; ?></td>
                                    <td>
                                        <span class="label label-success"><?php echo $property->agent; ?></span>
                                    </td>
                                    <td><?php echo $property->company_name ?></td>
                                    <td><?php echo $property->company_email ?></td>
                                    <td><?php echo $property->company_phone ?></td>
                                    <td>
                                        <?php if($property->verified) { ?>
                                        <a href="{{url("admin/property/verify/".$property->id)}}">
                                            <button type="button" class="btn btn-block btn-success btn-xs"><i class="fa fa-check"></i> Verifyed</button>
                                        </a>
                                        <?php } else { ?>
                                        <a href="{{url("admin/property/unverify/".$property->id)}}">
                                            <button type="button" class="btn btn-block btn-danger btn-xs"><i class="fa fa-times"></i> Unverified</button>
                                        </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } } ?>
                                </tbody>
                            </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    {{ $properties->links() }}
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')

    <script type="text/javascript">
        $(document).ready(function(){
            $("#company_list").dataTable({
                "paging":   false,
                "ordering": false,
                "info":     false,
                "searching" : false
            });

            $("#publish_status").change(function(){
                published_status = $(this).val();
                $(location).attr("href", basepath+"/admin/company/properties/<?php echo $company->id; ?>/"+published_status);
            });
        });

    </script>
@stop