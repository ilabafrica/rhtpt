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
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-material">
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
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createMaterial">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Batch No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch" class="form-control" v-model="newMaterial.batch" />
                                        <span v-if="formErrors['batch']" class="error text-danger">@{{ formErrors['batch'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Prepared:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_prepared" class="form-control" v-model="newMaterial.date_prepared" />
                                        <span v-if="formErrors['date_prepared']" class="error text-danger">@{{ formErrors['date_prepared'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Expiry Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="expiry_date" class="form-control" v-model="newMaterial.expiry_date" />
                                        <span v-if="formErrors['expiry_date']" class="error text-danger">@{{ formErrors['expiry_date'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Material Type:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="option.name" v-model="newMaterial.material_type" name="material_type">
                                                @{{ option.title }}
                                            </label>
                                        </div>
                                        <span v-if="formErrors['material_type']" class="error text-danger">@{{ formErrors['material_type'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Original Source:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="original_source" class="form-control" v-model="newMaterial.original_source" />
                                        <span v-if="formErrors['original_source']" class="error text-danger">@{{ formErrors['original_source'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Collected:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_collected" class="form-control" v-model="newMaterial.date_collected" />
                                        <span v-if="formErrors['date_collected']" class="error text-danger">@{{ formErrors['date_collected'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="prepared_by" class="form-control" v-model="newMaterial.prepared_by" />
                                        <span v-if="formErrors['prepared_by']" class="error text-danger">@{{ formErrors['prepared_by'] }}</span>
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
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateMaterial(fillMaterial.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Batch No:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="batch" class="form-control" v-model="fillMaterial.batch" />
                                        <span v-if="formErrorsUpdate['batch']" class="error text-danger">@{{ formErrorsUpdate['batch'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Prepared:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_prepared" class="form-control" v-model="fillMaterial.date_prepared" />
                                        <span v-if="formErrorsUpdate['date_prepared']" class="error text-danger">@{{ formErrorsUpdate['date_prepared'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Expiry Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="expiry_date" class="form-control" v-model="fillMaterial.expiry_date" />
                                        <span v-if="formErrorsUpdate['expiry_date']" class="error text-danger">@{{ formErrorsUpdate['expiry_date'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Material Type:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio form-radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="option.name" v-model="fillMaterial.material_type" name="material_type">
                                                @{{ option.title }}
                                            </label>
                                        </div>
                                        <span v-if="formErrorsUpdate['material_type']" class="error text-danger">@{{ formErrorsUpdate['material_type'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Original Source:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="original_source" class="form-control" v-model="fillMaterial.original_source" />
                                        <span v-if="formErrorsUpdate['original_source']" class="error text-danger">@{{ formErrorsUpdate['original_source'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Collected:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_collected" class="form-control" v-model="fillMaterial.date_collected" />
                                        <span v-if="formErrorsUpdate['date_collected']" class="error text-danger">@{{ formErrorsUpdate['date_collected'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="prepared_by" class="form-control" v-model="fillMaterial.prepared_by" />
                                        <span v-if="formErrorsUpdate['prepared_by']" class="error text-danger">@{{ formErrorsUpdate['prepared_by'] }}</span>
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