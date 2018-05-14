@extends('admin.master')

@section('content')
<!-- content   -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Assets
    </h1>    
</section>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body" style="overflow-x: scroll;">
                    <table id="agents" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Asset Type</th>
                                <th>Asset Title</th>
                                <th>Asset Hood</th>
                                <th>Asset District</th>
                                <th>Asset Zipcode</th>
                                <th>Asset Price</th>
                                <th>Asset Detials</th>
                                <th>Asset Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                                <tr>
                                    <td>
                                        {{$asset->property_deal}}
                                    </td>
                                    <td>
                                        {{$asset->direccion}}
                                        <?php
                                            if($asset->agent_assigned_date != ''){
                                                $date1=date_create(date('Y-m-d'));
                                                $date2=date_create($asset->agent_assigned_date);
                                                $duration = date_diff($date1,$date2);
                                                if($duration->d < 5) {
                                                    echo '<small class="label label-danger">New</small>';
                                                }
                                            }
                                        ?>
                                    </td>
                                    <td>{{ $asset->hood->hood }}</td>
                                    <td>{{ $asset->district->dist_name  }}</td>
                                    <td>{{ $asset->cops  }}</td>
                                    <td>&euro;{{($asset->property_deal == 'SALE')?$asset->price:$asset->price.'/mo'}}</td>
                                    <td>{{isset($asset->rooms)?$asset->rooms:''}} bed, {{isset($asset->bathrooms)?$asset->bathrooms:''}} baths</td>
                                    <td>{{isset($asset->sizem2)?$asset->sizem2:''}} m2</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
<script>
    $('#agents').DataTable({
        "aaSorting": []
    });
</script>
@stop