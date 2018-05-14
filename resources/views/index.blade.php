@extends('layouts.app')
@section('title', 'Loquare | Home')
@section('content')
    <main>
        <div class="pageloader"></div>
        <div class="banner home_pg">
            <div class="container abslte">
                <div class="row">
                    <div class="home-set">
                        <h1>Find the perfect place to buy or rent <br/>using high-tech data</h1>
                        <div class="box-up-brd">
                            <ul class="circle-radio select-type">
                                <li class="type grn-act" data-value="RENT"><span></span> Rent</li>
                                <li class="type" data-value="SALE"><span></span> Buy</li>
                            </ul>
                            <div class="media vertic-mdl  sbmt-chat height-60">
                                <div class="media-body">
                                    <input type="text" class="form-control" placeholder="City area or street address" id="mapbox_search">
                                    <div rel="searchbox-list" class="search-list hidden" id="searchresults">
                                        <ul id="searchlist"></ul>
                                    </div>
                                </div>
                                <div class="media-right">
                                    <div class="agents">
                                        <input type="submit" class="grn-full-btn" value="Find" id="search_property">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end of home-content-->
            </div>
            <a href="#perfect-hm" class="pos-botm">
                <p>Learn more</p>
                <img src="{{ asset('/frontend/images/frontend/mouse-img.png') }}" alt=""/> </a></div>
        <!--end of banner-->
        <section id="perfect-hm">
            <div class="container">
                <div class="row">
                    <div class="perfect-home text-center">
                        <h1 class="dark-hd-clr">Use the latest real estate technologies <br/>
                            to find your
                            perfect home.</h1>
                        <p>Find the perfect match with the most complete data source of neighborhoods<br/>
                            and homes for sale and for rent.</p>
                        <div class="col-sm-4">
                            <div class="set-three-col"><img src="{{ asset('/frontend/images/frontend/home-page-icons.png') }}" alt=""/>
                                <h2 class="dark-hd-clr">Save Money</h2>
                                <p>Save on agent's fees. Our platform helps you communicate to the
                                    seller/buyer/renter for free.</p>
                            </div>
                        </div>
                        <!--end of sec-->

                        <div class="col-sm-4">
                            <div class="set-three-col"><img src="{{ asset('/frontend/images/frontend/home-page-icons_time.png') }}" alt=""/>
                                <h2 class="dark-hd-clr">Save Time</h2>
                                <p>No more going back and forth. Set up visits, negotiate, submits your documents
                                    online and save days and weeks of your time.</p>
                            </div>
                        </div>
                        <!--end of sec-->

                        <div class="col-sm-4">
                            <div class="set-three-col"><img src="{{ asset('/frontend/images/frontend/home-page-icons_smart-data.png') }}" alt=""/>
                                <h2 class="dark-hd-clr">Use smart data</h2>
                                <p>At your service. Verified, insightful and high quality data on apartments ,
                                    neighborhoods and situations on the market.</p>
                            </div>
                        </div>
                        <!--end of sec-->

                        <div class="clearfix"></div>
                        <div class="buttons gren-btn text-center mt-top-btn-30"><a href="{{url('property/add')}}" class="btn-round">Sign up</a></div>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-gray-sec compare_block">
            <div class="container">
                <div class="row">
                    <div class="perfect-home text-center">
                        <h1 class="dark-hd-clr">How our clients benefit from using loquare <br>
                            (compared to Real Estate Agents)</h1>
                        <div class="over-fl-x">
                            <div class="uper-table">
                                <div class="table-responsive tabl-width">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <th></th>
                                            <th class="text-head snd-col">Loquare</th>
                                            <th class="text-head thrd-col">Real Estate Agent</th>
                                        </tr>
                                        <tr>
                                            <td>Listing</td>
                                            <td class="snd-col"> High-quality
                                                consistent listing
                                                with contextual
                                                data, neighborhood
                                                analysis and
                                                detailed
                                                descriptions.
                                            </td>
                                            <td class="thrd-col"> Basic information, lack of <br/>
                                                pictures and details
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> Information,<br>
                                                property reports
                                            </td>
                                            <td class="snd-col">Online, via instant chat,<br>
                                                over the phone
                                            </td>
                                            <td class="thrd-col">Contact the agent</td>
                                        </tr>
                                        <tr>
                                            <td>Pricing</td>
                                            <td class="snd-col">Trusted advice, verified data,<br>
                                                precise estimation based on <br>
                                                varified algorithms
                                            </td>
                                            <td class="thrd-col">Agent's perception, no rules.</td>
                                        </tr>
                                        <tr>
                                            <td>Tour & Showings</td>
                                            <td class="snd-col">Instant set up, you are in control</td>
                                            <td class="thrd-col">Agent is in control</td>
                                        </tr>
                                        <tr>
                                            <td>Negotiation</td>
                                            <td class="snd-col">You are in control</td>
                                            <td class="thrd-col">Agent is in control</td>
                                        </tr>
                                        <tr>
                                            <td>Your refund selling</td>
                                            <td class="snd-col">More than 1.5%, no prepayment</td>
                                            <td class="thrd-col">0</td>
                                        </tr>
                                        <tr>
                                            <td>Your refund renting</td>
                                            <td class="snd-col">More than 50%</td>
                                            <td class="thrd-col">Full Price</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="padd-bot-20 ">
            <div class="container">
                <div class="perfect-home">
                    <div class="row">
                        <div class="text-center">
                            <h1 class="dark-hd-clr">How it works</h1>
                            <h6 class="bold-txt">Selling/Renting a place has never been easier</h6>
                        </div>
                        <div class="box-up-brd pad-10 no-border">
                            <div class="midl-box"><span>If you are a</span>
                                <ul class="circle-radio">
                                    <li class="grn-act_nw"><span></span>Owner</li>
                                    <li class=""><span></span>Buyer</li>
                                </ul>
                            </div>
                        </div>
                        <!--end of select option-->
                        <div class="box-up-brd no-border">
                            <div class="buyers mx-width-150">
                                <div>
                                    <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon1.png') }}" alt=""/>
                                        <div class="pos-abst">
                                            <p>Submit some photos and data about your home</p>
                                        </div>
                                    </div>
                                    <!--end of first set-->

                                    <div class="img-box left-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon2.png') }}" alt=""/>
                                        <div class="pos-abst">
                                            <p>Get multiple offers from online users</p>
                                        </div>
                                    </div>
                                    <!--end of second set-->
                                    <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon3.png') }}" alt=""/>
                                        <div class="pos-abst">
                                            <p>Connected with the trusted buyer/tenant Loquare specialist will send you only confirmed and trusted offers profile documentation reviewed and
                                                buyer tenant/profile confirmed.</p>
                                        </div>
                                    </div>
                                    <!--end of third set-->

                                    <div class="img-box left-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon4.png') }}" alt=""/>
                                        <div class="pos-abst">
                                            <p>Agree condition and price offer and a schedule a day to sign up</p>
                                        </div>
                                    </div>
                                    <!--end of fourth set-->
                                    <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon5.png') }}" alt=""/>
                                        <div class="pos-abst">
                                            <p>Seal the deal and receive your money!!</p>
                                        </div>
                                    </div>
                                    <!--end of fifth set-->
                                </div>
                            </div>
                            <div class="buttons gren-btn text-center mt-top-btn-30"><a href="{{url('property/add')}}" class="btn-round">Sign up</a></div>
                            <!--end of buyers-->

                            <div class="renters mx-width-150">
                                <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon1.png') }}" alt=""/>
                                    <div class="pos-abst">
                                        <p>Submit some photos and data about your home</p>
                                    </div>
                                </div>
                                <!--end of first set-->

                                <div class="img-box left-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon2.png') }}" alt=""/>
                                    <div class="pos-abst">
                                        <p>Get multiple offers from multiple users</p>
                                    </div>
                                </div>
                                <!--end of second set-->
                                <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon3.png') }}" alt=""/>
                                    <div class="pos-abst">
                                        <p>connected with the trusted buyer/tenant loquare specialist will send you only confirmed and trusted offers Profile documentation reviewed and buyer
                                            tenant/profile confirmed.</p>
                                    </div>
                                </div>
                                <!--end of third set-->

                                <div class="img-box left-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon4.png') }}" alt=""/>
                                    <div class="pos-abst">
                                        <p>Agree condition and price offer and a schedule a day to sign up</p>
                                    </div>
                                </div>
                                <!--end of fourth set-->
                                <div class="img-box right-set"><img src="{{ asset('/frontend/images/frontend/how-it-owners-icon5.png') }}" alt=""/>
                                    <div class="pos-abst">
                                        <p>Seal the deal and receive your money!!</p>
                                    </div>
                                </div>
                                <!--end of third set-->
                                <div class="buttons gren-btn text-center mt-top-btn-30"><a href="{{url('property/add')}}" class="btn-round">Sign up</a></div>
                            </div>
                            <!--end of seller-->

                        </div>
                    </div>
                </div>
        </section>
        <!--end of how it works-->
        <section class="text-center cheper-sec">
            <div class="container">
                <div class="perfect-home">
                    <div class="row">
                        <h1 class="dark-hd-clr">Why is it cheaper?</h1>
                        <h6 class="bold-txt">By cutting of the middle man and using
                            automated algorithms,<br/>
                            we save our client's
                            money: </h6>
                        <div class="mid-sec">
                            <div class="col-xs-6 bg-crcle">
                                <div class="in-box">
                                    <h3 class="dark-hd-clr">Selling</h3>
                                    <p>The industry standard commission rate is 3% to 6%. Loquare will refund you more than 50% of a real estate agent's commission. </p>
                                    <p class="bold-txt"> You will receive a minimum refund of 1.5% of the deal price. </p>
                                </div>
                            </div>
                            <!--end of circle-->
                            <div class="col-xs-6 bg-crcle">
                                <div class="in-box">
                                    <h3 class="dark-hd-clr">Renting</h3>
                                    <p>The standard commission rate in the<br/>
                                        real estate industry: 1 month rent.<br/>
                                        Loquare will refund more than<br/>
                                        50% of this amount.</p>
                                    <p class="bold-txt"> Minimum refund:<br/>
                                        50% of the commission. </p>
                                </div>
                            </div>
                            <!--end of circle 2-->
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end of cheaper section-->
        <section class="text-center about-sec">
            <div class="container">
                <div class="perfect-home">
                    <div class="row">
                        <h1 class="dark-hd-clr">About Loquare</h1>
                        <p class="bold-txt">A real estate tech firm that revolutionizes the industry.<br/>
                            we make the home searching/buying/renting<br/> process seamless </p>
                        <div class="mid-sec text-center mt-top-30">
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon.png') }}" alt=""/>
                                    <div class="txt-box"> Providing a new vision<br/>
                                        of space (location)
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->

                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_1.png') }}" alt=""/>
                                    <div class="txt-box"> Access to <br/>
                                        contextual data
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->

                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_2.png') }}" alt=""/>
                                    <div class="txt-box"> Location <br/>
                                        analytics
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->

                            <div class="clearfix"></div>
                            <!--end of first call-->
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_3.png') }}" alt=""/>
                                    <div class="txt-box"> Extensive<br/>
                                        neighborhood analytics
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_4.png') }}" alt=""/>
                                    <div class="txt-box"> Market <br/>
                                        Analysis
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_5.png') }}" alt=""/>
                                    <div class="txt-box"> Not a physical <br/>
                                        agent
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="clearfix"></div>
                            <!--end of first call-->

                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_6.png') }}" alt=""/>
                                    <div class="txt-box"> User has the full control<br/>
                                        over the process
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_7.png') }}" alt=""/>
                                    <div class="txt-box"> Helping clients <br/>
                                        recieve their refunds
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="col-xs-4">
                                <div class="icon-box"><img src="{{ asset('/frontend/images/frontend/about-loqure-icon_8.png') }}" alt=""/>
                                    <div class="txt-box"> Only meaningful <br/>
                                        and trusted data
                                    </div>
                                </div>
                            </div>
                            <!--end of icon box-->
                            <div class="clearfix"></div>
                            <!--end of first call-->

                            <div class="buttons gren-btn text-center mt-top-btn-30"><a href="{{url('property/add')}}" class="btn-round  pad-more">Sign up</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="padd-bot-0"><img src="{{ asset('/frontend/images/frontend/bg-laher_2.png') }}" alt="" class="img-wd-100"/></section>
        <section class="full-section">
            <div class="container-fluid">
                <div class="row">
                    <h2 class="iner-head text-center mid-head-top">Choose your favorite area in Barcelona</h2>
                    <div class="home_area">

                        <?php if($areas != false){ foreach($areas as $area) { ?>
                        <div class="col-sm-6"><a href="{{url('/state/'.$area['id'])}}">
                                <div class="img-gallry" style="background-image: url('{{ asset('/frontend/images/frontend/'.$area['image']) }}')">
                                    <img src="{{ asset('/frontend/images/frontend/'.$area['image']) }}" alt=""/>
                                    <div class="overlay">
                                        <h3><?php echo $area['dist_name']; ?></h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } } ?>

                    </div>
                    <!--end of col-->
                </div>
                <div class="buttons gren-btn text-center mt-top-btn-30"><a href="{{url('/area')}}" class="btn-round  pad-more">Browse city areas</a></div>
            </div>
        </section>
        <section class="contact-bg">
            <div class="container">
                <div class=" perfect-home">
                    <h3 class="text-head text-center clr-white font-40">Got question? Contact us today:</h3>
                    <form class="form" name="contact_us_form" id="contact_us_form" action="{{url('/contact_us')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input class="form-control" placeholder="John smith" type="text" name="contact_name" id="contact_name">
                                </div>
                            </div>

                            <!--end of col-->
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input class="form-control" placeholder="Example@gmail.com" type="email" name="contact_email" id="contact_email">
                                </div>
                            </div>
                            <!--end of col-->

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input class="form-control" placeholder="+11-111-111-1111" type="tel" name="contact_phone" id="contact_phone">
                                </div>
                            </div>
                            <!--end of col-->
                            <div class="clearfix"></div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea class="form-control" placeholder="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the" name="contact_message" id="contact_message"></textarea>
                                </div>
                            </div>
                            <!--end of col-->
                            <div class="clearfix"></div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="buttons gren-btn text-center">
                                        <button class="btn-round pad-more" type="button" id="request_contact">Send Message</button>
                                        <div class="icon-imgs"><img src="{{ asset('/frontend/images/frontend/map-icon.png') }}" alt=""/></div>
                                    </div>
                                </div>
                            </div>

                            <!--end of col-->
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

@endsection
