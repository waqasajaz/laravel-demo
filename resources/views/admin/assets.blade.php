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
                                <!--<th>Asset Type</th>-->
                                <th>Asset Title</th>
                                <th>Hood</th>
                                <th>District</th>
                                <th>Cops</th>
                                <th>Size (m<sup>2</sup>)</th>
                                <th>Gallery</th>
                                <th>Agent</th>
                                <th>Contact Name</th>
                                <th>Contact email</th>
                                <th>Contact phone</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($assets as $asset)
                                <tr>
                                <!--<td>{{$asset->property_deal}}</td>-->
                                    <td>{{$asset->direccion}} @if(!$asset->admin_notified)
                                            <small class="label label-danger">New</small> @endif
                                    </td>
                                    <td>{{ $asset->hood  }}</td>
                                    <td>{{ $asset->dist_name  }}</td>
                                    <td>{{ $asset->cops  }}</td>
                                    <td>{{ $asset->sizem2  }}</td>
                                    <td>
                                        <a href="{{url('/property/'.$asset->id.'/gallery')}}" title="Property Images"><i class="fa fa-image"></i></a>
                                    </td>
                                    <td>
                                        @if(isset($asset->agent))
                                            <span class="label label-success">{{$asset->agent->name}}</span>
                                        @else
                                            <span class="label label-danger">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td>{{ $asset->contact_name  }}</td>
                                    <td>{{ $asset->contact_email  }}</td>
                                    <td>{{ $asset->contact_phone  }}</td>
                                    <td>
                                        <select class="form-control" onchange="return allocateAgent(this.value, {{$asset->id}})">
                                            <option value="0">Please Assign Agent</option>
                                            @forelse($agents as $agent)
                                                <option value="{{$agent->id}}" {{(isset($asset->agent) && $asset->agent->id == $agent->id)?'selected':''}}>{{$agent->name}}</option>
                                            @empty
                                            @endforelse
                                        </select>
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

    <script type="text/javascript">
        function allocateAgent(agent_id, asset_id)
        {
            $.ajax({
                url: "{{ url('/admin/assign/agent') }}",
                type: 'post',
                async: false,
                data: {
                    "_token": '{{ csrf_token() }}',
                    "agent_id": agent_id,
                    "asset_id": asset_id
                },
                success: function(response) {
                    $('#gif').hide();
                    location.reload();
                }
            });
        }
    </script>
@stop