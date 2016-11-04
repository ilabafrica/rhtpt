@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('shipment.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.shipment', 2) !!}</a></li>
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
			{!! Form::open(array('route' => 'shipment.store', 'id' => 'form-add-shipment', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('round', trans_choice('messages.pt-round', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
					{!! Form::select('round', array(''=>trans('messages.select'))+$rounds, '', array('class' => 'form-control c-select', 'id' => 'item')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('county', trans_choice('messages.county', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
					{!! Form::select('county', array(''=>trans('messages.select'))+$counties, '', array('class' => 'form-control c-select', 'id' => 'county')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('sub-county', trans_choice('messages.sub-county', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
					{!! Form::select('sub_county', array(''=>trans('messages.select')), '', array('class' => 'form-control c-select', 'id' => 'sub_county')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('facility', trans_choice('messages.facility', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
					{!! Form::select('facility', array(''=>trans('messages.select')), '', array('class' => 'form-control c-select', 'id' => 'facility')) !!}
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
					{!! Form::label('date-shipped', trans('messages.date-shipped'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
						{!! Form::text('date_shipped', old('date_shipped'), array('class' => 'form-control')) !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('shipping-method', trans_choice('messages.shipping-method', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-8">
						@foreach($shipping_methods as $key => $value)
							<label class="radio-inline">{!! Form::radio('shipper', $key, false, array('id' => 'shipper', 'onclick' => 'toggling(".toggled", this)')) !!}{{ $value }}</label>
						@endforeach
					</div>
				</div>
				<div class="form-group row toggled" id="specify" style="display:none;">
					{!! Form::label('specify', 'If Other, specify', array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('shipping_method', old('courier'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row toggled" id="courier" style="display:none;">
				{!! Form::label('courier', trans_choice('messages.courier', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::select('shipping_method', array(''=>trans('messages.select'))+$courier, '', array('class' => 'form-control c-select')) !!}
					</div>
				</div>
				<div class="form-group row toggled" id="partners" style="display:none;">
					{!! Form::label('partner', trans_choice('messages.partner', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::select('shipping_method', array(''=>trans('messages.select')), '', array('class' => 'form-control c-select', 'id' => 'partner')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('panels-shipped', trans('messages.panels-shipped'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('panels_shipped', old('panels_shipped'), array('class' => 'form-control')) !!}
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
