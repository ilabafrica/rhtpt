@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.facility-catalog') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.facility', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-book"></i> {!! trans_choice('messages.facility', 2) !!}
	    <span>
        @permission('create-facility')
		    <a class="btn btn-sm btn-belize-hole" href="{!! url("facility/create") !!}" >
  				<i class="fa fa-plus-circle"></i>
  				{!! trans('messages.add') !!}
  			</a>
        @endpermission
  			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
  				<i class="fa fa-step-backward"></i>
  				{!! trans('messages.back') !!}
  			</a>
  		</span>
	</div>
  	<div class="card-block">
		@if (Session::has('message'))
			<div class="alert alert-info">{!! Session::get('message') !!}</div>
		@endif
		@if($errors->all())
    <div class="alert alert-danger alert-dismissible" facility="alert">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
        {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
    </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
    	 	<table class="table table-bordered table-sm search-table" id="example">
    			<thead>
    				<tr>
    					<th>{!! trans('messages.code') !!}</th>
    					<th>{!! trans('messages.name') !!}</th>
    					<th>{!! trans('messages.mailing-address') !!}</th>
              <th>{!! trans('messages.in-charge') !!}</th>
              <th>{!! trans_choice('messages.sub-county', 1) !!}</th>
              <th>{!! trans_choice('messages.county', 1) !!}</th>
              <th>{!! trans('messages.status') !!}</th>
    					<th>{!! trans('messages.action') !!}</th>
    				</tr>
    			</thead>
    			<tbody>
    			@foreach($facilities as $key => $value)
    				<tr @if(session()->has('active_facility'))
    	                    {!! (session('active_facility') == $value->id)?"class='warning'":"" !!}
    	                @endif
    	                >
    					<td>{!! $value->code !!}</td>
    					<td>{!! $value->name !!}</td>
    					<td>{!! $value->mailing_address !!}</td>
              <td>{!! $value->in_charge !!}</td>
              <td>{!! $value->subCounty->name !!}</td>
              <td>{!! $value->subCounty->county->name !!}</td>
              <td>{!! is_null($value->deleted_at)?trans('messages.active'):trans('messages.inactive') !!}</td>

    					<td>

    					<!-- show the test category (uses the show method found at GET /facility/{id} -->
                @permission('view-facility')
    						<a class="btn btn-sm btn-success" href="{!! url("facility/" . $value->id) !!}" >
    							<i class="fa fa-folder-open-o"></i>
    							{!! trans('messages.view') !!}
    						</a>
                @endpermission
    					<!-- edit this test category (uses edit method found at GET /facility/{id}/edit -->
                @permission('update-facility')
    						<a class="btn btn-sm btn-info" href="{!! url("facility/" . $value->id . "/edit") !!}" >
    							<i class="fa fa-edit"></i>
    							{!! trans('messages.edit') !!}
    						</a>
                @endpermission
    					<!-- delete this test category (uses delete method found at GET /facility/{id}/delete -->
                @permission('delete-facility')
    						<button class="btn btn-sm btn-danger delete-item-link"
    							data-toggle="modal" data-target=".confirm-delete-modal"
    							data-id='{!! url("facility/" . $value->id . "/delete") !!}'>
    							<i class="fa fa-trash-o"></i>
    							{!! trans('messages.delete') !!}
    						</button>
                @endpermission
    					</td>
    				</tr>
    			@endforeach
    			</tbody>
    		</table>
      </div>
    </div>
	</div>
</div>
{!! session(['SOURCE_URL' => URL::full()]) !!}
@endsection
