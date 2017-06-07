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
                <button class="btn btn-sm btn-primary" @click.prevent="editLot(lot)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('delete-lot')
                <button class="btn btn-sm btn-danger" @click.prevent="deleteLot(lot)"><i class="fa fa-power-off"></i> Disable</button>
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
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="newLot.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Lot No.:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="lot" v-model="newLot.lot">
                                        <option selected></option>
                                        <option v-for="lt in [1,2,3,4,5]" :value="lt">@{{ lt }}</option>   
                                    </select>
                                    <span v-if="formErrors['lot']" class="error text-danger">@{{ formErrors['lot'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tester ID:</label>
                                <div class="col-sm-8">
                                    <div class="form-checkbox checkbox-inline" v-for="option in [0,1,2,3,4,5,6,7,8,9]">
                                        <label class="form-checkbox-label">
                                            <input type="checkbox" :value="option" v-model="newLot.tester_id">
                                            @{{ option }}
                                        </label>
                                    </div>
                                    <span v-if="formErrors['tester_id']" class="error text-danger">@{{ formErrors['tester_id'] }}</span>
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
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="fillLot.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrorsUpdate['round_id']" class="error text-danger">@{{ formErrorsUpdate['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Lot No.:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="lot" v-model="fillLot.lot">
                                        <option selected></option>
                                        <option v-for="lt in [1,2,3,4,5]" :value="lt">@{{ lt }}</option>   
                                    </select>
                                    <span v-if="formErrorsUpdate['lot']" class="error text-danger">@{{ formErrorsUpdate['lot'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tester ID:</label>
                                <div class="col-sm-8">
                                    <div class="form-checkbox checkbox-inline" v-for="option in [0,1,2,3,4,5,6,7,8,9]">
                                        <label class="form-checkbox-label">
                                            <input type="checkbox" :value="option" v-model="fillLot.tester_id" :checked="fillLot.tester_id.includes(option)">
                                            @{{ option }}
                                        </label>
                                    </div>
                                    <span v-if="formErrorsUpdate['tester_id']" class="error text-danger">@{{ formErrorsUpdate['tester_id'] }}</span>
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