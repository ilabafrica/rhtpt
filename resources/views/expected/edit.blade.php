@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('expected.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.expected-result', 2) !!}</a></li>
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
			{!! Form::model($expected, array('route' => array('expected.update', $expected->id), 'method' => 'PUT', 'id' => 'form-edit-expected', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
        <div class="form-group row">
            {!! Form::label('item', trans_choice('messages.pt-item', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('item', array(''=>trans('messages.select'))+$items, $item, array('class' => 'form-control c-select', 'id' => 'item')) !!}
            </div>
        </div>
				<div class="form-group row">
					{!! Form::label('expected-result', trans_choice('messages.expected-result', 1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-8">
            @foreach($results as $key => $value)
						      <label class="radio-inline">{!! Form::radio('result', $key, false) !!}{{ $value }}</label>
            @endforeach
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('tested-by', trans('messages.tested-by'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('tested_by', array(''=>trans('messages.select'))+$users, $user, array('class' => 'form-control c-select', 'id' => 'tested_by')) !!}
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
