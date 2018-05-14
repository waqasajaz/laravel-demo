@extends('layouts.app')
@section('title', 'Loquare | '.$hood->district->dist_name." | ".$curr_neighbour['hood'])
@section('content')

    <main>
        <input type='hidden' name='curr_neighbour_id' id='curr_neighbour_id' value='<?php echo $curr_neighbour['id']; ?>' >
        <div class="banner all-neighbours">
            <div class="container abslte">
                <div class="row">
                    <h3><span>&#60;</span> All areas </h3>
                    <h1><?php echo $curr_neighbour['hood'];?></h1>
                    <input type="hidden" id="hood_name" value="<?php  echo trim($curr_neighbour['hood']); ?>">
                    <h4>Area overview for future residets</h4>
                </div>
            </div>
        </div>
      <div class="fix-nav-bar">
            <div class="container">
                <ul>
                    <li class="current page-scroll"><a href="#overveiw">Overview</a></li>
                    <li class="page-scroll"><a href="#lifestyle">Lifestyle</a></li>
                    <li class="page-scroll"><a href="#memographics">Demographics</a></li>
                    <li class="page-scroll"><a href="#pricing">Pricing</a></li>
                    <li class="page-scroll"><a href="#satelite">Satelite</a></li>
                </ul>
            </div>
        </div>

        <div class="main-body less-pad">
            <!--fix-nav-->
            <div class="container">
                <div class="top-over"></div>
                <section class="less-pad" id="overveiw">
                    <div class="row overview_section">
                        <div class="col-sm-6 col-md-5">
                            <div class="text-bx">
                                <h3 class="text-head mt-top">Area Overview</h3>
                                <p>Ciutat Vella (Catalan pronunciation: [siwˈtad ˈbeʎə], meaning in English "Old City")
                                    is a district of Barcelona, numbered District 1. The name means "old city" in
                                    Catalan and refers to the oldest neighborhoods in the city of Barcelona, Catalonia,
                                    Spain. Ciutat Vella is nestled between the Mediterranean Sea and the neighborhood
                                    called l'Eixample ("the Extension"). It is considered the centre of the city; the
                                    Plaça Catalunya is one of the most popular meeting points in all of Catalonia.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-md-offset-1">
                            <div id="map-hood"></div>
                        </div>
                    </div>
                    <div class="row sec-in">
                        <div class="col-sm-6 col-md-5">
                            <div class="text-bx">
                                <h3 class="text-head">Nearest Transportation</h3>
                                <div class="trans-ptn">
                                    <div class="row no-marg sbway">
                                        <span class="flt-lft">Subway</span>
                                        <ul>
                                            <li><a href="#">L1</a></li>
                                            <li><a href="#">L2</a></li>
                                            <li><a href="#">L3</a></li>
                                            <li><a href="#">L3</a></li>
                                            <li><a href="#">L5</a></li>
                                            <li><a href="#">L7</a></li>
                                            <li><a href="#">L11</a></li>
                                            <li><a href="#">M</a></li>
                                        </ul>
                                    </div>
                                    <div class="row no-marg busess">
                                        <span class="flt-lft">Buses</span>
                                        <ul>
                                            <li class="blue"><a href="#">H8</a></li>
                                            <li class="blue"><a href="#">H10</a></li>
                                            <li class="blue"><a href="#">H12</a></li>
                                            <li class="blue"><a href="#">H14</a></li>
                                            <li><a href="#">V3</a></li>
                                            <li><a href="#">V7</a></li>
                                            <li><a href="#">V13</a></li>
                                            <li><a href="#">V15</a></li>
                                        </ul>
                                    </div>
                                    <div class="row no-marg tramss">
                                        <span class="flt-lft">Trams</span>
                                        <ul>
                                            <li><a href="#">T4</a></li>
                                            <li><a href="#">T5</a></li>
                                            <li><a href="#">T6</a></li>
                                        </ul>
                                    </div>
                                  </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-md-offset-1">
                            <div class="text-bx">
                                <h3 class="text-head"> Area tags</h3>
                                <ul class="area-tag nav nav-pills">
                                    <li><a href="#">Fancy</a></li>
                                    <li><a href="#">modern</a></li>
                                    <li><a href="#">restaurants</a></li>
                                    <li><a href="#">schools</a></li>
                                    <li><a href="#">great transportation</a></li>
                                    <li><a href="#">inspirational</a></li>
                                    <li><a href="#">good for kids</a></li>
                                    <li><a href="#">food for elderly</a></li>
                                    <li><a href="#">touristic</a></li>
                                    <li><a href="#">cool</a></li>
                                    <li><a href="#">very clean</a></li>
                                </ul>
                             </div>
                        </div>
                    </div>
                </section>
            </div>
            <section class="photos ful-sld-bx">
                <div class="top-over"></div>
                <div id="lifestyle"></div>
                <div class="lyf-style">
                    <div><img src="{{ asset('frontend/images/frontend/bg-slide.jpg') }}"></div>
                    <div><img src="{{ asset('frontend/images/frontend/bg-slide.jpg') }}"></div>
                    <div><img src="{{ asset('frontend/images/frontend/bg-slide.jpg') }}"></div>
                </div>
            </section>
            <section class="graph-sec">
                <div class="top-over"></div>
                <div id="memographics"></div>
                <div class="container">
                    <h3 class="text-head text-center mt-botms">Demographics</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="min-graph box-shdw">
                                <h4 class="graph-head">Ethnicity</h4>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <canvas id="nationality" width="170px" height="155px"></canvas>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="infosection__charts-item">
                                            <div class="infosection__chart-legend circl-grph-dtl">
                                                <div>
                                                    <li><span><?php echo $nationality['spanish']['value']; ?>% </span>
                                                        spanish
                                                    </li>
                                                    <li><span><?php echo $nationality['other']['value']; ?>% </span>
                                                        others
                                                    </li>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-shdw white-bx">
                                        <ul class="numbs">
                                            <li class="halfs">
                                                <h3>12%</h3>
                                                <div>
                                                    <img src="{{ asset('frontend/images/frontend/single-icon.png') }}"
                                                         alt=""/> Singles
                                                </div>
                                            </li>
                                            <li class="halfs">
                                                <h3>82%</h3>
                                                <div>
                                                    <img src="{{ asset('frontend/images/frontend/not-sngle-icon.png') }}"
                                                         alt=""/> Not single
                                                 </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="mid-col box-shdw">
                                        <ul class="numbs">
                                            <li>
                                                <h3><?php echo $age['between16_64']['value']; ?></h3>
                                                <div>
                                                    Medium <br/> Age
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="mid-col box-shdw">
                                        <ul class="numbs">
                                            <li>
                                                <h3><?php echo $empty_house['empty_house']['value']; ?>%</h3>
                                                <div>
                                                    Empty <br> House
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-xs-4">
                                    <div class="mid-col box-shdw">
                                        <ul class="numbs">
                                            <li>
                                                <h3>
                                                    <?php
                                                    $economic_index = [];
                                                    for($i=0;$i<5;$i++)
                                                    {
                                                        if(array_key_exists("index_".($i+1),$indexes))
                                                        {
                                                            $economic_index[] =  $indexes["index_".($i+1)]['value'];
                                                        }
                                                    }
                                                    $economic_index = array_unique($economic_index);
                                                        echo implode(", ", $economic_index);
                                                    ?>
                                                </h3>
                                                <div>
                                                    Iconomic <br/> Index
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pad-no-top graph-sec">
                <div class="top-over"></div>
                <div id="pricing"></div>
                <div class="container">
                    <h3 class="text-head text-center mt-botms">Pricing</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="min-graph box-shdw">
                                <h4 class="graph-head">Pricing</h4>
                                <p>Average pricing per square meter in this area over the last <span class='count_years'>1.5 </span> years</p>
                                <div class="row">
                                       <select name="property_deal" id="property_deal" class="custom-select pull-right" style="width:130px;">
                                            <option value="1" selected>SALE</option>
                                            <option value="2">RENT</option>
                                        </select>
                                    <canvas id="pricing_graph"></canvas>
                               </div>
                            </div>
                        </div>
        
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-shdw white-bx singl-pad">
                                        <ul class="numbs">
                                            <?php if($hood_sale_price['avg_price_sale'] != "") { ?>
                                            <li>
                                                 <?php
                                                    $sale_price_amount = str_replace(' ','', $hood_sale_price['avg_price_sale']);
                                                    $sale_price_amount = number_format($sale_price_amount, 2, '.', ',');
                                                ?> 
                                                <h3 class='font_family_h3'>&euro; <?php echo $sale_price_amount; ?> </h3>
                                                <div>
                                                    Median Sale Price
                                                </div>
                                            </li>
                                            <?php } else { ?>
                                            <li>Median Sale Price not available</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="box-shdw white-bx singl-pad">
                                        <ul class="numbs">
                                            <?php if($hood_sale_price['avg_price_sale'] != "") { ?>
                                            <li>
                                                 <?php
                                                    $rent_price_amount = str_replace(' ','',$hood_rent_price['avg_price_rent']);
                                                    $rent_price_amount = number_format($rent_price_amount, 2, '.', ',');
                                                ?> 
                                                <h3 class='font_family_h3 orange'>&euro; <?php echo $rent_price_amount;  ?>  / mo</h3>
                                                <div>
                                                    Median Rent Price
                                                </div>
                                            </li>
                                            <?php } else { ?>
                                            <li>Median Rent Price not available</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                      </div>
                    </div>
                </div>
            </section>

            <section>
                <div class="top-over"></div>
                <div id="satelite"></div>
                <div class="container">
                    <h3 class="text-head text-center mt-botms">SATELLITE IMAGE</h3>
                    <div class="col-sm-12">
                        <div id="satelite-hood"></div>
                    </div>
                </div>
            </section>

            <section class="pad-no-top sell_rent_offers">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="sec-in mx-600">
                                <h3 class="text-head text-center">Want to live in this area</h3>
                                <ul class="mid-btn">
                                    <li class="col-xs-6"><a href="{{ url('/properties/sell/'.$curr_neighbour['id'])  }}" class="btn">see selling offers</a></li>
                                    <li class="col-xs-6"><a href="{{ url('/properties/rent/'.$curr_neighbour['id'])  }}" class="btn">see renting offers</a></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </section>

            <section class="pad-no-top">
                <h3 class="text-head text-center mt-botms">Check out other neighborhoods in this district</h3>

                <div class="slide-place">
                    <?php if($nearby_hoods != false) foreach($nearby_hoods as $hood){?>
                    <div>
                        <a href="{{ url('/neighbour/'.$hood['id'])  }}">
                            <div class="img-gallry">
                                <img src="{{ asset('/storage/district/'.$hood['image']) }}" height='300px' alt="">
                                <div class="overlay"><h3><?php echo $hood['hood'];?></h3></div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>

                </div>
            </section>
      </div>

    @include('short_contactus')

    </main>

    <script type="text/javascript" src="http://www.chartjs.org/dist/2.7.1/Chart.bundle.js"></script>
    <script type="text/javascript" src="http://www.chartjs.org/samples/latest/utils.js"></script>

    <script type="text/javascript">

        var chartoptions = {
            responsive: true,
            legend: {
                display: false,
                position: 'bottom',
            },
            title: {
                display: false,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            cutoutPercentage: 75,
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var datalabel = data.labels[tooltipItem.index];
                        var currentValue = dataset.data[tooltipItem.index];
                        return datalabel + ":" + currentValue + "%";
                    }
                }
            }
        };

        $(window).scroll(function () {
            if ($(this).scrollTop() > 60) {
                $('.navbar-fixed-top').addClass("logo-fix-adon");

            }
            else {
                $('.navbar-fixed-top').removeClass("logo-fix-adon");

            }

            if ($(this).scrollTop() > 500) {
                $('.fix-nav-bar').addClass("nav-fix-adon");

            }
            else {
                $('.fix-nav-bar').removeClass("nav-fix-adon");

            }
         });
    </script>

    <script type="text/javascript">
        $('.slide-place').slick({
            dots: true,
            infinite: false,
            speed: 300,
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        });
    </script>


    <script type="text/javascript">
        $(function (){
            $('.page-scroll a').bind('click', function (event) {
                var $anchor = $(this);
                $('html, body').stop().animate({
                    scrollTop: $($anchor.attr('href')).offset().top - 160
                }, 1500, 'easeInOutExpo');
                event.preventDefault();
            });

            $('body').scrollspy({
                target: '.navbar-fixed-top'
            })

            $(".fix-nav-bar li").click(function () {
                $(".fix-nav-bar li").removeClass("current");
                $(this).addClass("current");
            });
        });
    </script>

    <script type="text/javascript">
	$(document).ready(function(){

        ethnicity();

        $('.lyf-style').slick();

         historical_cost_label = [
            <?php if($hood_lists != false){
                foreach($hood_lists as $hood_list){
                    if(!empty($hood_list['year'])) { echo $hood_list['year'].','; }
                }
            }
            ?>
         ];
      
         historical_cost_sale = [
            <?php if($hood_lists != false) {
             foreach($hood_lists as $hood_list){
                     if(!empty($hood_list['price'])) {  echo $hood_list['price'].','; }
                }
            }
            ?>
         ];
    
        <?php
            $count_years = "";
            if($hood_lists != false) {
            foreach($hood_lists as $hood_list){
                $year_array[]=$hood_list['year'];
            }
            $count_duplicate_year=array_count_values($year_array); //count duplicate/same values 

            $count_years='0';
            foreach($count_duplicate_year as $quarter)
            {
                $count_year_by_quarter=($quarter-1)*(1/$quarter); 
                $count_years=$count_years+$count_year_by_quarter;
            }
            }
        ?>

        $('.count_years').text(<?php echo $count_years; ?>); 
        var historical_cost = historical_cost_sale;
        var q_number='1';
        var config = {
            type: 'line',
            data: {
                labels: historical_cost_label,
                datasets: [{
                    label: "SALE",
                    fill: false,
                    backgroundColor: '#00ab8a',
                    borderColor: '#00ab8a',
                    data: historical_cost
					}]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Pricing Graph'
                },
                 scales: {
                        yAxes: [{
                             ticks: {
                                  stepSize: 100000,
                                },
                          }],
                        xAxes:[{
                              ticks:{
                                  callback: function (value) {
                                        q_number=(q_number>4)?'1':q_number;
                                        return "Q"+(q_number++)+" "+Number(value)
                                    }},
                              }]
                    },
                    tooltips: {
                        callbacks: {
                        label: function(tooltipItems, data) {
                                        return data.datasets[tooltipItems.datasetIndex].label +': € ' + tooltipItems.yLabel;
                                    } }},
                    }
        };

        var pricing_graph = document.getElementById("pricing_graph").getContext("2d");
        window.myLine = new Chart(pricing_graph, config);
          
       //filter price value by dropdown value(rent/sale) select
        $("#property_deal").change(function()
        {
      		var property_deal= $("#property_deal").val();
            var curr_neighbour_id=$('#curr_neighbour_id').val();
            var stepsize=(property_deal == 1)?'100000':'500';
            var label=(property_deal == 1)?'SALE':'RENT';
            var lineColor=(property_deal == 1)?'#00ab8a':'#FF9700';
            
            $.ajax({
                    url:basepath+"/sell_rent_price",
                    type: 'POST',
                    data: {
                             "curr_neighbour_id":curr_neighbour_id,
                             "property_deal":property_deal,
                             "_token":$("input[name='_token']").val()
                          },        
                    success: function (res) {
                            res = jQuery.parseJSON(res);
                            historical_cost=eval(res.historical_cost_price);
                     
                            var config_ctx = {
                                    type: 'line',
                                    data: {
                                        labels: historical_cost_label,
                                        datasets: [{
                                            label: label,
                                            fill: false,
                                            backgroundColor: lineColor,
                                            borderColor: lineColor,
                                            data: historical_cost
                                            }]
                                    },
                                    options: {
                                        responsive: true,
                                        title:{
                                            display:true,
                                            text:'Pricing Graph'
                                        },
                                         scales: {
                                                yAxes: [{
                                                     ticks: {
                                                          stepSize: stepsize,
                                                        },
                                                }],
                                                xAxes:[{
                                                          ticks:{
                                                              callback: function (value) {
                                                                   q_number=(q_number>4)?'1':q_number;
                                                                    return "Q"+(q_number++)+" "+Number(value)
                                                                }},
                                                }]
                                                },
                                        tooltips: {
                                            callbacks: {
                                            label: function(tooltipItems, data) {
                                                            return data.datasets[tooltipItems.datasetIndex].label +': € ' + tooltipItems.yLabel;
                                                } }},
                                     }
                                 };

                                var pricing_graph = document.getElementById("pricing_graph").getContext("2d");
                                var myLine = new Chart(pricing_graph, config_ctx);
                                
                                addData(myLine, historical_cost, 0);

                                function addData(chart, data, datasetIndex) {
                                    chart.data.datasets[datasetIndex].data = data;
                                    chart.update();
                                }
                        }
            });
         });
  });

        function ethnicity()
        {
            <?php
                $chartdata = [];
                $datalabel = [];

                foreach($nationality as $national)
                {
                    $chartdata[] = $national['value'];
                    $datalabel[] = $national['title'];
                }
            ?>
            var national = {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [<?php echo implode(", ", $chartdata); ?>],
                                backgroundColor:[
                                    "#FF9700",
                                    "#00AB8A"
                                ],
                                label: 'nationality'
                            }],
                                labels: [<?php echo implode(", ", $datalabel); ?>]
                        },
                        options: chartoptions
                    };
            var ctx = document.getElementById("nationality").getContext("2d");
            window.national = new Chart(ctx, national);
        }

    </script>
@endsection