@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans('messages.assign-roles') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-assignments">
    <!-- Round Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans('messages.assign-roles') !!}
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
        </div>
    </div>
    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createAssignment" id="update_assignments">
        <table class="table table-bordered">
            <tr>
                <th>Users</th>
                <th colspan="@{{roles.length}}">Roles</th>
            </tr>
            <tr>
                <td></td>

                <td v-for="role in roles" v-if="role.id!=1 && role.id!=2">@{{ role.name }}</td>
            </tr>
            <tr v-for="user in users">
                <td>@{{ user.name }}</td>
                <td v-for="role in roles" v-if="role.id!=1 && role.id!=2">
                    <label v-if="role.id!=1" class="form-checkbox-label">
                        <input type="checkbox" value="@{{role.id}}" name="userRoles[@{{user.id}}][@{{role.id}}]" id="rolechecked_@{{user.id}}_@{{role.id}}" v-bind="{ 'checked': checks[user.id][role.id].checked}" v-on:change='checkedRole(role.id)'>
                    </label>
                        <!-- <div v-if="checkedRole[@{{user.id}}] == 3" class="shhde"> -->
                        <!-- Display Partners form assign.js loadPartners function -->
                       <div id="partner" class="collapse" v-if="role.id==3">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Partner:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="partner_@{{user.id}}_@{{role.id}}" id="partner">
                                        <option selected></option>
                                        <option v-for="partner in partners" value="@{{partner.id}}">@{{partner.value }}</option>
                                    </select>
                                </div>
                            </div>                   
                        </div>
                         <!-- Display Counties form assign.js loadCounties function -->                    
                       <!-- <div v-if="checkedRole[@{{user.id}}][@{{role.id}}] == 4" class="shhde"> -->
                        <div id="county" class="collapse" v-if="role.id==4">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">County:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="county_@{{user.id}}_@{{role.id}}" id="county"  v-on:change ="loadFacilities">
                                        <option selected></option>
                                        <option v-for="county in counties" value="@{{county.id}}">@{{ county.value }}</option>
                                    </select>
                                </div>
                            </div>                       
                        </div>
                </td>
            </tr>
        </table>
        <div class="form-group row col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
            <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
        </div>
    </form>
    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <li v-if="pagination.current_page > 1" class="page-item">
                <a class="page-link" href="#" aria-label="Previous"
                    @click.prevent="changePage(pagination.current_page - 1)">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li v-for="page in pagesNumber" class="page-item"
                v-bind:class="[ page == isActived ? 'active' : '']">
                <a class="page-link" href="#"
                    @click.prevent="changePage(page)">@{{ page }}</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page" class="page-item">
                <a class="page-link" href="#" aria-label="Next"
                    @click.prevent="changePage(pagination.current_page + 1)">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
@endsection