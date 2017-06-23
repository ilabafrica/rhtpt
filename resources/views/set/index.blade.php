@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.field-set', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-set">
    <!-- Field Set Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.field-set', 2) !!}
        
                @permission('create-set')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-set">
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
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query">
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
            <th>Order</th>
            <th>Action</th>
        </tr>
        <tr v-for="set in sets">
            <td>@{{ set.title }}</td>
            <td>@{{ set.description }}</td>
            <td>@{{ set.ordr }}</td>
            <td>
            @permission('update-set')	
                <button v-bind="{ 'disabled': set.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editSet(set)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-set')
                <button v-if="set.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreSet(set)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-set')
                <button v-if="!set.deleted_at" class="btn btn-sm btn-alizarin" @click.prevent="deleteSet(set)"><i class="fa fa-power-off"></i> Disable</button>
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

    <!-- Create Set Modal -->
    <div class="modal fade" id="create-set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Field Set</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createSet">

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('title') }" for="title">Title:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha'" class="form-control" :class="{'input': true, 'is-danger': errors.has('title') }" name="title" type="text" placeholder="" v-model="newSet.title" />
                                        <span v-show="errors.has('title')" class="help is-danger">@{{ errors.first('title') }}</span>
                                    </div>
                                </div>
    				            <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="newSet.description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('order') }" for="order">Order:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <select v-validate="'required'" class="form-control c-select" name="order" :class="{'input': true, 'is-danger': errors.has('order') }" v-model="newSet.order">
                                            <option selected></option>
                                            <option v-for="ordr in ordrs" :value="ordr.id">@{{ ordr.value }}</option>
                                        </select>
                                        <span v-show="errors.has('order')" class="help is-danger">@{{ errors.first('order') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                    <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                                </div>
                            </div>

                        </form>
                    </div>
                
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Set Modal -->
    <div class="modal fade" id="edit-set" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Set</h4>
                </div>
                <div class="row">
                    <div class="modal-body">

                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateSet(fillSet.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('title') }" for="title">Title:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha'" class="form-control" :class="{'input': true, 'is-danger': errors.has('title') }" name="title" type="text" placeholder="" v-model="fillSet.title" />
                                        <span v-show="errors.has('title')" class="help is-danger">@{{ errors.first('title') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="fillSet.description"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('order') }" for="order">Order:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <select v-validate="'required'" class="form-control c-select" name="order" :class="{'input': true, 'is-danger': errors.has('order') }" v-model="fillSet.order">
                                            <option selected></option>
                                            <option v-for="ordr in ordrs" :value="ordr.id">@{{ ordr.value }}</option>
                                        </select>
                                        <span v-show="errors.has('order')" class="help is-danger">@{{ errors.first('order') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
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