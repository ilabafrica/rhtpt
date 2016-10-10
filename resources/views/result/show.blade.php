@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li><a href="{!! route('option.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.option', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong></strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("option/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.add') !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("option/edit") !!}" >
				<i class="fa fa-edit"></i>
				{!! trans('messages.edit') !!}
			</a>
			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
				<i class="fa fa-step-backward"></i>
				{!! trans('messages.back') !!}
			</a>
		</span>
	</div>
	<!-- if there are creation errors, they will show here -->
	@if($errors->all())
		<div class="alert alert-danger">
			{!! HTML::ul($errors->all()) !!}
		</div>
	@endif
	<div class="card-block">
    <table class="table table-bordered table-sm">
      <thead>
        <tr>
          <th>{!! trans_choice('messages.field', 1) !!}</th>
          <th>{!! trans_choice('messages.option', 1) !!}</th>
        </tr>
      </thead>
      <tbody>
      @foreach($results as $key => $value)
        <tr>
          <td>{!! $value->field->name !!}</td>
          <td>{!! $value->response !!}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
	</div>
</div>
@endsection
