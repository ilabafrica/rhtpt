@extends('layouts.app')
@section('content')
<!-- Custom Styling -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<style>
.form-control:focus {
    border-color: #006400;
    outline: none;

   }  
</style>
<div class="resetpassword" style="padding-top:20px;">

    <div class="card col-md-3" style="margin:auto; float:none">
            @if (session('status'))
                         <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
        <form class="form-signin"  role="form" method="post" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}
            <div style="text-align:center;">
              <img src="{{ '../'.Config::get('cms.logo') }}" style="max-height:120px;margin:0 auto !important;">
                <h3 class="form-signin-heading">
                    <span>Kenya Serology Rapid HIV PT</span>
                </h3>               
            </div> 
            <div class="bs-callout bs-callout-info text-left">
            <h4 class="md-18">Reset Passowrd</h4>
            <p> Enter your email and your new password. Keep your password safe for your next login.</p>
            </div>  
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" >E-Mail Address</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                         </div>
                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" >Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
</div>
@endsection
