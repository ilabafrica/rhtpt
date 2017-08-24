<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" type="image/x-icon" href="{{ Config::get('cms.favicon') }}">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="theme-color" content="#3e454c">
    <meta id="token" name="token" value="{{ csrf_token() }}">  

	<title>{!! Config::get('cms.name') !!}</title>

	<!-- Font awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <!-- Custom Font -->
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <!-- Custom Styling -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Toastr Styling -->
    <link href="{{ asset('css/toastr.min.css') }}" rel="stylesheet">
</head>

<body>
	<div class="register-page" id="manage-registration" style="padding-top:20px;">
		<div class="card col-md-10" style="margin:auto; float:none">
			<div class="card-block">
				<div class="row" style="padding:20px">
					<div class="col-md-12 text-md-center">
						<img src="{{ '../'.Config::get('cms.logo') }}" height="50px">
						<h5 class="text-warning"><strong>{!! Config::get('cms.name') !!}</strong></h5>
                        <h5 class="text-primary"><strong>Email Verification</strong></h5>
					</div>
				</div>
				<div class="col-md-offset-1 col-md-10 text-md-center">
                    @if (session()->has('info'))
                    <div class="card card-outline-info text-center">
                        <div class="card-block">
                            <blockquote class="card-blockquote text-white">
                                <strong>{!! session('info') !!}</strong>
                            </blockquote>
                        </div>
                    </div>
                    @endif
                    @if (session()->has('warning'))
                    <div class="card card-outline-warning text-center">
                        <div class="card-block">
                            <blockquote class="card-blockquote text-white">
                                <strong>{!! session('warning') !!}</strong>
                            </blockquote>
                        </div>
                    </div>
                    @endif
                    @if (session()->has('success'))
                    <div class="card card-outline-success text-center">
                        <div class="card-block">
                            <blockquote class="card-blockquote text-white">
                                <strong>{!! session('success') !!}</strong>
                            </blockquote>
                        </div>
                    </div>
                    @endif
                </div>
			</div>
            <div class="row" style="padding:20px">
                <div class="col-md-12 text-md-center">
                    <div class="col-md-3 col-md-offset-4">
                        <button class="btn btn-midnight-blue btn-block" @click="backHome"><strong><i class="fa fa-logout"></i> Home</strong></button>                            
                    </div>
                </div>
            </div>
		</div>
	</div>
</body>
	<!-- Core JS -->
	<script src="{{ asset('js/jquery-1.12.3.min.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- Vue JS -->
    <script src="{{ asset('js/vue.js') }}"></script>
    <script src="{{ asset('js/vue-resource.min.js') }}"></script>
    <script src="{{ asset('js/vee-validate.js') }}"></script>

    <script>
        Vue.use(VeeValidate); // good to go. 
    </script>
    <!-- Toastr -->
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('controllers/signup.js') }}"></script>
</html>
