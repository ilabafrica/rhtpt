@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.expected-result', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-expected">
    <!-- Expected Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.expected-result', 2) !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-expected">
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
            <th>PT Item</th>
            <th>Expected Results</th>
            <th>Tested By</th>
            <th>Action</th>
        </tr>
        <tr v-for="expected in expecteds">
            <td>@{{ expected.itm }}</td>
            <td>@{{ expected.rslt }}</td>
            <td>@{{ expected.tested_by }}</td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editExpected(expected)"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deleteExpected(expected)"><i class="fa fa-power-off"></i> Disable</button>
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

    <!-- Create Expected Modal -->
    <div class="modal fade" id="create-expected" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Create Expected</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createExpected('create_expected')" data-vv-validate="create_expected">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">PT Item:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="item_id" v-model="newExpected.item_id">
                                            <option selected></option>
                                            <option v-for="item in items" :value="item.id">@{{ item.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['item_id']" class="error text-danger">@{{ formErrors['item_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Result:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio form-radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="option.id" v-model="newExpected.result" name="result">
                                                @{{ option.value }}
                                            </label>
                                        </div>
                                        <span v-if="formErrors['result']" class="error text-danger">@{{ formErrors['result'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Tested By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tested_by" class="form-control" v-model="newExpected.tested_by" />
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

    <!-- Edit Expected Modal -->
    <div class="modal fade" id="edit-expected" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Expected</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateExpected(fillExpected.id, 'update_expected')" data-vv-validate="update_expected">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">PT Item:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="item_id" v-model="fillExpected.item_id">
                                            <option selected></option>
                                            <option v-for="item in items" :value="item.id">@{{ item.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['item_id']" class="error text-danger">@{{ formErrorsUpdate['item_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Result:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio form-radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="option.id" v-model="fillExpected.result" name="result">
                                                @{{ option.value }}
                                            </label>
                                        </div>
                                        <span v-if="formErrorsUpdate['result']" class="error text-danger">@{{ formErrorsUpdate['result'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Tested By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tested_by" class="form-control" v-model="fillExpected.tested_by" />
                                        <span v-if="formErrorsUpdate['tested_by']" class="error text-danger">@{{ formErrorsUpdate['tested_by'] }}</span>
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