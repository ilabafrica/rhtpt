@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.role', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-role">
    <!-- Role Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.role', 2) !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-role">
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.add') !!}
                    </button>
                @endpermission
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query" v-on:keyup.enter="search()">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button" @click="search()" v-if="!loading"><i class="fa fa-search"></i></button>
                        <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr v-for="role in roles" v-bind:class="{ 'text-muted': role.deleted_at}">
            <td>@{{ role.name }}</td>
            <td>@{{ role.description }}</td>
            <td>
                <button v-if="!role.deleted_at" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="role.deleted_at" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>	
            @permission('update-role')
                <button v-bind="{ 'disabled': role.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editRole(role)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-role')
                <button v-if="role.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreRole(role)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-role')
                <button v-if="!role.deleted_at" class="btn btn-sm btn-alizarin" @click.prevent="deleteRole(role)"><i class="fa fa-power-off"></i> Disable</button>
            @endpermission
            </td>
        </tr>
    </table>
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
    <!-- Create Role Modal -->
    <div id="create-role" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Role</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createRole('create_role')" data-vv-validate="create_role" data-vv-scope="create_role">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_role.name') }" for="name">Title:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_role.name') }" name="name" type="text" placeholder="" v-model="newRole.name" />
                                        <span v-show="errors.has('create_role.name')" class="help is-danger">@{{ errors.first('create_role.name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_role.display name') }" for="display name">Display Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_role.display name') }" name="display name" type="text" placeholder="" v-model="newRole.display_name" />
                                        <span v-show="errors.has('create_role.display name')" class="help is-danger">@{{ errors.first('create_role.display name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="newRole.description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                    <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="edit-role" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Role</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateRole(fillRole.id)">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('name') }" for="name">Title:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('name') }" name="name" type="text" placeholder="" v-model="fillRole.name" />
                                    <span v-show="errors.has('name')" class="help is-danger">@{{ errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('display name') }" for="display name">Display Name:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('display name') }" name="display name" type="text" placeholder="" v-model="fillRole.display_name" />
                                    <span v-show="errors.has('display name')" class="help is-danger">@{{ errors.first('display name') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                <div class="col-sm-8">
                                    <textarea name="description" class="form-control" v-model="fillRole.description"></textarea>
                                </div>
                            </div>

                            <div class="form-group row col-sm-offset-4 col-sm-8">
                                <button class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection