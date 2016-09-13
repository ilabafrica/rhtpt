@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('item.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.pt-item', 2) !!}</a></li>
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
			{!! Form::model($item, array('route' => array('item.update', $item->id), 'method' => 'PUT', 'id' => 'form-edit-item', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
				<div class="form-group row">
					{!! Form::label('pt-id', trans('messages.pt-id'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('pt_identifier', $item->pt_id, array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('program', trans_choice('messages.program', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('program', array(''=>trans('messages.select'))+$programs, $program, array('class' => 'form-control c-select', 'id' => 'program')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('material', trans_choice('messages.material', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('material', array(''=>trans('messages.select'))+$materials, $material, array('class' => 'form-control c-select', 'id' => 'material')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('round', trans_choice('messages.pt-round', 1), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('round', array(''=>trans('messages.select'))+$rounds, $round, array('class' => 'form-control c-select', 'id' => 'round')) !!}
            </div>
        </div>
        <div class="form-group row">
            {!! Form::label('prepared-by', trans('messages.prepared-by'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('prepared_by', array(''=>trans('messages.select'))+$users, $user, array('class' => 'form-control c-select', 'id' => 'prepared_by')) !!}
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
