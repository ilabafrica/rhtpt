@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('item.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.pt-item', 2) !!}</a></li>
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
			{!! Form::open(array('route' => 'item.store', 'id' => 'form-add-item', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('pt-id', trans('messages.pt-id'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('pt_id', old('pt_id'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('program', trans_choice('messages.pt-program', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6 custom-c-select">
              {!! Form::select('program', array(''=>trans('messages.select'))+$programs, '', array('class' => 'form-control', 'id' => 'program')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('material', trans('messages.material'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6 custom-c-select">
              {!! Form::select('material', array(''=>trans('messages.select'))+$materials, '', array('class' => 'form-control', 'id' => 'material')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('round', trans_choice('messages.pt-round', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6 custom-c-select">
              {!! Form::select('round', array(''=>trans('messages.select'))+$rounds, '', array('class' => 'form-control', 'id' => 'round')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('prepared-by', trans('messages.prepared-by'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6 custom-c-select">
              {!! Form::select('prepared_by', array(''=>trans('messages.select'))+$users, '', array('class' => 'form-control', 'id' => 'prepared_by')) !!}
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
