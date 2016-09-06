@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li><a href="{!! route('material.index') !!}"><i class="fa fa-cube"></i> {!! trans('messages.sample-preparation') !!}</a></li>
            <li class="active">{!! trans('messages.add') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-pencil"></i> {!! trans('messages.add') !!}
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
			{!! Form::open(array('route' => 'material.store', 'id' => 'form-add-material', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('batch', trans('messages.batch'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('batch', old('batch'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('date-prepared', trans('messages.date-prepared'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
						{!! Form::text('date_prepared', old('date_prepared'), array('class' => 'form-control')) !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('expiry-date', trans('messages.expiry-date'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
						{!! Form::text('expiry_date', old('expiry_date'), array('class' => 'form-control')) !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('material-type', trans('messages.material-type'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('material_type', old('material_type'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('original-source', trans('messages.original-source'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('original_source', old('original_source'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('date-collected', trans('messages.date-collected'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
						{!! Form::text('date_collected', old('date_collected'), array('class' => 'form-control')) !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('prepared-by', trans('messages.prepared-by'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('prepared_by', old('prepared_by'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row col-sm-offset-4 col-sm-8">
					{!! Form::button("<i class='fa fa-plus-circle'></i> ".trans('messages.save'),
						array('class' => 'btn btn-primary btn-sm', 'onclick' => 'submit()')) !!}
					<a href="#" class="btn btn-sm btn-silver"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</a>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
  	</div>
</div>
@endsection
