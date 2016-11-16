@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li><a href="{!! route('item.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('messages.pt-item', 2) !!}</a></li>
            <li class="active">{!! trans('messages.view') !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-file-text"></i> <strong>{!! $item->name !!}</strong>
	    <span>
        @permission('create-item')
	    	<a class="btn btn-sm btn-belize-hole" href="{!! url("item/create") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.add') !!}
  			</a>
        @endpermission
        @permission('update-item')
  			<a class="btn btn-sm btn-info" href="{!! url("item/" . $item->id . "/edit") !!}" >
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
				<p>{!! trans('messages.pt-id').': ' !!}<span class="text-primary">{!! $item->pt_id !!}</span></p>
				<p>{!! trans('messages.tester-id-range').': ' !!}<span class="text-default">{!! $item->program->name !!}</span></p>
				<p>{!! trans('messages.material').': ' !!}<span class="text-primary">{!! $item->material->material($item->material->material_type) !!}</span></p>
				<p>{!! trans_choice('messages.pt-round', 1).': ' !!}<span class="text-primary">{!! $item->round->name !!}</span></p>
        <p>{!! trans('messages.prepared-by').': ' !!}<span class="text-primary">{!! $item->user->name !!}</span></p>
			</strong>
		</div>
	</div>
</div>
@endsection
