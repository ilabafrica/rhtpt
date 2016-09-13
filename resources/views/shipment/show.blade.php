@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('shipment.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.shipment', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $shipment->name !!}</strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("shipment/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.add') !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("shipment/" . $shipment->id . "/edit") !!}" >
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
		<div class="custom-callout custom-callout-midnight-blue gem-h5">
			<strong>
				<p>{!! trans_choice('messages.pt-round', 1).': ' !!}<span class="text-primary">{!! $shipment->round->name !!}</span></p>
				<p>{!! trans('messages.date-prepared').': ' !!}<span class="text-default">{!! $shipment->date_prepared !!}</span></p>
				<p>{!! trans('messages.date-shipped').': ' !!}<span class="text-default">{!! $shipment->date_shipped !!}</span></p>
				<p>{!! trans_choice('messages.shipping-method', 1).': ' !!}<span class="text-default">{!! $shipment->shipping($shipment->shipping_method) !!}</span></p>
        <p>{!! trans_choice('messages.courier', 1).': ' !!}<span class="text-default">{!! $shipment->courier !!}</span></p>
        <p>{!! trans_choice('messages.participant', 1).': ' !!}<span class="text-default">{!! $shipment->part->name !!}</span></p>
				<p>{!! trans('messages.panels-shipped').': ' !!}<span class="text-primary">{!! $shipment->panels_shipped !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
