@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ul class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.privilege', 2) !!}</li>
        </ul>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-book"></i> {!! trans_choice('messages.privilege', 2) !!}
	    <span>
		    <a class="btn btn-sm btn-belize-hole" href="{!! url("role/create") !!}">
				<i class="fa fa-plus-circle"></i> {!! trans_choice('messages.role', 1) !!}
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
		{!! Form::open(array('route'=>'role.assign')) !!}
	 	<table class="table table-bordered table-sm">
			<thead>
                <tr>
                    <th>{!! trans_choice('messages.user', 2) !!}</th>
                    <th colspan="{!! count($roles)!!}">{!! trans_choice('messages.role', 2) !!}</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                <td></td>
                @forelse($roles as $role)
                    <td>{!!$role->name!!}</td>
                @empty
                    <td>{!!trans('messages.no-records-found')!!}</td>
                @endforelse
            </tr>
            @forelse($users as $userKey=>$user)
                <tr>
                    <td>{!! $user->name !!}</td>
                    @forelse($roles as $roleKey=>$role)
                    <td>
                        @if ($role == App\Models\Role::getAdminRole() && $user == App\Models\User::getAdminUser())
                            <i class="fa fa-lock"></i>
                            {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name),
                            array('style'=>'display:none')) !!}
                        @else
                           {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name)) !!}
                        @endif
                    </td>
                    @empty
                        <td>[-]</td>
                    @endforelse
                </tr>
            @empty
            <tr><td colspan="2">{!!trans('messages.no-records-found')!!}</td></tr>
            @endforelse
            </tbody>
		</table>
		<div class="form-group messagess-row" align="right">
        {!! Form::button("<i class='fa fa-check-circle'></i> ".trans('messages.update'),
			array('class' => 'btn btn-primary btn-sm', 'onclick' => 'submit()')) !!}
        </div>
        {!!Form::close()!!}
  	</div>
</div>
	{!! Session(['SOURCE_URL' => URL::full()]) !!}
@endsection
