@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.bulk-sms') !!}</li>
            <li><a href="{!! route('bulk.broadcast') !!}"><i class="fa fa-cube"></i> {!! trans('messages.broadcast') !!}</a></li>
        </ol>
    </div>
</div>
@if (Session::has('message'))
  <div class="alert alert-info">{!! Session::get('message') !!}</div>
@endif
@if($errors->all())
  <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
      {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
  </div>
@endif
<div class="card">
	<div class="card-header">
	    <i class="fa fa-book"></i> {!! trans('messages.broadcast') !!}
	    <span>
  			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
  				<i class="fa fa-step-backward"></i>
  				{!! trans('messages.back') !!}
  			</a>
		</span>
	</div>
  	<div class="card-block">
      <div class="row">
    			{!! Form::open(array('route' => 'bulk.send', 'id' => 'form-compose-sms', 'class' => 'form-horizontal')) !!}
    			<!-- CSRF Token -->
          <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
          <!-- ./ csrf token -->
    			<div class="col-md-8">
            <div class="form-group row">
                {!! Form::label('round', trans_choice('messages.pt-round', 1), array('class' => 'col-sm-4 form-control-label')) !!}
                <div class="col-sm-6">
                  {!! Form::select('round', array(''=>trans('messages.select'))+$rounds, '', array('class' => 'form-control c-select', 'id' => 'round')) !!}
                </div>
            </div>
            <div class="form-group row">
    					{!! Form::label('message', trans('messages.message'), array('class' => 'col-sm-4 form-control-label')) !!}
    					<div class="col-sm-6">
    						{!! Form::textarea('message', old('message'), array('class' => 'form-control', 'rows' => '3')) !!}
    					</div>
    				</div>
            <div class="tbl">
                <hr>
              	 	<table class="table table-bordered table-sm search-table" id="example" style="width:100%">
              			<thead>
              				<tr>
                        <th>{!! trans_choice('messages.participant', 1) !!}</th>
                        <th>{!! trans('messages.phone') !!}</th>
                        <th>{!! trans_choice('messages.facility', 1) !!}</th>
              					<th>{!! trans_choice('messages.sub-county', 1) !!}</th>
              				</tr>
              			</thead>
              			<tbody>
              			@foreach($users as $key => $value)
              				<tr>
              					<td>
                          <label class="checkbox-inline">
                              {!! Form::checkbox("participant[]", $value->phone, '') !!}{!! $value->name !!}
                          </label>
                        </td>
                        <td>{!! $value->phone !!}</td>
              					<td>{!! $value->phone !!}</td>
              					<td>{!! $value->phone !!}</td>
              				</tr>
              			@endforeach
              			</tbody>
              		</table>
                <hr>
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
{!! session(['SOURCE_URL' => URL::full()]) !!}
@endsection
