@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans('messages.sample-preparation') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-material">
    <!-- Material Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.sample-preparation', 2) !!}
        
                @permission('create-sample')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-material" >
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
            <th>Batch No</th>
            <th>Date Prepared</th>
            <th>Expiry Date</th>
            <th>Type</th>
            <th>Source</th>
            <th>Date Collected</th>
            <th>Action</th>
        </tr>
        <tr v-for="material in materials">
            <td>@{{ material.batch }}</td>
            <td>@{{ material.date_prepared }}</td>
            <td>@{{ material.expiry_date }}</td>
            <td>@{{ material.mt }}</td>
            <td>@{{ material.original_source }}</td>
            <td>@{{ material.date_collected }}</td>
            <td>
            @permission('update-sample')	
                <button class="btn btn-sm btn-primary" @click.prevent="editMaterial(material)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('delete-sample')
                <button class="btn btn-sm btn-danger" @click.prevent="deleteMaterial(material)"><i class="fa fa-power-off"></i> Disable</button>
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

    <!-- Create Material Modal -->
    <div class="modal fade" id="create-material" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Material</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createMaterial('create_material')" data-vv-validate="create_material" data-vv-scope="create_material">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('batch no.') }" for="batch no.">Batch No:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|numeric'" class="form-control" :class="{'input': true, 'is-danger': errors.has('batch no.') }" name="batch no." type="text" placeholder="" v-model="newMaterial.batch" />
                                        <span v-show="errors.has('batch no.')" class="help is-danger">@{{ errors.first('batch no.') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date prepared') }" for="date prepared">Date Prepared:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control datepicker" :class="{'input': true, 'is-danger': errors.has('date prepared') }" name="date prepared" type="text" placeholder="" v-model="newMaterial.date_prepared" />
                                        <span v-show="errors.has('date prepared')" class="help is-danger">@{{ errors.first('date prepared') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('expiry date') }" for="expiry date">Expiry Date:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control datepicker" :class="{'input': true, 'is-danger': errors.has('expiry date') }" name="expiry date" type="text" placeholder="" v-model="newMaterial.expiry_date" />
                                        <span v-show="errors.has('expiry date')" class="help is-danger">@{{ errors.first('expiry date') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('material type') }" for="material type">Material Type:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" :value="option.name" :class="{'input': true, 'is-danger': errors.has('material type') }" v-model="newMaterial.material_type" name="material type">
                                                @{{ option.title }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('material type')" class="help is-danger">@{{ errors.first('material type') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('original source') }" for="original source">Original Source:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('original source') }" name="original source" type="text" placeholder="" v-model="newMaterial.original_source" />
                                        <span v-show="errors.has('original source')" class="help is-danger">@{{ errors.first('original source') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date collected') }" for="date collected">Date Collected:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date collected') }" name="date collected" type="date" placeholder="" v-model="newMaterial.date_collected" />
                                        <span v-show="errors.has('date collected')" class="help is-danger">@{{ errors.first('date collected') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('prepared by') }" for="prepared by">Prepared By:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('prepared by') }" name="prepared by" type="text" placeholder="" v-model="newMaterial.prepared_by" />
                                        <span v-show="errors.has('prepared by')" class="help is-danger">@{{ errors.first('prepared by') }}</span>
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

    <!-- Edit Material Modal -->
    <div class="modal fade" id="edit-material" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Material</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateMaterial(fillMaterial.id, 'update_material')" data-vv-validate="update_material">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('batch no.') }" for="batch no.">Batch No:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|numeric'" class="form-control" :class="{'input': true, 'is-danger': errors.has('batch no.') }" name="batch no." type="text" placeholder="" v-model="fillMaterial.batch" />
                                        <span v-show="errors.has('batch no.')" class="help is-danger">@{{ errors.first('batch no.') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date prepared') }" for="date prepared">Date Prepared:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date prepared') }" name="date prepared" type="date" placeholder="" v-model="fillMaterial.date_prepared" />
                                        <span v-show="errors.has('date prepared')" class="help is-danger">@{{ errors.first('date prepared') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('expiry date') }" for="expiry date">Expiry Date:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('expiry date') }" name="expiry date" type="date" placeholder="" v-model="fillMaterial.expiry_date" />
                                        <span v-show="errors.has('expiry date')" class="help is-danger">@{{ errors.first('expiry date') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('material type') }" for="material type">Material Type:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" :value="option.name" :class="{'input': true, 'is-danger': errors.has('material type') }" v-model="fillMaterial.material_type" name="material type">
                                                @{{ option.title }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('material type')" class="help is-danger">@{{ errors.first('material type') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('original source') }" for="original source">Original Source:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('original source') }" name="original source" type="text" placeholder="" v-model="fillMaterial.original_source" />
                                        <span v-show="errors.has('original source')" class="help is-danger">@{{ errors.first('original source') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date collected') }" for="date collected">Date Collected:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date collected') }" name="date collected" type="date" placeholder="" v-model="fillMaterial.date_collected" />
                                        <span v-show="errors.has('date collected')" class="help is-danger">@{{ errors.first('date collected') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('prepared by') }" for="prepared by">Prepared By:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('prepared by') }" name="prepared by" type="text" placeholder="" v-model="fillMaterial.prepared_by" />
                                        <span v-show="errors.has('prepared by')" class="help is-danger">@{{ errors.first('prepared by') }}</span>
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