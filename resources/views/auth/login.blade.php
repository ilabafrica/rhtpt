<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="{{ Config::get('cms.favicon') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>{!! Config::get('cms.name') !!}</title>

    <!-- Font awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom Font -->
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <!-- Custom Styling -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Sweet Alert Styling -->
    <link href="{{ asset('css/sweetalert.css') }}" rel="stylesheet">
    
    
</head>

<body>
    <div class="login-page" style="padding-top:20px;">
        <div class="card col-md-5" style="margin:auto; float:none">
            <div class="card-block">
                <div class="row" style="padding:20px">
                    <div class="col-md-12  text-md-center">
                        <img src="{{ '../'.Config::get('cms.logo') }}" height="75px">
                        <h4 class="text-primary">{!! Config::get('cms.name') !!}</h4>
                    </div>
                </div>
                <form class="mt" role="form" method="POST" action="{{ route('login') }}">
                    <!-- CSRF Token -->
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!-- ./ csrf token -->
                    @if($errors)
                        @if (count($errors) > 0)
                        <div class="alert alert-danger col-md-12">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    @endif
                    @if (session()->has('message'))
                        <div class="alert alert-danger">
                            <p>{!! session('message') !!}</p>
                        </div>
                    @endif
                    <div class="form-group row">
                        <label for="username" class="col-md-1 col-form-label"></label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="username" placeholder="Username or PT Enrollment ID" data-toggle="tooltip" title="Enter Username or PT Tester Enrollment ID which you got at the time of registration" data-placement="top" data-trigger="hover">
                        </div>
                    </div>                 
                     <div class="form-group row">
                        <label for="inputPassword3" class="col-md-1 col-form-label"></label>
                        <div class="col-md-10">
                            <input type="password" class="form-control" name="password" placeholder="Password " data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Enter Password that you use to Login"><a style="float: right" href="{{url('password/reset')}}" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click and enter Tester ID to get reset password link in your email">Forgot password</a>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-offset-1 col-md-5">
                            <button class="btn btn-primary btn-block" type="submit" name="signin" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click to LogIn"  >LOGIN</button>
                            </div>
                           <div class="col-md-offset-1 col-md-5">
                            <button class="btn btn-wisteria btn-block" onclick="confirmRegistration()" data-toggle="tooltip" data-placement="top" title="Click to Register a new participant" data-trigger="hover">REGISTER HERE</button>                            
                            </div>
                            <div class="col-md-offset-1 col-md-10">
                            <hr>
                            <h6 class="text-md-center">Designed for <a href="http://www.nphls.or.ke" data-toggle="tooltip" data-placement="top"  data-trigger="hover" title="Click to go to NPHL website">NPHL</a> by <a href="//www.ilabafrica.ac.ke" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click to go to iLabAfrica website" >@iLabAfrica</a></h6>
                            </div>                        
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
    <!-- Vue JS -->
    
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/vue-resource.min.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>
    <!-- Sweet Alert -->
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/jquery-1.12.3.min.js') }}"></script>
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        $(function () {
           $('[data-toggle="tooltip"]').tooltip()
          });
       
    </script>
    <script type="text/javascript">
        function confirmRegistration()
        {
            swal({
                title: "Have you participated in PT before?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "No",
                cancelButtonText: "Yes",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm)
            {
                if (isConfirm) 
                {
                    window.location.replace("/signup");
                } 
                else 
                {
                    swal("PT Participation Prompt", "By getting to this page, you're already registered, please retrieve your enrollment ID from past reports or your county health coordinator to proceed.", "success");
                }
            });
        }        
    </script>    
</html>