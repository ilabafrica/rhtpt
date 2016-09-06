@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li><a href="{!! route('material.index') !!}"><i class="fa fa-cube"></i> {!! trans('messages.sample-preparation') !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $material->name !!}</strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("material/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.add') !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("material/" . $material->id . "/edit") !!}" >
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
				<p>{!! trans('messages.batch').': ' !!}<span class="text-primary">{!! $material->batch !!}</span></p>
				<p>{!! trans('messages.date-prepared').': ' !!}<span class="text-primary">{!! $material->date_prepared !!}</span></p>
				<p>{!! trans('messages.expiry-date').': ' !!}<span class="text-default">{!! $material->expiry_date !!}</span></p>
        <p>{!! trans('messages.material-type').': ' !!}<span class="text-primary">{!! $material->material_type !!}</span></p>
        <p>{!! trans('messages.original-source').': ' !!}<span class="text-primary">{!! $material->original_source !!}</span></p>
        <p>{!! trans('messages.date-collected').': ' !!}<span class="text-primary">{!! $material->date_collected !!}</span></p>
        <p>{!! trans('messages.prepared-by').': ' !!}<span class="text-primary">{!! $material->prepared_by !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
