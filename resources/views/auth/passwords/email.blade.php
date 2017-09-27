@extends('layouts.app')
@section('content')
<div class="resetpassword" style="padding-top:20px;">
    <div class="card col-md-4" style="margin:auto; float:none">
        @if (session('status'))
             <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form class="form-signin"  role="form" method="post" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div style="text-align:center;">
              <img src="{{ '../'.Config::get('cms.logo') }}" style="max-height:120px;margin:0 auto !important;">
                <h3 class="form-signin-heading">
                    <span>Kenya Serology Rapid HIV PT</span>
                </h3>               
            </div> 
            <div class="bs-callout bs-callout-info text-left">
                <h4 class="md-18">Password Recovery</h4>
                <p> If you have forgotten your password, fill in your PT Enrollment ID below and click Reset Password button. Instructions will be sent to your registered email on how to reset your password.</p>
            </div>   
            <div class="form-group{{ $errors->has('email_enrollment_id') ? ' has-error' : '' }}">
                <label for="email_enrollment_id">PT Enrollment ID/Email</label>
                <input id="email_enrollment_id" type="text"  class="form-control" name="email" placeholder="eg. 11695 or me@mymail.com  " value="{{ old('email_enrollment_id') }}" data-toggle="tooltip" title="Enter Email or PT Enrollment ID" data-placement="top" data-trigger="hover" autofocus="" required>
               @if ($errors->has('email_enrollment_id'))
                    <span class="help-block ">
                        <strong>{{ $errors->first('email_enrollment_id') }}</strong>
                    </span>
                @endif
            </div>            
            <button style="background-color: #3498db;border-color: #3498db" type="submit" class="btn btn-md btn-info btn-block">Reset Password</button>
            <br><a href="/login" style="color:#18bc9c">No, I remember my password.</a>
        </form>
      </div> 
</div>
@endsection