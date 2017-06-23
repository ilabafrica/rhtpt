@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.permission', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-permission">
    <!-- Round Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.permission', 2) !!}
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
        </div>
    </div>
    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createPrivilege" id="update_privileges">
        <table class="table table-bordered">
            <tr>
                <th>Permissions</th>
                <th :colspan="roles.length">Roles</th>
            </tr>
            <tr>
                <td></td>

                <td v-for="role in roles">@{{ role.name }}</td>
            </tr>
            <tr v-for="permission in permissions">
                <td>@{{ permission.name }}</td>
                <td v-for="role in roles">
                    <label v-if="role.id==1" class="form-checkbox-label">
                        <i class="fa fa-lock"></i>
                        <input type="checkbox" value="1" :name="'permissionRoles['+permission.id+']['+role.id+']'" v-bind="{ 'checked': checks[permission.id][role.id].checked}" style="display:none;">
                    </label>
                    <label v-else="role.id!=1" class="form-checkbox-label">
                        <input type="checkbox" value="1" :name="'permissionRoles['+permission.id+']['+role.id+']'" v-bind="{ 'checked': checks[permission.id][role.id].checked}">
                    </label>
                </td>
            </tr>
        </table>
        <div class="form-group row col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
            <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
        </div>
    </form>
</div>
@endsection