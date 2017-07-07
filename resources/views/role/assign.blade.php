@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ul class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans('messages.assign-roles') !!}</li>
        </ul>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-book"></i> {!! trans('messages.assign-roles') !!}
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
	 	<table class="table table-bordered table-sm search-table" id="example">
			<thead>
          <tr>
              <th>{!! trans_choice('messages.user', 2).' / '.trans_choice('messages.role', 2) !!}</th>
              @forelse($roles as $role)
                  <th>{!!$role->name!!}</th>
              @empty
                  <th>{!!trans('messages.no-records-found')!!}</th>
              @endforelse
          </tr>
      </thead>
      <tbody>
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
                      @if($role->id == App\Models\Role::idByName('County Lab Coordinator'))
                          {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name), array('onclick' => "county('$user->id')")) !!}
                          @if($user->id != App\Models\User::getAdminUser()->id)
                              <br />
                              <div class="kaunti{!! $user->id !!}" <?php if(!$user->hasRole('County Lab Coordinator')){ ?>style="display:none" <?php } ?>>
                                  <div class="form-group row">
                                      <div class="col-sm-12">
                                          {!! Form::select('county'.$user->id, array(''=>trans('messages.select'))+$counties, ($user->tier && $user->hasRole('County Lab Coordinator'))?$user->tier->tier:'', array('class' => 'form-control c-select')) !!}
                                      </div>
                                  </div>
                              </div>
                          @endif
                      @elseif($role->id == App\Models\Role::idByName('Partner Admin'))
                          {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name), array('onclick' => "partner('$user->id')")) !!}
                          @if($user->id != App\Models\User::getAdminUser()->id)
                              <br />
                              <div class="part{!! $user->id !!}" <?php if(!$user->hasRole('Partner Admin')){ ?>style="display:none" <?php } ?>>
                                  <div class="form-group row">
                                      <div class="col-sm-12">
                                          {!! Form::select('partner'.$user->id, array(''=>trans('messages.select'))+$partners, ($user->tier && $user->hasRole('Partner Admin'))?$user->tier->tier:'', array('class' => 'form-control c-select')) !!}
                                      </div>
                                  </div>
                              </div>
                          @endif
                      @elseif($role->id == App\Models\Role::idByName('Participant'))
                        {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name), array('onclick' => "site('$user->id')")) !!}
                        @if($user->id != App\Models\User::getAdminUser()->id)
                          <br />
                            <div class="form-group row faci{!! $user->id !!}" <?php if(!$user->hasRole('Participant')){ ?>style="display:none"<?php } ?>>
                                <div class="col-sm-12">
                                    {!! Form::select('county_'.$user->id, array(''=>trans('messages.select'))+$counties,
                                        ($user->tier && $user->hasRole('Participant'))?App\Models\Facility::find($user->tier->tier)->subCounty->county->id:'',
                                        array('class' => 'form-control c-select', 'style' => 'margin-bottom:5px;', 'id' => 'county_'.$user->id, 'onchange' => "load('$user->id')")) !!}
                                </div>
                            </div>
                            <br />
                            <div class="form-group row faci{!! $user->id !!}" <?php if(!$user->hasRole('Participant')){ ?>style="display:none"<?php } ?>>
                                <div class="col-sm-12">
                                    {!! Form::select('sub_county'.$user->id, array(''=>trans('messages.select'))+$subCounties,
                                        ($user->tier && $user->hasRole('Participant'))?App\Models\Facility::find($user->tier->tier)->subCounty->id:'',
                                        array('class' => 'form-control c-select', 'style' => 'margin-bottom:5px;', 'id' => 'sub_county'.$user->id, 'onchange' => "drill('$user->id')")) !!}
                                </div>
                            </div>
                            <br />
                            <div class="form-group row faci{!! $user->id !!}" <?php if(!$user->hasRole('Participant')){ ?>style="display:none"<?php } ?>>
                                <div class="col-sm-12">
                                    {!! Form::select('facility'.$user->id, array(''=>trans('messages.select'))+$facilities,
                                        ($user->tier && $user->hasRole('Participant'))?$user->tier->tier:'',
                                        array('class' => 'form-control c-select', 'style' => 'margin-bottom:5px;', 'id' => 'facility'.$user->id)) !!}
                                </div>
                            </div>
                            <br />
                            <div class="form-group row faci{!! $user->id !!}" <?php if(!$user->hasRole('Participant')){ ?>style="display:none"<?php } ?>>
                                <div class="col-sm-12">
                                    {!! Form::select('program'.$user->id, array(''=>trans('messages.select'))+$programs,
                                        ($user->tier && $user->hasRole('Participant'))?$user->tier->program_id:'',
                                        array('class' => 'form-control c-select', 'id' => 'program'.$user->id)) !!}
                                </div>
                            </div>
                        @endif
                      @else
                          {!! Form::checkbox('userRoles['.$userKey.']['.$roleKey.']', '1', $user->hasRole($role->name)) !!}
                      @endif
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
