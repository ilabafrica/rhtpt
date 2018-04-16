@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> Designations</li>
        </ol>
    </div>
</div>
<div class="" id="manage-des">
    <!-- Designations Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> Designations
        
                @permission('create-program')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-designation">
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
        <tr v-for="designation in designations">
            <td>@{{ designation.name }}</td>
            <td>@{{ designation.description }}</td>
            <td>
                <button v-if="!designation.deleted_at" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="designation.deleted_at" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>
            @permission('update-program')	
                <button v-bind="{ 'disabled': designation.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editDesignation(designation)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-program')
                <button v-if="designation.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreDesignation(designation)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-program')
                <button v-if="!designation.deleted_at" class="btn btn-sm btn-alizarin" @click.prevent="deleteDesignation(designation)"><i class="fa fa-power-off"></i> Disable</button>
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

    <!-- Create Designation Modal -->
    <div class="modal fade" id="create-designation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Designation</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createDesignation('create_designation')" data-vv-scope="create_designation">

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_designation.title') }" for="title">Title:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_designation.title') }" name="title" type="text" placeholder="" v-model="newDesignation.name" />
                                        <span v-show="errors.has('create_designation.title')" class="help is-danger">@{{ errors.first('create_designation.title') }}</span>
                                    </div>
                                </div>
    				            <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="newDesignation.description"></textarea>
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

    <!-- Edit Designation Modal -->
    <div class="modal fade" id="edit-designation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Designation</h4>
                </div>
                <div class="row">
                    <div class="modal-body">

                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateDesignation(fillDesignation.id, 'update_designation')" data-vv-scope="update_designation">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('update_designation.title') }" for="title">Title:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('update_designation.title') }" name="title" type="text" placeholder="" v-model="fillDesignation.name" />
                                        <span v-show="errors.has('update_designation.title')" class="help is-danger">@{{ errors.first('update_designation.title') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="fillDesignation.description"></textarea>
                                        <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
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
</div>
@endsection