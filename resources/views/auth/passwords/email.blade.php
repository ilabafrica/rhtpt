@extends('layouts.app')
@section('content')
<style>
.form-control:focus {
    border-color: #006400;
    outline: none;

   }  

     </style>
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
                    <span>Kenya Serology Rapid HIV PT</span><br><br>Password Recovery<br><br>
                </h3>               
            </div>    
             <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email">PT Enrollment ID</label>
                 <input id="email" type="email" class="form-control" name="username" placeholder="eg. 19595" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
            </div>            
            <button style="background-color: #3498db;border-color: #3498db" type="submit" class="btn btn-md btn-info btn-block">
           Send Password Reset Link
              </button>
            <br><a href="/login" style="color:#18bc9c">No, I remember my password.</a>
        </form>
            </div> 
    </div>
@endsection
