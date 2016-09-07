@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.facility-catalog') !!}</li>
            <li><a href="{!! route('facility.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.facility', 2) !!}</a></li>
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
			{!! Form::model($facility, array('route' => array('facility.update', $facility->id), 'method' => 'PUT', 'id' => 'form-edit-facility', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
        <div class="form-group row">
					{!! Form::label('code', trans('messages.code'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('code', old('code'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('name', trans_choice('messages.name',1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('sub-county', trans_choice('messages.sub-county',1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('sub_county', old('sub_county'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('mailing-address', trans('messages.mailing-address'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('mailing_address', old('mailing_address'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('in-charge', trans('messages.in-charge'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('in_charge', old('in_charge'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('in-charge-phone', trans('messages.in-charge-phone'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('in_charge_phone', old('in_charge_phone'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('in-charge-email', trans('messages.in-charge-email'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('in_charge_email', old('in_charge_email'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('longitude', trans('messages.longitude'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('longitude', old('longitude'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('latitude', trans('messages.latitude'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('latitude', old('latitude'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row col-sm-offset-4 col-sm-8">
					{!! Form::button("<i class='fa fa-check-circle'></i> ".trans('messages.update'),
					array('class' => 'btn btn-primary btn-sm', 'onclick' => 'submit()')) !!}
					<a href="#" class="btn btn-sm btn-silver"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</a>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
  	</div>
</div>
@endsection