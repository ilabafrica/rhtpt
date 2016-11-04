@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li><a href="{!! route('user.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.user', 2) !!}</a></li>
            <li class="active">{!! trans('messages.edit') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-edit"></i> {!! trans('messages.edit') !!}
	    <span>
			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
				<i class="fa fa-step-backward"></i>
				{!! trans('messages.back') !!}
			</a>
		</span>
	</div>
  	<div class="card-block">
		<!-- if there are creation errors, they will show here -->
		@if($errors->all())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
            {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
        </div>
        @endif
		<div class="row">
			{!! Form::model($user, array('route' => array('user.update', $user->id),
    		'method' => 'PUT', 'enctype' => 'multipart/form-data', 'id' => 'form-edit-user', 'class' => 'form-horizontal', 'files' => 'true')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('name', trans_choice('messages.name',1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('uid', trans('messages.uid'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('uid', old('uid'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('gender', trans('messages.gender'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						<label class="radio-inline">{!! Form::radio('gender', App\Models\User::MALE, true) !!}{{ trans('messages.male') }}</label>
            <label class="radio-inline">{!! Form::radio("gender", App\Models\User::FEMALE, false) !!}{{ trans('messages.female') }}</label>
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('email', trans('messages.email-address'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('email', old('email'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('phone', trans('messages.phone'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
                {!! Form::text('phone', old('phone'), array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('address', trans('messages.address'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
                {!! Form::textarea('address', old('address'), array('class' => 'form-control', 'rows' => '3')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('username', trans('messages.username'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
                {!! Form::text('username', old('username'), array('class' => 'form-control')) !!}
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-6">
                <label class="checkbox-inline">
                    {!! Form::checkbox("default_password", '1', '', array('onclick' => 'toggle(".pword", this)')) !!}{{ trans('messages.use-default') }}
                </label>
            </div>
        </div>
        <div class="pword">
          <div class="form-group row">
              {!! Form::label('password', trans_choice('messages.password', 1), array('class' => 'col-sm-4 form-control-label')) !!}
              <div class="col-sm-6">
                  {!! Form::password('password', array('class' => 'form-control')) !!}
              </div>
          </div>
          <div class="form-group row">
              {!! Form::label('password_confirmation', trans_choice('messages.password', 2), array('class' => 'col-sm-4 form-control-label')) !!}
              <div class="col-sm-6">
                  {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
              </div>
          </div>
        </div>
				<div class="form-group row col-sm-offset-4 col-sm-8">
					{!! Form::button("<i class='fa fa-check-circle'></i> ".trans('messages.update'),
					array('class' => 'btn btn-primary btn-sm', 'onclick' => 'submit()')) !!}
					<a href="#" class="btn btn-sm btn-silver"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</a>
				</div>
			</div>
	        <div class="col-md-4">
	            <div class="row">
	                <div class="col-md-12">
	                    <div class="thumbnail">
	                        {!! HTML::image('images/profiles/'.$user->image, trans('messages.no-photo'), array('class'=>'img-responsive img-thumbnail user-image')) !!}
	                    </div>
	                </div>
	                <div class="col-md-8 col-sm-offset-1">
	                    <div class="form-group">
	                        <label>{{ trans('messages.profile-photo') }}</label>
	                        {!! Form::file('photo', null, ['class' => 'form-control']) !!}
	                    </div>
	                </div>
	            </div>
	        </div>
			{!! Form::close() !!}
		</div>
  	</div>
</div>
@endsection
