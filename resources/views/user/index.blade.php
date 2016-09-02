@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('menu.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('menu.access-control') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('menu.user', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
			    <i class="fa fa-book"></i> {!! trans_choice('menu.user', 2) !!}
			    <span>
				    <a class="btn btn-sm btn-belize-hole" href="{!! url("user/create") !!}" >
						<i class="fa fa-plus-circle"></i>
						{!! trans('messages.add') !!}
					</a>
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
	            <div class="alert alert-danger alert-dismissible" role="alert">
	                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
	                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
	            </div>
	            @endif
			 	<table class="table table-bordered table-sm search-table" id="example">
					<thead>
						<tr>
							<th>{!! trans('messages.full-name') !!}</th>
							<th>{!! trans('messages.username') !!}</th>
							<th>{!! trans('messages.gender') !!}</th>
							<th>{!! trans('messages.email') !!}</th>
							<th>{!! trans_choice('menu.role', 1) !!}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@foreach($users as $key => $value)
						<tr @if(session()->has('active_user'))
			                    {!! (session('active_user') == $value->id)?"class='warning'":"" !!}
			                @endif
			                >
							<td>{!! $value->name !!}</td>
							<td>{!! $value->username !!}</td>
							<td>{!! ($value->gender == 0) ? "Male":"Female" !!}</td>
							<td>{!! $value->email !!}</td>
							<td></td>

							<td>

							<!-- show the test category (uses the show method found at GET /user/{id} -->
								<a class="btn btn-sm btn-success" href="{!! url("user/" . $value->id) !!}" >
									<i class="fa fa-folder-open-o"></i>
									{!! trans('messages.view') !!}
								</a>

							<!-- edit this test category (uses edit method found at GET /user/{id}/edit -->
								<a class="btn btn-sm btn-info" href="{!! url("user/" . $value->id . "/edit") !!}" >
									<i class="fa fa-edit"></i>
									{!! trans('messages.edit') !!}
								</a>

							<!-- delete this test category (uses delete method found at GET /user/{id}/delete -->
								<button class="btn btn-sm btn-danger delete-item-link"
									data-toggle="modal" data-target=".confirm-delete-modal"
									data-id='{!! url("user/" . $value->id . "/delete") !!}'>
									<i class="fa fa-trash-o"></i>
									{!! trans('messages.delete') !!}
								</button>
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
