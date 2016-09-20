@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li><a href="{!! route('field.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.field', 2) !!}</a></li>
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
			{!! Form::open(array('route' => 'field.store', 'id' => 'form-add-field', 'class' => 'form-horizontal')) !!}
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
					{!! Form::label('label', trans('messages.label'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('label', old('label'), array('class' => 'form-control')) !!}
					</div>
				</div>
        <div class="form-group row">
            {!! Form::label('order', trans('messages.order'), array('class' => 'col-sm-4 form-control-label')) !!}
            <div class="col-sm-6">
              {!! Form::select('order', array(''=>trans('messages.select'))+$fields, '', array('class' => 'form-control c-select', 'id' => 'item')) !!}
            </div>
        </div>
        <hr>
				<div class="form-group row">
					{!! Form::label('tag', trans('messages.tag'), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
            <div class="card card-block">
              {{--*/ $counter = 0 /*--}}
              @foreach($field_types as $key => $value)
                    {!! ($counter%4==0)?"<div class='row'>":"" !!}
                    {{--*/ $counter++ /*--}}
                    <div class="col-md-3">
                      <label class="radio-inline">{!! Form::radio('tag', $key, false, array('id' => 'tag', 'onclick' => 'options(".options", this)')) !!}{{ $value }}</label>
                    </div>
                    {!! ($counter%4==0)?"</div>":"" !!}
              @endforeach
            </div></div>
					</div>
				</div>
        <div class="options" style="display:none;">
          <div class="form-group row">
              {!! Form::label('options', trans_choice('messages.option', 2), array('class' => 'col-sm-4 form-control-label')) !!}
              <div class="col-sm-6">
                <div class="card card-block">
                  {{--*/ $cnt = 0 /*--}}
                  @foreach($options as $k => $val)
                        {!! ($cnt%4==0)?"<div class='row'>":"" !!}
                        {{--*/ $cnt++ /*--}}
                        <div class="col-md-3">
                          <label class="checkbox-inline">{!! Form::checkbox('opt[]', $k, '') !!}{{ $val }}</label>
                        </div>
                        {!! ($cnt%4==0)?"</div>":"" !!}
                  @endforeach
                </div>
              </div>
          </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-sm-offset-4 col-sm-6">
                <label class="checkbox-inline">
                    {!! Form::checkbox("is_matrix", '1', '', array('onclick' => 'untoggle(".matrix", this)')) !!}{{ trans('messages.matrix') }}
                </label>
            </div>
        </div>
        <div class="matrix" style="display:none;">
          <div class="form-group row">
              {!! Form::label('test-resuts', trans('messages.matrix'), array('class' => 'col-sm-4 form-control-label')) !!}
              <div class="col-sm-6">
                @foreach($matrix_types as $x => $y)
                      <label class="radio-inline">{!! Form::radio('matrix', $x, false) !!}{{ $y }}</label>
                @endforeach
              </div>
          </div>
        </div>
        <hr>
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
