@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('receipt.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.receipt', 2) !!}</a></li>
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
			{!! Form::open(array('route' => 'receipt.store', 'id' => 'form-add-receipt', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
        <div class="form-group row">
            {!! Form::label('shipment', trans_choice('messages.shipment', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('shipment', array(''=>trans('messages.select'))+$shipments, '', array('class' => 'form-control c-select', 'id' => 'item')) !!}
            </div>
        </div>
        <div class="form-group row">
					{!! Form::label('date-received', trans('messages.date-received'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
						{!! Form::text('date_received', old('date_received'), array('class' => 'form-control')) !!}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
				</div>
        <div class="form-group row">
					{!! Form::label('panels-received', trans('messages.panels-received'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('panels_received', old('panels_received'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('condition', trans('messages.condition'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
                {!! Form::textarea('condition', old('condition'), array('class' => 'form-control', 'rows' => '3')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('storage', trans('messages.storage'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
                {!! Form::textarea('storage', old('storage'), array('class' => 'form-control', 'rows' => '3')) !!}
            </div>
        </div>
        <div class="form-group row">
					{!! Form::label('transit-temperature', trans('messages.transit-temperature'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('transit_temperature', old('transit_temperature'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('recipient', trans_choice('messages.recipient', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('recipient', array(''=>trans('messages.select'))+$facilities, '', array('class' => 'form-control c-select', 'id' => 'facility')) !!}
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
