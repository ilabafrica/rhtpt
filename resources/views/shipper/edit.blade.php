@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('shipper.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.shipper', 2) !!}</a></li>
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
			{!! Form::model($shipper, array('route' => array('shipper.update', $shipper->id), 'method' => 'PUT', 'id' => 'form-edit-shipper', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('name', trans_choice('messages.name', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('shipper-type', trans_choice('messages.shipper-type', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              @foreach($shipper_types as $key => $value)
  						      <label class="radio-inline">{!! Form::radio('shipper_type', $key, false, array('id' => 'shipper_type', 'onclick' => 'health(".tbl", this)')) !!}{{ $value }}</label>
              @endforeach
            </div>
        </div>
        <div class="form-group row">
					{!! Form::label('contact', trans_choice('messages.contact', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::textarea('contact', old('contact'), array('class' => 'form-control', 'rows' => '3')) !!}
					</div>
				</div>
        <div class="tbl" style="display:none;">
            <hr>
          	 	<table class="table table-bordered table-sm search-table" id="example" style="width:100%">
          			<thead>
          				<tr>
                    <th>{!! trans('messages.mfl-code') !!}</th>
                    <th>{!! trans_choice('messages.name', 1) !!}</th>
          					<th>{!! trans_choice('messages.sub-county', 1) !!}</th>
          					<th>{!! trans_choice('messages.county', 1) !!}</th>
          				</tr>
          			</thead>
          			<tbody>
          			@foreach($facilities as $key => $value)
          				<tr>
          					<td>
                      <label class="checkbox-inline">
                          {!! Form::checkbox("care[]", $value->id, '') !!}{!! $value->code !!}
                      </label>
                    </td>
                    <td>{!! $value->name !!}</td>
          					<td>{!! $value->subCounty->name !!}</td>
          					<td>{!! $value->subCounty->county->name !!}</td>
          				</tr>
          			@endforeach
          			</tbody>
          		</table>
            <hr>
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
