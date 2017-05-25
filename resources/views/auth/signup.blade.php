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
	<link rel="stylesheet" href="{{ asset('harmony/css/font-awesome.min.css') }}">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('harmony/css/bootstrap.css') }}">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="{{ asset('harmony/css/awesome-bootstrap-checkbox.css') }}">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="{{ asset('harmony/css/style.css') }}">
	<!-- Custom Font -->
	<link rel="stylesheet" href="{{ asset('harmony/css/font.css') }}">
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
					<div class="col-md-12  text-md-center">
						<img src="{{ '../'.Config::get('cms.logo') }}" height="50px">
						<h5 class="text-primary">{!! Config::get('cms.name') !!}</h5>
					</div>
				</div>
				<form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createParticipant" id="self_registration">
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
					<!-- Begin form fields -->
					<div class="col-md-6">
						<div class="form-group row">
	                        <label class="col-sm-4 form-control-label" for="title">Name:</label>
	                        <div class="col-sm-8">
	                            <input type="text" name="name" class="form-control" v-model="newParticipant.name"/>
	                            <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
	                        </div>
	                    </div>
	                    <div class="form-group row">
                            <label class="col-sm-4 form-control-label" for="title">Gender:</label>
                            <div class="col-sm-8">

                                <div class="form-radio radio-inline" v-for="sex in sexes">
                                    <label class="form-radio-label">
                                        <input type="radio" :value="sex.name" name="gender" v-model="newParticipant.gender">
                                        @{{ sex.title }}
                                    </label>
                                </div>
                                
                                <span v-if="formErrors['gender']" class="error text-danger">@{{ formErrors['gender'] }}</span>
                             </div>
                        </div>
	                    <div class="form-group row">
	                        <label class="col-sm-4 form-control-label" for="title">Phone Number:</label>
	                        <div class="col-sm-8">
	                            <input type="text" name="phone" class="form-control" v-model="newParticipant.phone" />
	                            <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <label class="col-sm-4 form-control-label" for="title">Email:</label>
	                        <div class="col-sm-8">
	                            <input type="text" name="email" class="form-control" v-model="newParticipant.email" />
	                            <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
	                        </div>
	                    </div>
	                    <div class="form-group row">
	                        <label class="col-sm-4 form-control-label" for="title">Address:</label>
	                        <div class="col-sm-8">
	                            <input type="text" name="address" class="form-control" v-model="newParticipant.address" />
	                            <span v-if="formErrors['address']" class="error text-danger">@{{ formErrors['address'] }}</span>
	                        </div>
	                    </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label" for="title">Designation:</label>
                            <div class="col-sm-8">
                                <select class="form-control c-select" name="designation" v-model="newParticipant.designation">
                                    <option v-for="des in designations" :value="des.name">@{{ des.title }}</option>   
                                </select>
                                <span v-if="formErrors['designation']" class="error text-danger">@{{ formErrors['designation'] }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 form-control-label" for="title">Program:</label>
                            <div class="col-sm-8">
                                <select class="form-control c-select" name="program" v-model="newParticipant.program">
                                    <option selected></option>
                                    <option v-for="prog in programs" :value="prog.id">@{{ prog.value }}</option>   
                                </select>
                                <span v-if="formErrors['program']" class="error text-danger">@{{ formErrors['program'] }}</span>
                            </div>
                        </div>
					</div>
					<div class="col-md-6">
						<div class="form-group row">
							<label for="username" class="col-md-4 col-form-label">County:</label>
							<div class="col-sm-8">
                                <select class="form-control c-select" name="county" v-model="newParticipant.county">
                                    <option v-for="cnty in counties" :value="cnty.id">@{{ cnty.value }}</option>   
                                </select>
                                <span v-if="formErrors['county']" class="error text-danger">@{{ formErrors['county'] }}</span>
                            </div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-md-4 col-form-label">Sub County:</label>
							<div class="col-md-8">
								<input type="text" name="sub_county" class="form-control" v-model="newParticipant.sub_county"/>
	                            <span v-if="formErrors['sub_county']" class="error text-danger">@{{ formErrors['sub_county'] }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="username" class="col-md-4 col-form-label">MFL Code:</label>
							<div class="col-md-8">
								<input type="text" name="mfl_code" class="form-control" v-model="newParticipant.mfl_code"/>
	                            <span v-if="formErrors['mfl_code']" class="error text-danger">@{{ formErrors['mfl_code'] }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-md-4 col-form-label">Facility:</label>
							<div class="col-md-8">
								<input type="text" name="facility" class="form-control" v-model="newParticipant.facility"/>
	                            <span v-if="formErrors['facility']" class="error text-danger">@{{ formErrors['facility'] }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="username" class="col-md-4 col-form-label">In Charge:</label>
							<div class="col-md-8">
								<input type="text" name="in_charge" class="form-control" v-model="newParticipant.in_charge"/>
	                            <span v-if="formErrors['in_charge']" class="error text-danger">@{{ formErrors['in_charge'] }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-md-4 col-form-label">In Charge Email:</label>
							<div class="col-md-8">
								<input type="text" name="in_charge_email" class="form-control" v-model="newParticipant.in_charge_email"/>
	                            <span v-if="formErrors['in_charge_email']" class="error text-danger">@{{ formErrors['in_charge_email'] }}</span>
							</div>
						</div>
						<div class="form-group row">
							<label for="inputPassword3" class="col-md-4 col-form-label">In Charge Phone:</label>
							<div class="col-md-8">
								<input type="text" name="in_charge_phone" class="form-control" v-model="newParticipant.in_charge_phone"/>
	                            <span v-if="formErrors['in_charge_phone']" class="error text-danger">@{{ formErrors['in_charge_phone'] }}</span>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-md-offset-2 col-md-10">
							<button class="btn btn-wisteria btn-block" type="submit">REGISTER</button>							
						</div>
					</div>
					<hr>
					<h6 class="text-md-center">Designed for <a href="http://www.nphls.or.ke">NHRL</a> by <a href="//www.ilabafrica.ac.ke">@iLabAfrica</a></h6>
				</form>
			</div>
		</div>
	</div>
</body>
	<!-- Vue JS -->
	<script src="{{ asset('js/jquery-1.12.3.min.js') }}"></script>
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/vue-resource.min.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script src="{{ asset('controllers/signup.js') }}"></script>
</html>
