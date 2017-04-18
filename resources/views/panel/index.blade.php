@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.panel', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-panel">
    <!-- panel Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.panel', 2) !!}
        
                @permission('create-panel')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-panel">
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
            <th>PT Sample ID</th>
            <th>Expected Result</th>
            <th>Prepared By</th>
            <th>Tested By</th>
            <th>Action</th>
        </tr>
        <tr v-for="panel in panels">
            <td>@{{ panel.sample }}</td>
            <td>@{{ panel.rslt }}</td>
            <td>@{{ panel.prepared_by }}</td>
            <td>@{{ panel.tested_by }}</td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editPanel(panel)"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deletePanel(panel)"><i class="fa fa-trash-o"></i> Delete</button>
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

    <!-- Create panel Modal -->
    <div class="modal fade" id="create-panel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Panel</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createPanel">

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Lot:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="lot_id" v-model="newPanel.lot_id">
                                        <option selected></option>
                                        <option  v-for="lot in lots" :value="lot.id">@{{ lot.value }}</option>
                                    </select>
                                    <span v-if="formErrors['lot']" class="error text-danger">@{{ formErrors['lot'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Panel ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="panel" v-model="newPanel.panel">
                                        <option selected></option>
                                        <option  v-for="panel in [1,2,3,4,5,6]" :value="panel">@{{ panel }}</option>
                                    </select>
                                    <span v-if="formErrors['panel']" class="error text-danger">@{{ formErrors['panel'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Material:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="material_id" v-model="newPanel.material_id">
                                        <option selected></option>
                                        <option  v-for="material in materials" :value="material.id">@{{ material.value }}</option>
                                    </select>
                                    <span v-if="formErrors['material_id']" class="error text-danger">@{{ formErrors['material_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="prepared_by" class="form-control" v-model="newPanel.prepared_by" />
                                    <span v-if="formErrors['prepared_by']" class="error text-danger">@{{ formErrors['prepared_by'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Expected Result:</label>
                                <div class="col-sm-8">
                                    <div class="form-radio radio-inline" v-for="option in options">
                                        <label class="form-radio-label">
                                            <input type="radio" :value="option.id" v-model="newPanel.result" name="result">
                                            @{{ option.value }}
                                        </label>
                                    </div>
                                    <span v-if="formErrors['result']" class="error text-danger">@{{ formErrors['result'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tested By:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="tested_by" class="form-control" v-model="newPanel.tested_by" />
                                    <span v-if="formErrors['tested_by']" class="error text-danger">@{{ formErrors['tested_by'] }}</span>
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

    <!-- Edit Panel Modal -->
    <div class="modal fade" id="edit-panel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Panel</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updatePanel(fillPanel.id)">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Identifier:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="pt_id" class="form-control" v-model="fillPanel.pt_id" />
                                    <span v-if="formErrorsUpdate['pt_id']" class="error text-danger">@{{ formErrorsUpdate['pt_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Panel ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="panel" v-model="fillPanel.panel">
                                        <option selected></option>
                                        <option  v-for="panel in panels" :value="panel.id">@{{ panel.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['panel']" class="error text-danger">@{{ formErrorsUpdate['panel'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tester ID Range:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="tester_id_range" v-model="fillPanel.tester_id_range">
                                        <option selected></option>
                                        <option  v-for="range in ranges" :value="range.id">@{{ range.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['tester_id_range']" class="error text-danger">@{{ formErrorsUpdate['tester_id_range'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Material:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="material_id" v-model="fillPanel.material_id">
                                        <option selected></option>
                                        <option v-for="material in materials" :value="material.id">@{{ material.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['material_id']" class="error text-danger">@{{ formErrorsUpdate['material_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="fillPanel.round_id">
                                        <option v-for="round in rounds" :value="round.id" :selected="(fillPanel.round_id == round.id)">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrorsUpdate['round_id']" class="error text-danger">@{{ formErrorsUpdate['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="prepared_by" class="form-control" v-model="fillPanel.prepared_by" />
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