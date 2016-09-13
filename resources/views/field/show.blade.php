@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li><a href="{!! route('field.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.field', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $field->name !!}</strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("field/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.add') !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("field/" . $field->id . "/edit") !!}" >
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
				<p>{!! trans('messages.name').': ' !!}<span class="text-primary">{!! $field->name !!}</span></p>
				<p>{!! trans('messages.label').': ' !!}<span class="text-primary">{!! $field->label !!}</span></p>
				<p>{!! trans('messages.order').': ' !!}<span class="text-default">{!! $field->order($field->order) !!}</span></p>
        <p>{!! trans('messages.tag').': ' !!}<span class="text-default">{!! $field->tag($field->tag) !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
