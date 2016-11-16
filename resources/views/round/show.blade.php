@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('round.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.pt-round', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $round->name !!}</strong>
	    <span>
        @permission('create-round')
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("round/create") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.add') !!}
  			</a>
        @endpermission
        @permission('update-round')
  			<a class="btn btn-sm btn-info" href="{!! url("round/" . $round->id . "/edit") !!}" >
  				<i class="fa fa-edit"></i>
  				{!! trans('messages.edit') !!}
  			</a>
        @endpermission
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
		<div class="custom-callout custom-callout-midnight-blue gem-h5">
			<strong>
				<p>{!! trans('messages.name').': ' !!}<span class="text-primary">{!! $round->name !!}</span></p>
				<p>{!! trans('messages.description').': ' !!}<span class="text-default">{!! $round->description !!}</span></p>
				<p>{!! trans('messages.start-date').': ' !!}<span class="text-primary">{!! $round->start_date !!}</span></p>
				<p>{!! trans('messages.end-date').': ' !!}<span class="text-primary">{!! $round->end_date !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
