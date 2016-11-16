@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.facility-catalog') !!}</li>
            <li><a href="{!! route('facility.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.facility', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $facility->name !!}</strong>
	    <span>
        @permission('create-facility')
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("facility/create") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.add') !!}
  			</a>
        @endpermission
        @permission('update-facility')
  			<a class="btn btn-sm btn-info" href="{!! url("facility/" . $facility->id . "/edit") !!}" >
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
				<p>{!! trans('messages.code').': ' !!}<span class="text-primary">{!! $facility->code !!}</span></p>
				<p>{!! trans('messages.name').': ' !!}<span class="text-primary">{!! $facility->name !!}</span></p>
				<p>{!! trans_choice('messages.sub-county', 1).': ' !!}<span class="text-default">{!! $facility->subCounty->name !!}</span></p>
        <p>{!! trans('messages.mailing-address').': ' !!}<span class="text-primary">{!! $facility->mailing_address !!}</span></p>
        <p>{!! trans('messages.in-charge').': ' !!}<span class="text-primary">{!! $facility->in_charge !!}</span></p>
        <p>{!! trans('messages.phone').': ' !!}<span class="text-primary">{!! $facility->in_charge_phone !!}</span></p>
        <p>{!! trans('messages.email').': ' !!}<span class="text-primary">{!! $facility->in_charge_email !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
