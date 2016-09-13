<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="icon" type="image/x-icon" href="{{ Config::get('cms.favicon') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{!! Config::get('cms.name') !!}</title>

        <meta name="description" content="">
        <meta name="author" content="{{ Config::get('woburn.designer') }}">
        <!-- Base Styling  -->
        <link href="{{ asset('css/app.v1.css') }}" rel="stylesheet">
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

        <!-- Custom Font -->
        <link rel="stylesheet" href="{{ asset('css/font.css') }}">

        <!-- Custom Styling -->
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/buttons.bootstrap4.min.css') }}">
        <!-- Awesome Bootstrap checkbox -->
        <link rel="stylesheet" href="{{ asset('css/awesome-bootstrap-checkbox.css') }}">
        <style type="text/css">
        /* CSS used here will be applied after bootstrap.css */
            .picha {
                background-color: white;
            }
        </style>
        <!-- Datepicker -->
        <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.css') }}" />
    </head>
    <body>
        <!-- Preloader -->
        <div class="loading-container">
            <div class="loading">
                <div class="l1">
                    <div></div>
                </div>
                <div class="l2">
                    <div></div>
                </div>
                <div class="l3">
                    <div></div>
                </div>
                <div class="l4">
                    <div></div>
                </div>
            </div>
        </div>
        <!-- Preloader -->

        <aside class="left-panel">

            <div class="user text-xs-center">
                <img src="{{ (count(Request::segments())>1)?'../../'.Config::get('cms.logo'):Config::get('cms.logo') }}" class="img-circle picha" alt="...">
                <h4 class="user-name text-warning">{!! Config::get('cms.name') !!}</h4>
            </div>
            <nav class="navigation">
                <ul class="list-unstyled">
                    <li class="{!! Request::segment(1)==strtolower(trans('messages.home'))?strtolower(trans('messages.active')):'' !!}">
                        <a href="#"><i class="fa fa-dashboard"></i> {!! trans('messages.dashboard') !!}</a>
                    </li>
                    <li class="has-submenu{!! in_array(Request::segment(1), [strtolower('pt'), strtolower('program'), strtolower('material'), strtolower('round'), strtolower('item'), strtolower('expected'), strtolower('shipment'), strtolower('receipt'), strtolower('result')])?' '.strtolower(trans('messages.active')):'' !!}">
                        <a href="#"><i class="fa fa-graduation-cap"></i> {!! trans('messages.pt') !!}</a>
                        <ul class="list-unstyled">
                            <li class="{!! Request::segment(1)==strtolower('program')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('program') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.program', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('material')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('material') !!}"><i class="fa fa-bookmark"></i> {!! trans('messages.sample-preparation') !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('round')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('round') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.pt-round', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('item')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('item') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.pt-item', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('expected')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('expected') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.expected-result', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('shipment')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('shipment') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.shipment', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('receipt')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('receipt') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.receipt', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('result')?strtolower(trans('messages.active')):'' !!}">
                                <a href="#"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.result', 2) !!}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="has-submenu{!! in_array(Request::segment(1), [strtolower('field'), strtolower('option')])?' '.strtolower(trans('messages.active')):'' !!}">
                        <a href="#"><i class="fa fa-google-wallet"></i> {!! trans('messages.program-management') !!}</a>
                        <ul class="list-unstyled">
                            <li class="{!! Request::segment(1)==strtolower('field')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('field') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.field', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('response')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('option') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.option', 2) !!}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{!! Request::segment(1)==strtolower('facility')?strtolower(trans('messages.active')):'' !!}">
                        <a href="{!! url('facility') !!}"><i class="fa fa-building"></i> {!! trans('messages.facility-catalog') !!}</a>
                    </li>
                    <li class="has-submenu{!! in_array(Request::segment(1), [strtolower('user'), strtolower('role'), strtolower('permission'), strtolower('assign')])?' '.strtolower(trans('messages.active')):'' !!}">
                        <a href="#"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</a>
                        <ul class="list-unstyled">
                            <li class="{!! Request::segment(1)==strtolower('user')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('user') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.user', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('role')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('role') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.role', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('permission')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('permission') !!}"><i class="fa fa-bookmark"></i> {!! trans_choice('messages.permission', 2) !!}</a>
                            </li>
                            <li class="{!! Request::segment(1)==strtolower('assign')?strtolower(trans('messages.active')):'' !!}">
                                <a href="{!! url('assign') !!}"><i class="fa fa-bookmark"></i> {!! trans('messages.assign-roles') !!}</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <section class="content">
            <header class="top-head container-fluid">
                <nav class="navbar-default" role="navigation">
                    <div class="collapse navbar-toggleable-xs" id="collapsingNavbar">
                        <ul class="nav navbar-nav pull-right">
                            <li class="nav-item active">
                                <a class="nav-link text-primary" href="#">Home <span class="sr-only">Home</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#features">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#myAlert" data-toggle="collapse">Wow</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="" data-target="#myModal" data-toggle="modal">About</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            <!-- Header Ends -->
            </header>

            <div class="warper container-fluid">
                @yield('content')
            </div>
            <!-- Warper Ends Here (working area) --><hr>
            <div id="footer" class="">
                <div class="container-fluid">
                    <p class="text-muted gem-h7">
                        <strong>
                            &copy; {!! date('Y').' '.Config::get('cms.name') !!}
                            <span style="float:right">
                                Designed by {!! Config::get('cms.designer') !!}&nbsp;&nbsp;&nbsp;
                                <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
                            </span>
                        </strong>
                    </p>
                </div>
            </div>
        </section>
        <!-- JQuery v1.9.1 -->
        <script src="{{ asset('js/jquery-1.12.3.min.js') }}"></script>
        <script src="{{ asset('js/underscore-min.js') }}"></script>
        <!-- Bootstrap -->
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <!-- Datatables -->
        <script type="text/javascript" src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('js/datatables/dataTables.buttons.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/jszip.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/pdfmake.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/vfs_fonts.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/buttons.html5.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/buttons.print.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datatables/buttons.colVis.min.js') }}"></script>
        <!-- Datepicker -->
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

        <!-- Globalize -->
        <script src="{{ asset('js/globalize.min.js') }}"></script>

        <!-- NanoScroll -->
        <script src="{{ asset('js/jquery.nicescroll.min.js') }}"></script>
        <!-- Custom JQuery -->
        <script src="{{ asset('js/custom.js') }}"></script>
        <!-- search table for datatables -->
        <script src="{{ asset('js/harmony/custom.js') }}"></script>
        <!-- Datepicker -->
        <script src="{{ asset('js/harmony/bootstrap-datepicker.js') }}"></script>
    </body>
</html>
