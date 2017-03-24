@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.pt-item', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-item">
    <!-- Item Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.pt-item', 2) !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-item">
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.add') !!}
                    </button>
                @endpermission
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a></h5>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>PT Identifier</th>
            <th>Panel</th>
            <th>Tester ID Range</th>
            <th>Material</th>
            <th>PT Round</th>
            <th>Prepared By</th>
            <th>Action</th>
        </tr>
        <tr v-for="item in items">
            <td>@{{ item.pt_id }}</td>
            <td>@{{ item.panel }}</td>
            <td>@{{ item.tstr }}</td>
            <td>@{{ item.mtrl }}</td>
            <td>@{{ item.rnd }}</td>
            <td>@{{ item.prepared_by }}</td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editItem(item)"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deleteItem(item)"><i class="fa fa-trash-o"></i> Delete</button>
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

    <!-- Create Item Modal -->
    <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Item</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createItem">

                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Identifier:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="pt_id" class="form-control" v-model="newItem.pt_id" />
                                    <span v-if="formErrors['pt_id']" class="error text-danger">@{{ formErrors['pt_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Panel ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="panel" v-model="newItem.panel">
                                        <option selected></option>
                                        <option  v-for="panel in panels" :value="panel.id">@{{ panel.value }}</option>
                                    </select>
                                    <span v-if="formErrors['panel']" class="error text-danger">@{{ formErrors['panel'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tester ID Range:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="tester_id_range" v-model="newItem.tester_id_range">
                                        <option selected></option>
                                        <option  v-for="range in ranges" :value="range.id">@{{ range.value }}</option>
                                    </select>
                                    <span v-if="formErrors['tester_id_range']" class="error text-danger">@{{ formErrors['tester_id_range'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Material:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="material_id" v-model="newItem.material_id">
                                        <option selected></option>
                                        <option  v-for="material in materials" :value="material.id">@{{ material.value }}</option>
                                    </select>
                                    <span v-if="formErrors['material_id']" class="error text-danger">@{{ formErrors['material_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="newItem.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="prepared_by" class="form-control" v-model="newItem.prepared_by" />
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

    <!-- Edit Item Modal -->
    <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Item</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateItem(fillItem.id)">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Identifier:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="pt_id" class="form-control" v-model="fillItem.pt_id" />
                                    <span v-if="formErrorsUpdate['pt_id']" class="error text-danger">@{{ formErrorsUpdate['pt_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Panel ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="panel" v-model="fillItem.panel">
                                        <option selected></option>
                                        <option  v-for="panel in panels" :value="panel.id">@{{ panel.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['panel']" class="error text-danger">@{{ formErrorsUpdate['panel'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tester ID Range:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="tester_id_range" v-model="fillItem.tester_id_range">
                                        <option selected></option>
                                        <option  v-for="range in ranges" :value="range.id">@{{ range.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['tester_id_range']" class="error text-danger">@{{ formErrorsUpdate['tester_id_range'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Material:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="material_id" v-model="fillItem.material_id">
                                        <option selected></option>
                                        <option v-for="material in materials" :value="material.id">@{{ material.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['material_id']" class="error text-danger">@{{ formErrorsUpdate['material_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="fillItem.round_id">
                                        <option v-for="round in rounds" :value="round.id" :selected="(fillItem.round_id == round.id)">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrorsUpdate['round_id']" class="error text-danger">@{{ formErrorsUpdate['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Prepared By:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="prepared_by" class="form-control" v-model="fillItem.prepared_by" />
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