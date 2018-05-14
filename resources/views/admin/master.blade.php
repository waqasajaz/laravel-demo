<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('labels.appname')}}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <script type="text/javascript">
            var basepath = "{{ url('/')  }}";
        </script>


        <!-- Data Table css -->

        <link rel="stylesheet" href="{{ asset('backend/plugins/datatables/jquery.dataTables.min.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/plugins/datatables/dataTables.bootstrap.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css')}}">

        <link rel="stylesheet" href="{{ asset('backend/plugins/datatables/editor.dataTables.min.css')}}">

        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/bootstrap.css')}}">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('backend/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/loader.css')}}">
        <!-- Ionicons -->
        <!--<link rel="stylesheet" href="{{ asset('css/ionicons.min.css')}}">-->
        <!-- <link rel="stylesheet" href="{{ asset('plugins/datepicker/bootstrap-datetimepicker.css')}}"> -->


        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('css/jquery-confirm.min.css')}}">

        <link rel="stylesheet" href="{{ asset('backend/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/skins/_all-skins.min.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/jquery-ui.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/custom-admin.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/custom.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/css/jquery.tag-editor.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker-bs3.css')}}">
        <link rel="stylesheet" href="{{ asset('backend/plugins/datepicker/datepicker3.css')}}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" />
        <script src="{{ asset('backend/js/jquery.min.js') }}"></script>

        @yield('header')
    </head>
    @if (Auth::guard('admin')->check())
    <body class="hold-transition skin-purple-light sidebar-mini">
        @else
    <body class="hold-transition login-page">
        @endif
        {{csrf_field()}}
        <div id="gif">
            <div class="gifInner">
                <div class="gifImg">
                    <i class="fa fa-refresh fa-spin" aria-hidden="true"></i>
                </div>
            </div>
        </div>
        <div class="wrapper">
            @if (Auth::guard('admin')->check())
            <header class="main-header">
                <!-- Logo -->
                <a href="{{ url('/admin')}}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>{{trans('labels.appshortname')}}</b></span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>{{trans('labels.appname')}}</b></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">{{trans('labels.togglenav')}}</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">

                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ asset('backend/images/avatar5.png')}}" class="user-image" alt="User Image">                             <span class="hidden-xs">{{Auth::guard('admin')->user()->name}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{ asset('backend/images/avatar5.png')}}" class="img-circle" alt="User Image">
                                        <p>
                                            {{Auth::guard('admin')->user()->name}}
                                        </p>
                                    </li>

                                    <li class="user-footer">
                                        <div style="">
                                            <a href="{{ url('/admin/settings')}}" class="btn bg-purple btn-flat pull-left"><i class="fa fa-gears"></i> Settings</a>
                                            <a href="{{ url('/admin/logout')}}" class="btn bg-purple btn-flat pull-right">{{trans('labels.logout')}}</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{ asset('backend/images/avatar5.png')}}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>{{Auth::guard('admin')->user()->name}}</p>
                            <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i>{{trans('labels.online')}}</a>
                          </div>
                    </div>
                    <?php $newOffers = Loquare::getNewOffers();?>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="<?php if (Route::current()->uri() == 'admin/dashboard') {echo 'active';}?> treeview">
                            <a href="{{ url('admin/dashboard') }}">
                                <small class="label pull-right bg-red" id="offers_inreview">{{(isset($newOffers['offers_inreview']) && $newOffers['offers_inreview'] > 0)?$newOffers['offers_inreview'] . " new":""}}</small>
                                <i class="fa fa-dashboard"></i> <span>{{trans('labels.dashboard')}}</span>
                            </a>
                        </li>

                        @if(Auth::guard('admin')->user()->role->type == 'admin')
                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets', 'admin/assets/{type}', 'admin/agent'])) {echo 'active';}?> treeview">
                            <?php $assets = Loquare::getNewAssetsCount(); ?>
                            <a href="{{ url('admin/agent') }}">
                                <i class="fa fa-users"></i> <span>Agents</span>
                                <small class="label pull-right bg-red">{{(isset($assets['total']) && $assets['total'] > 0)?$assets['total']:""}}</small>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-down pull-right"></i>
                                </span>
                            </a>

                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets', 'admin/assets/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/assets/rent') }}"><i class="fa fa-circle-o"></i> Renting <small class="label pull-right bg-red">{{(isset($assets['rent']) && $assets['rent'] > 0)?$assets['rent']:""}}</small></a></li>
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets', 'admin/assets/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/assets/sale') }}"><i class="fa fa-circle-o"></i> Selling <small class="label pull-right bg-red">{{(isset($assets['sale']) && $assets['sale'] > 0)?$assets['sale']:""}}</small></a></li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::guard('admin')->user()->role->type == 'admin')
                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/published', 'admin/assets/published/{type}', 'admin/assets/unpublished', 'admin/assets/unpublished/{type}'])) {echo 'active menu-open';}?> treeview">
                            <a href="http://localhost/loquaregit/admin/assets">
                                <i class="fa fa-building"></i> <span>Single Assets</span>
                                <span class="pull-right-container">
                              <i class="fa fa-angle-down pull-right"></i>
                            </span>
                            </a>
                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/published', 'admin/assets/published/{type}'])) {echo 'active menu-open';}?> treeview">
                                    <?php $published_assets = Loquare::getNewPublishedAssetsCount(); ?>
                                    <a href="{{ url('admin/assets') }}">
                                        <span>Published Assets</span>
                                        <small class="label pull-right bg-red">{{(isset($published_assets['total']) && $published_assets['total'] > 0)?$published_assets['total']:""}}</small>
                                        <span class="pull-right-container">
                                          <i class="fa fa-angle-down pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="">
                                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/published', 'admin/assets/published/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/assets/published/rent') }}"><i class="fa fa-circle-o"></i> Renting <small class="label pull-right bg-red">{{(isset($published_assets['rent']) && $published_assets['rent'] > 0)?$published_assets['rent']:""}}</small></a></li>
                                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/published', 'admin/assets/published/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/assets/published/sale') }}"><i class="fa fa-circle-o"></i> Selling <small class="label pull-right bg-red">{{(isset($published_assets['sale']) && $published_assets['sale'] > 0)?$published_assets['sale']:""}}</small></a></li>
                                    </ul>
                                </li>

                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/unpublished', 'admin/assets/unpublished/{type}'])) {echo 'active menu-open';}?> treeview">
                                    <?php $published_assets = Loquare::getNewUnpublishedAssetsCount(); ?>
                                    <a href="{{ url('admin/assets') }}">
                                        <span>UnPublished Assets</span>
                                        <small class="label pull-right bg-red">{{(isset($published_assets['total']) && $published_assets['total'] > 0)?$published_assets['total']:""}}</small>
                                        <span class="pull-right-container">
                                          <i class="fa fa-angle-down pull-right"></i>
                                        </span>
                                    </a>
                                    <ul class="treeview-menu" style="">
                                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/unpublished', 'admin/assets/unpublished/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/assets/unpublished/rent') }}"><i class="fa fa-circle-o"></i> Renting <small class="label pull-right bg-red">{{(isset($published_assets['rent']) && $published_assets['rent'] > 0)?$published_assets['rent']:""}}</small></a></li>
                                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/assets/unpublished', 'admin/assets/unpublished/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/assets/unpublished/sale') }}"><i class="fa fa-circle-o"></i> Selling <small class="label pull-right bg-red">{{(isset($published_assets['sale']) && $published_assets['sale'] > 0)?$published_assets['sale']:""}}</small></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(Auth::guard('admin')->user()->role->type == 'agent')
                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/my-assets', 'admin/my-assets/{type}'])) {echo 'active';}?> treeview">
                            <?php $myassets = Loquare::getNewMyAssetsCount(); ?>
                            <a href="{{ url('admin/my-assets') }}">
                                <i class="fa fa-building"></i> <span>My Assets</span>
                                <small class="label pull-right bg-red">{{(isset($myassets['total']) && $myassets['total'] > 0)?$myassets['total']:""}}</small>
                                <span class="pull-right-container">
                                  <i class="fa fa-angle-down pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/my-assets', 'admin/my-assets/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/my-assets/rent') }}"><i class="fa fa-circle-o"></i> Renting <small class="label pull-right bg-red">{{(isset($myassets['rent']) && $myassets['rent'] > 0)?$myassets['rent']:""}}</small></a></li>
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/my-assets', 'admin/my-assets/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/my-assets/sale') }}"><i class="fa fa-circle-o"></i> Selling <small class="label pull-right bg-red">{{(isset($myassets['sale']) && $myassets['sale'] > 0)?$myassets['sale']:""}}</small></a></li>
                            </ul>
                        </li>
                        @endif

                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-completed', 'admin/offers-completed/{type}'])) {echo 'active';}?> treeview">
                            <a href="{{ url('admin/offers-completed') }}">
                                <i class="fa fa-battery-4"></i> <span>Offers Completed</span>
                                <small class="label pull-right bg-red" id="offers_completed">{{(isset($newOffers['offers_completed']) && $newOffers['offers_completed'] > 0)?$newOffers['offers_completed'] . " new":""}}</small>
                                <span class="pull-right-container">
                                  <i class="fa fa-angle-down pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-completed', 'admin/offers-completed/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/offers-completed/rent') }}"><i class="fa fa-circle-o"></i> Renting <small class="label pull-right bg-red" id="offers_completed_rent">{{(isset($newOffers['offers_completed_rent']) && $newOffers['offers_completed_rent'] > 0)?$newOffers['offers_completed_rent'] . " new":""}}</small></a></li>
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-completed', 'admin/offers-completed/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/offers-completed/sale') }}"><i class="fa fa-circle-o"></i> Selling <small class="label pull-right bg-red" id="offers_completed_sale">{{(isset($newOffers['offers_completed_sale']) && $newOffers['offers_completed_sale'] > 0)?$newOffers['offers_completed_sale'] . " new":""}}</small></a></li>
                            </ul>
                        </li>

                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-accepted', 'admin/offers-accepted/{type}'])) {echo 'active';}?> treeview">
                            <a href="{{ url('admin/offers-accepted') }}">
                                <i class="fa fa-check"></i> <span>Offers Accepted</span>
                                <span class="pull-right-container">
                                  <i class="fa fa-angle-down pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-accepted', 'admin/offers-accepted/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('admin/offers-accepted/rent') }}"><i class="fa fa-circle-o"></i> Renting</a></li>
                                <li class="<?php if (in_array(Route::current()->uri(), ['admin/offers-accepted', 'admin/offers-accepted/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('admin/offers-accepted/sale') }}"><i class="fa fa-circle-o"></i> Selling</a></li>
                            </ul>
                        </li>
						<li class="<?php if (in_array(Route::current()->uri(), ['admin/companies'])) {echo 'active';}?>">
                            <a href="{{ url('admin/companies') }}">
                                <i class="fa fa-industry"></i> <span>Company Assets</span>
                            </a>
                        </li>
                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/tour/no'])) {echo 'active';}?>">
                            <a href="{{ url('admin/tour/no') }}">
                                <i class="fa fa-industry"></i> <span> Listing Tour Requested</span>
                            </a>
                        </li> <li class="<?php if (in_array(Route::current()->uri(), ['admin/tour/yes'])) {echo 'active';}?>">
                            <a href="{{ url('admin/tour/yes') }}">
                                <i class="fa fa-industry"></i> <span>  Listing Asset Visited</span>
                            </a>
                        </li>
                        <li class="<?php if (in_array(Route::current()->uri(), ['assets/sold-assets', 'assets/sold-assets/{type}'])) {echo 'active';}?> treeview">
                            <a href="{{ url('admin/offers-accepted') }}">
                                <i class="fa fa-check-circle"></i> <span>Sold assets</span>
                                <span class="pull-right-container">
                                  <i class="fa fa-angle-down pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" style="">
                                <li class="<?php if (in_array(Route::current()->uri(), ['assets/sold-assets/{type}']) && isset($type) && $type == 'rent') {echo 'active';}?>"><a href="{{ url('assets/sold-assets/rent') }}"><i class="fa fa-circle-o"></i> Renting</a></li>
                                <li class="<?php if (in_array(Route::current()->uri(), ['assets/sold-assets/{type}']) && isset($type) && $type == 'sale') {echo 'active';}?>"><a href="{{ url('assets/sold-assets/sale') }}"><i class="fa fa-circle-o"></i> Selling</a></li>
                            </ul>
                        </li>
                        <li class="<?php if (in_array(Route::current()->uri(), ['admin/logs'])) {echo 'active';}?>">
                            <a href="{{ url('admin/logs') }}">
                                <i class="fa fa-list-o"></i> <span>Logs</span>
                            </a>
                        </li>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            @endif

            @if (Auth::guard('admin')->check())
            <div class="content-wrapper">

                @if ($message = Session::get('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                                <h4><i class="icon fa fa-check"></i> {{trans('validation.successlbl')}}</h4>
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if ($message = Session::get('error'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="alert alert-error alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">X</button>
                                {{ $message }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @yield('content')
            </div><!-- /.content-wrapper -->
            @else
            @yield('content')
            @endif

            @if (Auth::guard('admin')->check())

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    {!! trans('labels.version') !!}
                </div>
                {!! trans('labels.copyrightstr') !!}
            </footer>
            @endif
            @yield('footer')
        </div>

        <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.js')}}"></script>
        <script src="{{ asset('backend/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js')}}"></script>
        <script src="{{ asset('backend/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>


        <script src="{{ asset('backend/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('backend/js/dataTables.select.min.js') }}"></script>
        <script src="{{ asset('backend/js/dataTables.editor.min.js') }}"></script>

        <script src="{{ asset('backend/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/jquery-confirm.min.js')}}"></script>
        
         <!-- Bootstrap 3.3.5 -->
        <script src="{{ asset('backend/js/bootstrap.min.js')}}"></script>
        <!-- SlimScroll -->
        <script src="{{ asset('backend/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
        <!-- FastClick -->
        <script src="{{ asset('backend/plugins/fastclick/fastclick.min.js')}}"></script>
        <!-- backendLTE App -->
        <script src="{{ asset('backend/js/app.min.js')}}"></script>
        <script src="{{ asset('backend/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('backend/js/additional-methods.js') }}"></script>

        <script src="{{ asset('backend/plugins/daterangepicker/moment.js')}}"></script>
        <script src="{{ asset('backend/plugins/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{ asset('backend/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
        <script>

            $(window).load(function() {
                $('#gif').hide();

            });

            setInterval(notifyForNewOffers, 5000);

            function notifyForNewOffers() {
                $.ajax({
                    url: "{{ url('/admin/new/offers') }}",
                    type: 'post',
                    async: false,
                    data: {
                        "_token": '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if(response.offers_inreview > 0){$('#offers_inreview').html(response.offers_inreview + " new");}
                        if(response.offers_completed > 0){$('#offers_completed').html(response.offers_completed + " new");}
                        if(response.offers_completed_rent > 0){$('#offers_completed_rent').html(response.offers_completed_rent + " new");}
                        if(response.offers_completed_sale > 0){$('#offers_completed_sale').html(response.offers_completed_sale + " new");}
                    }
                });
            }
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="{{ asset('backend/js/functions.js') }}"></script>
        @yield('script')
        <?php if(isset($scripts)){ foreach($scripts as $script)?>
            <script src="<?php echo $script; ?>" type="text/javascript"></script>
        <?php } ?>

    </body>
</html>
