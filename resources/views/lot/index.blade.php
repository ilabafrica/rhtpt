@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.lot', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-lot">
    <!-- Lot Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.lot', 2) !!}
                @permission('create-lot')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-lot">
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
                        <button class="btn btn-secondary" type="button" @click="search" v-if="!loading"><i class="fa fa-search"></i></button>
                        <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Round</th>
            <th>Lot</th>
            <th>Tester IDs</th>
            <th>Total Participants</th>
            <th>Action</th>
        </tr>
        <tr v-for="lot in lots">
            <td>@{{ lot.rnd }}</td>
            <td>@{{ lot.lot }}</td>
            <td>@{{ lot.tester_id }}</td>
            <td></td>
            <td>
            @permission('update-lot')	
                <button v-bind="{ 'disabled': lot.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editLot(lot)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-lot')
                <button v-if="lot.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreLot(lot)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-lot')
                <button v-if="!lot.deleted_at" class="btn btn-sm btn-danger" @click.prevent="deleteLot(lot)"><i class="fa fa-power-off"></i> Disable</button>
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

    <!-- Create Lot Modal -->
    <div class="modal fade" id="create-lot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Lot</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createLot">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('pt round') }" for="round">PT Round:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="pt round" :class="{'input': true, 'is-danger': errors.has('pt round') }" v-model="newLot.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-show="errors.has('pt round')" class="help is-danger">@{{ errors.first('pt round') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('lot no.') }" for="lot">Lot No.:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="lot no." :class="{'input': true, 'is-danger': errors.has('lot no.') }" v-model="newLot.lot">
                                        <option selected></option>
                                        <option v-for="lt in [1,2,3,4,5]" :value="lt">@{{ lt }}</option>  
                                    </select>
                                    <span v-show="errors.has('lot no.')" class="help is-danger">@{{ errors.first('lot no.') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('tester id') }" for="tester id">Tester ID:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <div class="form-checkbox checkbox-inline" v-for="option in [0,1,2,3,4,5,6,7,8,9]">
                                        <label class="form-checkbox-label">
                                            <input v-validate="'required'" type="checkbox" name="tester id" :value="option" :class="{'input': true, 'is-danger': errors.has('tester id') }" v-model="newLot.tester_id">
                                            @{{ option }}
                                        </label>
                                    </div>
                                    <span v-show="errors.has('tester id')" class="help is-danger">@{{ errors.first('tester id') }}</span>
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

    <!-- Edit Lot Modal -->
    <div class="modal fade" id="edit-lot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Lot</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateLot(fillLot.id)">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('pt round') }" for="round">PT Round:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="pt round" :class="{'input': true, 'is-danger': errors.has('pt round') }" v-model="fillLot.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-show="errors.has('pt round')" class="help is-danger">@{{ errors.first('pt round') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('lot no.') }" for="lot">Lot No.:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="lot no." :class="{'input': true, 'is-danger': errors.has('lot no.') }" v-model="fillLot.lot">
                                        <option selected></option>
                                        <option v-for="lt in [1,2,3,4,5]" :value="lt">@{{ lt }}</option>  
                                    </select>
                                    <span v-show="errors.has('lot no.')" class="help is-danger">@{{ errors.first('lot no.') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('tester id') }" for="tester id">Tester ID:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <div class="form-checkbox checkbox-inline" v-for="option in [0,1,2,3,4,5,6,7,8,9]">
                                        <label class="form-checkbox-label">
                                            <input v-validate="'required'" type="checkbox" name="tester id" :value="option" :class="{'input': true, 'is-danger': errors.has('tester id') }" v-model="fillLot.tester_id">
                                            @{{ option }}
                                        </label>
                                    </div>
                                    <span v-show="errors.has('tester id')" class="help is-danger">@{{ errors.first('tester id') }}</span>
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