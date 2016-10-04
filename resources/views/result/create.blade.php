@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('result.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.result', 2) !!}</a></li>
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
			{!! Form::open(array('route' => 'result.store', 'id' => 'form-add-result', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8">
        @foreach($sets as $set)
            <h5><strong class="text-primary">{!! $set->name !!}</strong></h5>
            <hr>
            @if(count($set->fields)>0)
                @foreach($set->fields as $field)
                <div class="form-group row">
                  {!! Form::label('name', $field->label, array('class' => 'col-sm-4 form-control-label')) !!}
                    @if($field->tag == App\Models\Field::CHECKBOX)
                      <div class="col-sm-6">
                        <div class="card card-block">
                          {{--*/ $cnt = 0 /*--}}
                          @foreach($field->options as $k => $val)
                                {!! ($cnt%4==0)?"<div class='row'>":"" !!}
                                {{--*/ $cnt++ /*--}}
                                <div class="col-md-3">
                                  <label class="checkbox-inline">{!! Form::checkbox('opt[]', $val->id, '') !!}{{ $val->name }}</label>
                                </div>
                                {!! ($cnt%4==0)?"</div>":"" !!}
                          @endforeach
                          {!! ($cnt%4==0)?"":"</div>" !!}
                        </div>
                      </div>
                    @elseif($field->tag == App\Models\Field::DATE)
                      <div class="col-sm-6 input-group date datepicker"   style="padding-left:15px;padding-right:15px;">
            						{!! Form::text('start_date', old('start_date'), array('class' => 'form-control')) !!}
            						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            					</div>
                    @elseif($field->tag == App\Models\Field::EMAIL)
                      <div class="col-sm-6">
                        {!! Form::email('name', old('name'), array('class' => 'form-control')) !!}
                      </div>
                    @elseif($field->tag == App\Models\Field::FIELD)
                      <div class="col-sm-6">
                        {!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
                      </div>
                    @elseif($field->tag == App\Models\Field::RADIO)
                      <div class="col-sm-6">
                        <div class="card card-block">
                          {{--*/ $counter = 0 /*--}}
                          @foreach($field->options as $key => $value)
                              {!! ($counter%4==0)?"<div class='row'>":"" !!}
                              {{--*/ $counter++ /*--}}
                              <div class="col-md-3">
                                <label class="radio-inline">{!! Form::radio('tag', $value->id, false, array('id' => 'tag')) !!}{{ $value->name }}</label>
                              </div>
                              {!! ($counter%4==0)?"</div>":"" !!}
                          @endforeach
                          {!! ($counter%4==0)?"":"</div>" !!}
                        </div>
            					</div>
                    @elseif($field->tag == App\Models\Field::SELECT)
                      <div class="col-sm-6">
                        {!! Form::select('county', array(''=>trans('messages.select')), '', array('class' => 'form-control c-select', 'id' => '')) !!}
                      </div>
                    @elseif($field->tag == App\Models\Field::TEXT)
                      <div class="col-sm-6">
                          {!! Form::textarea('description', old('description'), array('class' => 'form-control', 'rows' => '3')) !!}
                      </div>
                    @endif
                  </div>
                @endforeach
            @endif
        @endforeach
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
