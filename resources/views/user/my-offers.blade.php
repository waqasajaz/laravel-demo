@extends('layouts.app')
@section('title', 'My Offers - '.Auth::user()->name)
@section('content')

    <main>
        <div class="page">
            <div class="container">
                <div class="page__title">My Offers</div>
                <div class="page-top">
                    <div class="page-top__left">&nbsp;</div>
                    <div class="page-top__right">
                        <div class="filter-big">
                            <div class="filter-big__title">Filter</div>
                            <div class="filter-big__items">
                                <a href="javascript:void(0)" class="filter-big__item filter_for active" data-type="">
                                    <span>all</span>
                                </a>
                                <a href="javascript:void(0)" class="filter-big__item filter_for" data-type="RENT">
                                    <span>rent</span>
                                </a>
                                <a href="javascript:void(0)" class="filter-big__item filter_for" data-type="SALE">
                                    <span>sale</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-offers-block" style="margin-top: 15px;">
                    <div class='my-offers-offers'>
                        <div class="my_properties_list" id='my_offers'></div>
                        <div class='load_more hidden'>
                            <buttn class="btn btn-primary btn-sm ">Load More</buttn>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="delete-offer" class="mfp-hide popup popup--400">
            <div class="popup__inner">
                <div class="popup__subtitle">
                    Do you really want to <br> cancel Offer for <br> “<span></span>” ?
                </div>
                <div class="popup__btns">
                    <button type="button" class="popup__ok-btn">yes, cancel this offer</button>
                    <button type="button" class="popup__cancel-btn">no, leave this offer</button>
                </div>
            </div>
        </div>

        @include('contactus')
    </main>
@endsection