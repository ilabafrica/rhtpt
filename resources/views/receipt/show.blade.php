@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('receipt.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.receipt', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $receipt->name !!}</strong>
	    <span>
        @permission('create-receipt')
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("receipt/create") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.add') !!}
  			</a>
        @endpermission
        @permission('update-receipt')
  			<a class="btn btn-sm btn-info" href="{!! url("receipt/" . $receipt->id . "/edit") !!}" >
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
				<p>{!! trans_choice('messages.pt-round', 1).': ' !!}<span class="text-primary">{!! $receipt->id !!}</span></p>
				<p>{!! trans('messages.date-received').': ' !!}<span class="text-default">{!! $receipt->date_received !!}</span></p>
				<p>{!! trans('messages.panels-received').': ' !!}<span class="text-default">{!! $receipt->panels_received !!}</span></p>
				<p>{!! trans('messages.condition').': ' !!}<span class="text-default">{!! $receipt->condition !!}</span></p>
        <p>{!! trans('messages.storage').': ' !!}<span class="text-default">{!! $receipt->storage !!}</span></p>
        <p>{!! trans('messages.transit-temperature').': ' !!}<span class="text-default">{!! $receipt->transit_temperature !!}</span></p>
				<p>{!! trans_choice('messages.recipient', 1).': ' !!}<span class="text-primary">{!! $receipt->recipient !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
