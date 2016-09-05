@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-roles"></i> {!! trans('messages.user-management') !!}</li>
            <li><a href="{!! route('role.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.role', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $role->name !!}</strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("role/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.new').' '.trans_choice('messages.role', 1) !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("role/" . $role->id . "/edit") !!}" >
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
				<p>{!! trans('messages.name').': ' !!}<span class="text-primary">{!! $role->name !!}</span></p>
				<p>{!! trans('messages.display-name').': ' !!}<span class="text-primary">{!! $role->display_name !!}</span></p>
				<p>{!! trans('messages.description').': ' !!}<span class="text-default">{!! $role->description !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
