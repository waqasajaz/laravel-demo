@extends('admin.master')

@section('content')
<!-- content   -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Agents
        <div class="pull-right">
            <a href="{{url('admin/agent/create')}}" class="btn bg-purple pull-right">Add Agent</a>
        </div>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agents as $agent)
                                <tr>
                                    <td>{{ucwords($agent->name)}}</td>
                                    <td>{{$agent->email}}</td>
                                    <td>
                                        <a href="{{url('admin/agent/edit')}}/{{Crypt::encrypt($agent->id)}}">
                                            <span class='glyphicon glyphicon-edit'></span>
                                        </a>
                                        <a onClick="return confirm('Are you sure to delete the data?')" href="{{url('admin/agent/delete')}}/{{Crypt::encrypt($agent->id)}}">
                                            <span class='glyphicon glyphicon-remove'></span>
                                        </a>
                                    </td>
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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script>
    $('#agents').DataTable();
</script>
@stop