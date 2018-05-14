@extends('admin.master')
@section('content')
    <section class="content-header">
        <h1>
            <i class="fa fa-image"></i> Schedule visit for - <?php echo $property->direccion; ?>
        </h1>
    </section>
    <section  class="content">
        <div class="row">
            <form class="form-horizontal" action="{{url('/schedule')}}" method="post">
                {{csrf_field()}}
                <div class="col-md-7">
                    <label class="col-md-1 control-label">Date</label>
                    <div class="col-md-9">
                        <input type="hidden" class="form-control" id="property" name="property" value="<?php echo $property->id; ?>" readonly required>
                        <input type="text" class="form-control"
                               id="schedule"
                               name="schedule"
                               required
                               value="<?php
                                   $dates = [];
                                   if($property->schedules)
                                   {
                                       foreach($property->schedules as $date){
                                           $dates[] = $date->scheduled_dates;
                                       }
                                   }
                                   echo trim(implode(",",$dates));
                               ?>"
                        >
                    </div>
                    <button type="submit" class="btn btn-success">Schedule</button>
                </div>
                <div class="col-md-5">
                    <ul id="selected_dates">
                    <?php if($property->schedules) { ?>
                        <?php foreach($property->schedules as $date){ ?>
                            <li><?php echo $date->scheduled_dates; ?></li>
                    <?php } } ?>
                    </ul>
                </div>
            </form>
        </div>
    </section>
@stop
@section('script')
    <script type="text/javascript">
    $(document).ready(function(){
        $("#schedule").datepicker({
            format: 'yyyy-mm-dd',
            multidate: true
        });

        $("#schedule").change(function(){
            $("#selected_dates").html("");
            $dates = $(this).val();
            selected_dates = $dates.split(",");
            for(i in selected_dates)
            {
                $("#selected_dates").append('<li>'+selected_dates[i]+'</li>');
            }
        });
    });
    </script>
@stop