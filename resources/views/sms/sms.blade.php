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
<!-- if there are creation errors, they will show here -->
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
	    <i class="fa fa-pencil"></i> {!! trans('messages.compose') !!}
	    <span>
			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
				<i class="fa fa-step-backward"></i>
				{!! trans('messages.back') !!}
			</a>
		</span>
	</div>
  	<div class="card-block">
      <table class="table table-bordered table-sm search-table" id="example">
        <thead>
          <tr>
            <th>{!! trans('messages.participant') !!}</th>
            <th>{!! trans('messages.phone') !!}</th>
            <th>{!! trans('messages.date-sent') !!}</th>
            <th>{!! trans('messages.status') !!}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($broadcast as $key => $value)
          <tr>
            <td>{!! $value->user_id !!}</td>
            <td>{!! $value->number !!}</td>
            <td>{!! $value->date_sent !!}</td>
            <td>{!! $value->status !!}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
  	</div>
</div>
@endsection
