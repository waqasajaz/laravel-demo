@extends('admin.master')

@section('content')
    <!-- content   -->
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
    <!-- Content Header (Page header) -->
    <section class="content-header">

        <h1>
            @if($type=="yes")
            LISTING ASSETS VISITED
                @else
                LISTING TOUR REQUESTED
                @endif
        </h1>
    </section>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">

                    <div class="box-body" style="overflow-x: scroll;">
                        <table id="offers" class="table table-bordered table-striped">
                            <thead>
                            <tr>

                                <th>Asset</th>
                                <th>Hood</th>
                                <th>District</th>
                                <th>Features</th>
                                <th>Listed Price</th>
                                <th>Owner Info</th>
                                <th>visitor Info</th>
                                <th>Date Requested</th>
                                <th>
                                    @if($type=='yes')
                                        Visited Date
                                    @else
                                        Visited?
                                        @endif
                                </th>

                            </tr>
                            </thead>
                            <tbody></tbody>
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

    <script type="text/javascript">

        var total_offers = 0;
        var refreshtimer = "";
        var offer_table = "";
        $(document).ready(function(){
            get_offers();
        });

        function get_offers()
        {
            if(offer_table != "")
            {
                offer_table.destroy();
            }
            $.ajax({
                url:basepath+"/admin/get-tour-requested",
                type:"POST",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "type" : '<?php echo (isset($type))?$type:"";?>', 
                },
                success:function(res){
                    res = jQuery.parseJSON(res);
                    total_offers = res.total;
                    $("#offers tbody").html(res.result);

                    if(refreshtimer != "")
                    {
                        clearInterval(refreshtimer);
                    }
                    $('.datepickers').datepicker({
                        format:'yyyy-mm-dd'
                    });

                },
                complete:function(){
                    offer_table = $('#offers').DataTable({
                        "aaSorting": [],
                        "fnDrawCallback": function (oSettings) {
                            refreshtimer = setInterval(function(){
                                refresh_table();
                                $('.datepickers').datepicker({
                                    format:'yyyy-mm-dd'
                                });
                            }, 5000);
                        }
                    });
                }
            });
        }

        function refresh_table()
        {
            $.ajax({
                url:basepath+"/admin/get-tour-requested",
                type:"POST",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "type" : '<?php echo (isset($type))?$type:"";?>',
                    "total" : total_offers
                },
                success:function(res){
                    res = jQuery.parseJSON(res);
                    if(res)
                    {
                        get_offers();
                    }
                }
            });
        }

    </script>
@stop