@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('menu.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('menu.access-control') !!}</li>
            <li><a href="{!! route('user.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('menu.user', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view').' '.trans_choice('menu.user', 1) !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $user->name !!}</strong>
	    <span>
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("user/create") !!}" >
				<i class="fa fa-plus-circle"></i>
				{!! trans('messages.add') !!}
			</a>
			<a class="btn btn-sm btn-info" href="{!! url("user/" . $user->id . "/edit") !!}" >
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
				<p>{!! trans('messages.name').': ' !!}<span class="text-primary">{!! $user->name !!}</span></p>
				<p>{!! trans('messages.username').': ' !!}<span class="text-muted">{!! $user->username !!}</span></p>
				<p>{!! trans('messages.gender').': ' !!}<span class="text-muted">{!! $user->gender==App\Models\User::MALE?trans('messages.male'):trans('messages.female') !!}</span></p>
				<p>{!! trans('messages.phone').': ' !!}<span class="text-muted">{!! $user->phone !!}</span></p>
				<p>{!! trans('messages.email').': ' !!}<span class="text-muted">{!! $user->email !!}</span></p>
				<p>{!! trans('messages.address').': ' !!}<span class="text-muted">{!! $user->address !!}</span></p>
				<p>{!! trans_choice('menu.role', 1).': ' !!}<span class="text-muted">{!! $user->username !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
