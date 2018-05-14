@extends('layouts.app')
@section('title', 'Property Evolution | '.$property->direccion)
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('frontend/styles/main-style.css')}}">
<main>
<div class="page">
    <div class="container">
        <div class="page__title" style="text-align: center;">Property Evolution : {{$property->direccion}}</div>
        <div class="clearfix progress_bar">
            <div class="left_progressbar">
                <h2>Offers In-progress</h2>
                <div class="processing_offer_at" style="font-size: 12px;font-weight: bold;"></div>
                <div class="loan_program_total_circle" style="height: 175px;width: 175px;">
                    <div class="income__big total_inprocess" id="total_price_display">{{$property->offers->where('step_7_completed', 1)->count()}}</div>
                </div>
            </div>
             <div class="right_progressbar">
                 <h2>Offers Completed</h2>
                 <div class="complete_offer_at" style="font-size: 12px;font-weight: bold;"></div>
                 <div class="loan_program_total_circle" style="height: 175px;width: 175px;">
                      <div class="income__big total_completed" id="total_price_display">{{$property->offers->where('step_7_completed', 1)->count()}}</div>
                  </div>
            </div>
        </div>
        <div class="row" style="margin: 100px 0 10px;">
            <div class="chart_filters" style="width: 50%; padding: 0 25px;display: inline-block;">
                <select id="year" class="custom-select">
                    <?php for($i=date('Y');$i>=2012;$i--){ ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php } ?>
                </select>
                <select id="month" class="custom-select">
                    <?php for($i=1;$i<=12;$i++){  $month = date('F', mktime(0,0,0,$i, 1, date('Y'))); ?>
                    <option value="<?php echo $month; ?>" <?php echo ($month == date("F"))?"selected":""; ?>><?php echo $month; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="chart_filters" style="width: 49%; padding: 0 25px;display: inline-block;">
                <label class="">Select Date</label>
                <input type="text" id="week_date" class="text-input"/>
            </div>
        </div>
        <div class="row" style="text-align: center;margin:0px 0 100px">
            <div class="left_chart">
                <canvas class="chart-canvas" style="width: 550px;height: 350px;" id="offers_monthly"></canvas>
            </div>
             <div class="right_chart">
                 <canvas class="chart-canvas" style="width: 550px;height: 350px;" id="offers_weekly"></canvas>
            </div>
        </div>
        <div class="clearfix progress_bar">
            <div class="left_progressbar">
                <h2>Visits Monthly</h2>
                <div class="loan_program_total_circle" style="height: 175px;width: 175px;">
                    <div class="income__big" id="total_price_display">{{($visits_monthly)?$visits_monthly['visits']:'0'}}</div>
                </div>
            </div>
             <div class="right_progressbar">
                 <h2>Visits Weekly</h2>
                 <div class="loan_program_total_circle" style="height: 175px;width: 175px;">
                      <div class="income__big" id="total_price_display">{{($visits_weekly)?$visits_weekly['visits']:'0'}}</div>
                  </div>
            </div>
        </div>
        <div class="row" style="text-align: center;margin:100px 0px">
            <div class="left_chart" style="display: none">
                <canvas class="chart-canvas" style="width: 550px;height: 350px;" id="myChart_monthly"></canvas>
            </div>
             <div class="right_chart"  style="display: none">
                 <canvas class="chart-canvas" style="width: 550px;height: 350px;" id="myChart_weekly"></canvas>
            </div>
        </div>
    </div>
</div>
</main>
<script type="text/javascript" src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
<script type="text/javascript" src="http://www.chartjs.org/samples/latest/utils.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        monthly_offers();
        weekly_offers();
        $("#year, #month").change(function(){
            monthly_offers();
        });

        $("#week_date").datepicker({
            dateFormat:"yy-mm-dd",
            onSelect: function(selected,evnt) {
                weekly_offers();
            }
        });
    });


    /* MONTHLY OFFERS REPORT START */
    var labels_monthly = [];
    var records_months = [];
    var offers_inprogress_scale_monthly = [];
    var offers_completed_scale_monthly = [];
    var ctx = document.getElementById("offers_monthly").getContext('2d');
    var report = {
        labels: labels_monthly,
        datasets: [
            {
                label: "Inprogress",
                fill: false,
                backgroundColor: '#00ab8a',
                borderColor: '#00ab8a',
                data: offers_inprogress_scale_monthly
            },
            {
                label: "Completed",
                fill: false,
                backgroundColor: '#ff9700',
                borderColor: '#ff9700',
                data: offers_completed_scale_monthly
            }
        ]
    };

    var config = {
        type: 'line',
        data: report,
        options: {
            responsive: true,
            legend: {display: false},
            title:{
                display:true,
                text:'Offers Monthly'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: 5,
                    },
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) {
                        offer = (tooltipItems.yLabel > 0)?records_months[tooltipItems.index]:0;
                        return [data.datasets[tooltipItems.datasetIndex].label +': ' + tooltipItems.yLabel, "Offer : "+offer];
                    }
                }
            },
        }
    };

    var myOffersLineChartMonthtly = new Chart(ctx, config);

    /* MONTHLY OFFERS REPORT START */


    /* WEEKLY OFFERS REPORT START */
    var labels_weekly = [];
    var records_weekly = [];
    var offers_inprogress_scale_weekly = [];
    var offers_completed_scale_weekly = [];
    var ctx_week = document.getElementById("offers_weekly").getContext('2d');

    var config_week = {
        type: 'line',
        data: {
            labels: labels_weekly,
            datasets: []
        },
        options: {
            responsive: true,
            legend: {display: false},
            title:{
                display:true,
                text:'Offers Weekly'
            },
            scales: {
                yAxes: [{
                    ticks: {
                        stepSize: 5,
                    },
                }],
                xAxes: [{
                    ticks: {
                        stepSize: 1,
                        autoSkip: false
                    },
                    position: 'bottom'
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItems, data) {
                        offer = (tooltipItems.yLabel > 0)?records_weekly[tooltipItems.index]:0;
                        return [data.datasets[tooltipItems.datasetIndex].label +': ' + tooltipItems.yLabel, "Offer : "+offer]; 
                    }
                }
            },
        }
    };

    var myLineChartWeekly = new Chart(ctx_week, config_week);

    /* MONTHLY OFFERS REPORT START */

    function monthly_offers()
    {
        $.ajax({
            url:basepath+"/user/offer_chart_data",
            type:"post",
            data:{
                id:'<?php echo $property->id; ?>',
                year:$("#year").val(),
                month:$("#month").val(),
                "_token":$("input[name='_token']").val()
            },
            success:function(res){
                res = jQuery.parseJSON(res);
                $(".total_inprocess").text(res.total_inprocess);
                $(".total_completed").text(res.total_completed);

                $(".processing_offer_at").text($("#month").val()+" - "+$("#year").val());
                $(".complete_offer_at").text($("#month").val()+" - "+$("#year").val());

                labels_monthly = $.map(res.labels_monthly, function(value, index) {  return [index]; });
                records_months = $.map(res.labels_monthly, function(value, index) {  return [value]; });
                offers_inprogress_scale_monthly = $.map(res.offers_inprogress_scale_monthly, function(value, index) {  return [value]; });
                offers_completed_scale_monthly = $.map(res.offers_completed_scale_monthly, function(value, index) {  return [value]; });

            },
            complete:function(){

                myOffersLineChartMonthtly.data.labels = labels_monthly;
                myOffersLineChartMonthtly.data.datasets = [
                    {
                        label: "Inprogress",
                        fill: false,
                        backgroundColor: '#00ab8a',
                        borderColor: '#00ab8a',
                        data: offers_inprogress_scale_monthly,
                    },
                    {
                        label: "Completed",
                        fill: false,
                        backgroundColor: '#ff9700',
                        borderColor: '#ff9700',
                        data: offers_completed_scale_monthly
                    }
                ];

                myOffersLineChartMonthtly.update();
            }
        });
    }

    function weekly_offers()
    {
        $.ajax({
            url:basepath+"/user/week_offer_chart_data",
            type:"post",
            data:{
                id:'<?php echo $property->id; ?>',
                "date":$("#week_date").val(),
                "_token":$("input[name='_token']").val()
            },
            success:function(res){

                res = jQuery.parseJSON(res);

                labels_weekly = $.map(res.labels_weekly, function(value, index) {  return [index]; });
                records_weekly = $.map(res.labels_weekly, function(value, index) {  return [value]; });
                offers_inprogress_scale_weekly = $.map(res.offers_in_progress_weekly, function(value, index) {  return [value]; });
                offers_completed_scale_weekly = $.map(res.offers_completed_weekly, function(value, index) {  return [value]; });

            },
            complete:function(){
                myLineChartWeekly.data.labels = labels_weekly;
                myLineChartWeekly.data.datasets = [
                    {
                        label: "Inprogress",
                        fill: false,
                        backgroundColor: '#00ab8a',
                        borderColor: '#00ab8a',
                        data: offers_inprogress_scale_weekly
                    },
                    {
                        label: "Completed",
                        fill: false,
                        backgroundColor: '#ff9700',
                        borderColor: '#ff9700',
                        data: offers_completed_scale_weekly
                    }
                ];

                myLineChartWeekly.update();
            }
        });
    }

</script>
@endsection
