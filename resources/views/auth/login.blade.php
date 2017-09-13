<!doctype html>
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

          <title>Kenya Serology Rapid HIV PT,Login</title>

    <!-- Bootstrap core CSS -->
      <link rel="stylesheet" href="/css/bootstrap-core.css" type="text/css" media="all">
      <link rel="stylesheet" href="/css/font-awesome.css" type="text/css" media="all"> 
      <style>
    .form-control:focus {
    border-color: #006400;
    outline: none;
  
     }
     .loginArea{
        color:#333;
        margin-top:15px;
        padding: 15px;
        border-radius: 3px 
     }
      </style>  
</head>

    <!-- Fixed navbar -->
    <body>    
    <div class="login-page" style="padding-top:20px;">
    <div class="card col-md-4" style="margin:auto; float:none">
        <div style="text-align:center;">
        <br>
         <img src="{{ '../'.Config::get('cms.logo') }}" style="max-height:120px;margin:0 auto !important;">
            <h3 class="form-signin-heading">
            <span>Kenya Serology Rapid HIV PT</span>
            </h3>
        </div> 
        <div class="bs-callout bs-callout-info text-left">
        <h5 class="md-18">Getting Started</h5>
             <p>Login with your Username or PT Enrollment ID. Click on the Sign-up button to register as a new participant. If you have forgotten your password, you can reset it by clicking the Lost password button.</p>
        </div>
      <form class="mt form-signin" id="loginForm" Irole="form" method="POST" action="{{ route('login') }}">
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
                    <div class="form-group">
                        <label for="username/TesterID">Username or PT Enrollment ID</label>
                        <input type="text" class="isRequired form-control" data-vv-validate="'required'" name="username" placeholder="eg. mymail@gmail.com or 11695" data-toggle="tooltip" title="Enter Username or PT Tester Enrollment ID which you got at the time of registration" data-placement="top" data-trigger="hover" autofocus="">
                    </div>
                    <div class="form-group">
                         <label for="inputPassword3">Password</label>
                         <input type="password" class="form-control" name="password" placeholder="eg.sjK2542" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Enter Password that you use to Login">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" style="color:#fff;background-color:#2c3e50;border-color:#2c3e50" type="submit" name="signin" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click to LogIn"  >LOG IN</button>      
                    </div>    
                    <div class="form-group row">
                   
                        <div class="col-md-offset-1 col-md-5">
                            <br>
                            <a class=" pull-center" style="color:#3498db" onclick="confirmRegistration()" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click to Register as a new participant"><strong>Sign up </strong></a>
                            <br>
                        </div>
                        <div class="col-md-offset-1 col-md-5">
                            <br>
                            <a class=" pull-center" style="color:#3498db" href="{{url('password/reset')}}" data-toggle="tooltip" data-placement="top" data-trigger="hover" title="Click and enter Tester ID to get reset password link in your email"><strong>Lost password</strong></a>
                            <br>
                        </div>                 
                    </div>                       
        </form>
    </div>
    </div>    
</body>
</html>
   
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