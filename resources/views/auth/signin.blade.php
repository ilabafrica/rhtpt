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

	<title>{!! Config::get('cms.name') !!}</title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="{{ asset('harmony/css/font-awesome.min.css') }}">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('harmony/css/bootstrap.css') }}">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="{{ asset('harmony/css/awesome-bootstrap-checkbox.css') }}">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="{{ asset('harmony/css/style.css') }}">
	<!-- Custom Font -->
	<link rel="stylesheet" href="{{ asset('harmony/css/font.css') }}">

	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
	<div class="login-page bk-img" style="">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3" style="padding-top:20px;">
					<div class="card card-block">
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">

								<div class="row" style="padding-bottom:20px">
			                        <div class="col-md-5">
			                            <img src="{{ '../'.Config::get('cms.logo') }}" height="75px">
			                        </div>
			                        <div class="col-md-7">
			                            <h4 class="text-primary">{!! Config::get('cms.name') !!}</h4>
			                        </div>
			                    </div>
								@if($errors)
	                                @if (count($errors) > 0)
	                                <div class="alert alert-danger col-sm-12">
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
								<form class="mt" role="form" method="POST" action="{{ url('auth/login') }}">
									<!-- CSRF Token -->
	                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
	                                <!-- ./ csrf token -->

									<label for="" class="text-uppercase text-sm">Your Username or Email</label>
									<input type="text" name="username" id="username" placeholder="Username" class="form-control mb">

									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" name="password" id="password" placeholder="Password" class="form-control mb">

									<div class="checkbox checkbox-circle checkbox-info">
										<input id="checkbox7" type="checkbox" checked>
										<label for="checkbox7">
											Keep me signed in
										</label>
									</div>

									<button class="btn btn-primary btn-block" type="submit">LOGIN</button>

								</form>
							</div>
						</div>
						<div class="text-center text-light">
							<a href="#" class="text-light">Forgot password?</a>
						</div>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
	<!-- Loading Scripts -->
	<script src="{{ asset('harmony/js/jquery.min.js') }}"></script>
	<script src="{{ asset('harmony/js/bootstrap-select.min.js') }}"></script>
	<script src="{{ asset('harmony/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('harmony/js/main.js') }}"></script>
</html>
