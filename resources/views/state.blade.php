@extends('layouts.app')
@section('title', 'Loquare | '.$curr_district['dist_name'])
@section('content')
    <main>
        <div class="banner all-states">
            <div class="container abslte">
                <div class="row">
                    <h3><span>&#60;</span> All areas</h3>
                    <h1><?php echo $curr_district['dist_name'];?></h1>
                    <input type="hidden" id="dist_name" value="<?php  echo trim($curr_district['dist_name']); ?>">
                    <h4>consists of <?php echo count($hoods); ?> neighborhoods</h4>
                </div>
            </div>
        </div>
        <div class="main-body less-pad">
            <div class="container">
                <section class="less-pad">
                    <div class="row">
                        <div class="col-sm-6 col-md-5">
                            <div class="text-bx">
                                <h3 class="text-head mt-top">Area Overview</h3>
                                <p class='overview_text'>Ciutat Vella (Catalan pronunciation: [siwˈtad ˈbeʎə], meaning
                                    in English "Old City") is a district of Barcelona, numbered District 1. The name
                                    means "old city" in Catalan and refers to the oldest neighborhoods in the city of
                                    Barcelona, Catalonia, Spain. Ciutat Vella is nestled between the Mediterranean Sea
                                    and the neighborhood called l'Eixample ("the Extension"). It is considered the
                                    centre of the city; the Plaça Catalunya is one of the most popular meeting points in
                                    all of Catalonia.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-md-offset-1 state_div">
                            <div id='map-state'></div>
                        </div>
                    </div>
                </section>

                <section class="photos">
                    <h2 class="mid-head">Photos</h2>
                    <div class="row">
                        <div class="col-sm-12 col-md-12  col-xs-12">
                            <div class="photos-bx">
                                <?php
                                if(!empty($galleries)){
                                foreach($galleries as $key=>$gal_img){ if($key % 2 == 0){?>
                                <div>
                                    <div class="in-phots"><a href="#"><img
                                                    src="{{ asset('/storage/district/'.$gal_img['image']) }}"
                                                    height='280px' alt=""></a></div>
                                    <?php }else{?>
                                    <div class="in-phots "><a href="#"><img
                                                    src="{{ asset('/storage/district/'.$gal_img['image']) }}"
                                                    height='280px' alt=""></a></div>
                                </div>
                                <?php }}} ?>
                            </div>
                        </div>
                    </div>
                </section>
                <!--end of section-->
                <section class="nbrhd">
                    <h2 class="mid-head">Neighborhoods</h2>
                    <div class="row">
                        <div class="district_hoods_slider">
                        <?php if(!empty($hoods)){ foreach($hoods as $hood){ ?>

                            <div class="col-sm-4" style='margin-bottom:20px;'>
                                <a href="{{ url('/neighbour/'.$hood['id'])  }}">
                                    <div class="img-gallry" style="background-image: url('{{ asset('/storage/district/'.$hood['image']) }}')">
                                        <img src="{{ asset('/storage/district/'.$hood['image']) }}" height='350px' alt=""/>
                                        <div class="overlay"><h3><?php echo $hood['hood'];?></h3></div>
                                    </div>
                                </a>
                            </div>

                        <?php } } ?>
                    </div>
                </section>
            </div>
        </div>
        @include('short_contactus')

        <script type="text/javascript">
            $(window).scroll(function () {
                if ($(this).scrollTop() > 60) {
                    $('.navbar-fixed-top').addClass("logo-fix-adon");
                }
                else {
                    $('.navbar-fixed-top').removeClass("logo-fix-adon");
                }
            });
        </script>

        <script type="text/javascript">

            $(document).ready(function(){
                $(".district_hoods_slider").slick({
                    autoplay: true,
                    infinite: true,
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    dots: false,
                });

                $('.photos-bx').slick({
                    dots: true,
                    infinite: false,
                    speed: 300,
                    infinite: true,
                    slidesToShow: 3,
                    slidesToScroll: 3,
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
            });
        </script>
    </main>
@endsection