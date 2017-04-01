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
				<form class="mt" role="form" method="POST" action="{{ url('login') }}">
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
						<label for="username" class="col-md-2 col-form-label">Username</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="username" placeholder="Username">
						</div>
					</div>
					<div class="form-group row">
						<label for="inputPassword3" class="col-md-2 col-form-label">Password</label>
						<div class="col-md-10">
							<input type="password" class="form-control" name="password" placeholder="Password">
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-offset-2 col-md-10">
							<button class="btn btn-primary btn-block" type="submit">LOGIN</button>
							<hr>
							<h6 class="text-md-center">Designed for <a href="http://www.nphls.or.ke">NHRL</a> by <a href="//www.ilabafrica.ac.ke">@iLabAfrica</a></h6>
						</div>
					</div>
				</form>
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
