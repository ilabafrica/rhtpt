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
	    <i class="fa fa-book"></i> {!! trans('messages.message') !!}
	    <span>
        <a class="btn btn-sm btn-belize-hole" href="{!! url("bulk/compose") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.compose') !!}
  			</a>
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
            <th>{!! trans('messages.message') !!}</th>
            <th>{!! trans_choice('messages.pt-round', 1) !!}</th>
            <th>{!! trans('messages.date-sent') !!}</th>
            <th>{!! trans('messages.sender') !!}</th>
            <th>{!! trans('messages.total') !!}</th>
            <th>{!! trans('messages.action') !!}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($bulk as $key => $value)
          <tr>
            <td>{!! $value->message !!}</td>
            <td>{!! App\Models\Round::find($value->round_id)->name !!}</td>
            <td>{!! $value->created_at !!}</td>
            <td>{!! App\Models\Round::find($value->user_id)->name !!}</td>
            <td>{!! count($bulk) !!}</td>
            <td>

            <!-- show the test category (uses the show method found at GET /role/{id} -->
              <a class="btn btn-sm btn-success" href="{!! url("bulk/" . $value->id) !!}" >
                <i class="fa fa-folder-open-o"></i>
                {!! trans('messages.expand') !!}
              </a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
  	</div>
</div>
@endsection
