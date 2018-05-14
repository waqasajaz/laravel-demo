@extends('admin.master')

@section('content')
    <!-- content   -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Published Assets
        </h1>
    </section>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <style>
        .my-properties__equip {
            padding: 14px 0 0 0;
            margin: 0 -10px;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
        }

        .my-properties__equip-item {
            margin: 0 5px;
        }

        img {
            border-style: none;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body" style="overflow-x: scroll;">
                        <table id="agents" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <!--<th>Asset Type</th>-->
                                <th style="width: 15%;">Asset Title</th>
                                <th>Hood</th>
                                <th>District</th>
                                <th>Cop</th>
                                <th>Size (m<sup>2</sup>)</th>
                                <th>Price</th>
                                <?php if($type == "rent") { ?>
                                <th>Guarntee</th>
                                <?php } ?>
                                <th>Features</th>
                                <th>Picture</th>
                                <th>Image Gallery</th>
                                <th>Documents</th>
                                <th>Favourite Space</th>
                                <th>About Hood</th>
                                <th>Description Help Needed</th>
                                <th>Images Help Needed</th>
                                <th>Documentation Help Needed</th>
                                <th>Price Help Needed</th>
                                <th>Contact Name</th>
                                <th>Contact Phone</th>
                                <th>Contact Email</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($assets as $asset)
                                <tr>
                                    <td style="width: 15%;">{{$asset->direccion}} @if(!$asset->admin_notified)
                                            <small class="label label-danger">New</small> @endif</td>
                                    <td>
                                        @if(isset($asset->district->dist_name))
                                            {{ $asset->district->dist_name  }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($asset->hood->hood))
                                            {{ $asset->hood->hood  }}
                                        @endif
                                    </td>
                                    <td>{{ $asset->cops  }}</td>
                                    <td>{{ $asset->sizem2  }}</td>
                                    <td>&euro;{{($asset->property_deal == 'SALE')?$asset->price:$asset->price.'/mo'}}</td>
                                    <?php if($type == "rent") { ?>
                                    <td>&euro;<?php echo $asset->guarantee; ?></td>
                                    <?php } ?>
                                    <td class="my-properties__equip" style="border: none;">
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
                                        <?php foreach($features as $feature => $value){ if($asset[$feature] == 1) { ?>
                                        <?php if($value['icon'] != "") { ?>
                                        <div class="my-properties__equip-item">
                                            <img src="<?php echo $value['icon']; ?>" title="{{$value['name']}}"/>
                                        </div>
                                        <?php } } } ?>
                                    </td>
                                    <td style="width: 200px;">
                                        @if(isset($asset->image->filename))
                                            <a class="property_images" href="{{ url('/property/'.$asset->id.'/images')  }}">
                                            <img src="<?php
                                            if (Storage::disk('s3')->exists("Properties/" . $asset->id . "/thumbs/" . $asset->image->filename)) {
                                                echo Storage::disk('s3')->url("Properties/" . $asset->id . "/thumbs/" . $asset->image->filename);
                                            } elseif (Storage::disk('s3')->exists("Properties/" . $asset->id . "/" . $asset->image->filename)) {
                                                echo Storage::disk('s3')->url("Properties/" . $asset->id . "/" . $asset->image->filename);
                                            } else {
                                                echo asset('/storage/Property/' . $asset->id . '/' . $asset->image->filename);
                                            } ?>" class="img-responsive"/>
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{url('/property/'.$asset->id.'/gallery')}}" title="Property Images"><i class="fa fa-image"></i></a>
                                    </td>
                                    <td style="font-size: 25px;">
                                        @if(isset($asset->owner_certificate))
                                            <?php
                                            $imagestore = (isset($asset->owner_certificate)) ? asset('/storage/ownercertificates/' . $asset->owner_certificate->post_id . '/' . $asset->owner_certificate->filename) : '';
                                            $file = Storage::disk('s3')->exists("Ownercertificates/" . $asset->id . "/" . $asset->owner_certificate->filename) ? Storage::disk('s3')->url("Ownercertificates/" . $asset->id . "/" . $asset->owner_certificate->filename) : $imagestore;

                                            ?>
                                            <?php if(strpos($asset->owner_certificate->filename, ".doc") !== false || strpos($asset->owner_certificate->filename, '.odt') !== false || strpos($asset->owner_certificate->filename, '.docx') !== false) {?>
                                            <a href="{{$file}}" target="_new" title="Owner Certificate - Doc" style="margin-right:5px;"><i class="fa fa-file"></i></a>
                                            <?php }else if(strpos($asset->owner_certificate->filename, ".pdf") !== false ){  ?>
                                            <a href="{{$file}}" target="_new" title="Owner Certificate - PDF" style="margin-right:5px;"><i class="fa fa-file"></i></a>
                                            <?php }else if(!empty($asset->owner_certificate->filename)){ ?>
                                            <a href="{{$file}}" target="_new" title="Owner Certificate - Image" style="margin-right:5px;"><i class="fa fa-image"></i></a>
                                            <?php }else { ?>
                                            <?php } ?>
                                        @endif

                                        @if(isset($asset->energy_certificate))
                                            <?php
                                            $imagestore = (isset($asset->energy_certificate)) ? asset('/storage/enrgycertificats/' . $asset->energy_certificate->post_id . '/' . $asset->energy_certificate->filename) : '';
                                            $file = Storage::disk('s3')->exists("Energycertificates/" . $asset->id . "/" . $asset->energy_certificate->filename) ? Storage::disk('s3')->url("Energycertificates/" . $asset->id . "/" . $asset->energy_certificate->filename) : $imagestore;
                                            ?>
                                            <?php if(strpos($asset->energy_certificate->filename, ".doc") !== false || strpos($asset->energy_certificate->filename, '.odt') !== false || strpos($asset->energy_certificate->filename, '.docx') !== false) {?>
                                            <a href="{{$file}}" target="_new" title="Energy Certificate - Doc" style="margin-right:5px;"><i class="fa fa-file"></i></a>
                                            <?php }else if(strpos($asset->energy_certificate->filename, ".pdf") !== false ){  ?>
                                            <a href="{{$file}}" target="_new" title="Energy Certificate - PDF" style="margin-right:5px;"><i class="fa fa-file"></i></a>
                                            <?php }else if(!empty($asset->energy_certificate->filename)){ ?>
                                            <a href="{{$file}}" target="_new" title="Energy Certificate - Image" style="margin-right:5px;"><i class="fa fa-image"></i></a>
                                            <?php }else { ?>
                                            <?php } ?>
                                        @endif
                                    </td>
                                    <td>{{ $asset->favourite_space  }}</td>
                                    <td>{{ $asset->about_hood  }}</td>
                                    <td>{{($asset['description_help_needed'] == 1)?"Yes":"-"}}</td>
                                    <td>{{($asset['images_help_needed'] == 1)?"Yes":"-"}}</td>
                                    <td>{{($asset['documentation_help_needed'] == 1)?"Yes":"-"}}</td>
                                    <td>{{($asset['price_help_needed'] == 1)?"Yes":"-"}}</td>
                                    <td>{{($asset->property_contact)?ucfirst($asset->property_contact->contact_name):"-"}}</td>
                                    <td>{{($asset->property_contact)?$asset->property_contact->contact_phone:"-"}}</td>
                                    <td>{{($asset->property_contact)?$asset->property_contact->contact_email:"-"}}</td>
                                    <td>
                                        <?php if($asset->verified) { ?>
                                        <a href="{{url("admin/property/verify/".$asset->id)}}">
                                            <button type="button" class="btn btn-block btn-success btn-xs"><i class="fa fa-check"></i> Verifyed</button>
                                        </a>
                                        <?php } else { ?>
                                        <a href="{{url("admin/property/unverify/".$asset->id)}}">
                                            <button type="button" class="btn btn-block btn-danger btn-xs"><i class="fa fa-times"></i> Unverified</button>
                                        </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                {{ $assets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
@stop