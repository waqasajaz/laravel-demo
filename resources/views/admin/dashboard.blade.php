@extends('admin.master')

@section('content')
    <!-- content   -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{trans('labels.offerlbl')}}
            <a class="btn btn-app pull-right" href="{{ url('admin/exportoffer/xls') }}/{{(isset($status))?$status:'all'}}{{(isset($type))?'/'.$type:''}}">
                <i class="fa fa-download"></i> EXCEL
            </a>
            <a class="btn btn-app pull-right" href="{{ url('admin/exportoffer/csv') }}/{{(isset($status))?$status:'all'}}{{(isset($type))?'/'.$type:''}}">
                <i class="fa fa-download"></i> CSV
            </a>
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
                                @if(!isset($type))
                                    <th>Type</th>
                                @endif
                                <th>{{trans('labels.namelbl')}}</th>
                                <th>{{trans('labels.refidlbl')}}</th>
                                <th>{{trans('labels.datelbl')}}</th>
                                <th>{{trans('labels.assetlbl')}}</th>
                                <th>Gallery</th>
                                <th>Hood</th>
                                <th>District</th>
                                <th>Cop</th>
                                <th>Size (m<sup>2</sup>)</th>
                                <th>{{trans('labels.step1lbl')}}</th>
                                <th>PriceListed</th>
                                <th>{{trans('labels.step2lbl')}}</th>
                                <th>Mortgage</th>
                                <th>Cash</th>
                                <th>{{trans('labels.step3lbl')}}</th>
                                <th>{{trans('labels.step4lbl')}}/RentalPeriod</th>
                                <th>{{trans('labels.step5lbl')}}</th>
                                <th>{{trans('labels.step6lbl')}}</th>
                                <th>{{trans('labels.step7lbl')}}</th>
                                <th>Contact name</th>
                                <th>Contact email</th>
                                <th>Contact phone</th>
                                @if(isset($actionColumn) && $actionColumn == 1)
                                    <th>Action</th>
                                @endif
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
        $(document).ready(function () {
            get_offers();
        });

        function get_offers() {
            if (offer_table != "") {
                offer_table.destroy();
            }
            $.ajax({
                url: basepath + "/admin/get-offers",
                type: "POST",
                data: {
                    "_token": $("input[name='_token']").val(),
                    "type": '<?php echo (isset($type)) ? $type : "";?>',
                    "status": '<?php echo $status; ?>',
                    "action": '<?php echo $actionColumn; ?>'
                },
                success: function (res) {
                    res = jQuery.parseJSON(res);
                    total_offers = res.total;
                    $("#offers tbody").html(res.result);

                    if (refreshtimer != "") {
                        clearInterval(refreshtimer);
                    }

                },
                complete: function () {
                    offer_table = $('#offers').DataTable({
                        "aaSorting": [],
                        "fnDrawCallback": function (oSettings) {
                            refreshtimer = setInterval(function () {
                                refresh_table();
                            }, 5000);
                        }
                    });

                    $(".offer_sold").off("click");
                    $(".offer_sold").click(function(e){
                        e.preventDefault();
                        ajaxurl = $(this).attr("href");
                        $.post(ajaxurl,{"_token":$("input[name='_token']").val()},function(){})
                                .done(function(){
                            });

                    });
                }
            });
        }

        function refresh_table() {
            $.ajax({
                url: basepath + "/admin/get-offers",
                type: "POST",
                data: {
                    "_token": $("input[name='_token']").val(),
                    "type": '<?php echo (isset($type)) ? $type : "";?>',
                    "status": '<?php echo $status; ?>',
                    "action": '<?php echo $actionColumn; ?>',
                    "total": total_offers
                },
                success: function (res) {
                    res = jQuery.parseJSON(res);
                    if (res) {
                        get_offers();
                    }
                }
            });
        }
    </script>
@stop