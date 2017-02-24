@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.result', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-result">
    <!-- Round Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.result', 2) !!}
        
                @permission('create-result')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-result">
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.enter-result') !!}
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
            <th>PT Round</th>
            <th>Participant</th>
            <th>Status</th>
            <th>Feedback</th>
            <th>Action</th>
        </tr>
        <tr v-for="result in results">
            <td>@{{ result.rnd }}</td>
            <td>@{{ result.tester }}</td>
            <td>
                <button v-if="shipment.panel_status==0" class="mbtn mbtn-raised mbtn-success mbtn-xs">Not Verified</button>
                <button v-if="shipment.panel_status==1" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Verified</button>
            </td>
            <td>
                <button v-if="shipment.feedback==0" class="mbtn mbtn-raised mbtn-success mbtn-xs">Unsatisfactory</button>
                <button v-if="shipment.feedback==1" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Satisfactory</button>
            </td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editResult(result)"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deleteResult(result)"><i class="fa fa-trash-o"></i> Delete</button>
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

    <!-- Enter Result Modal -->
    <div class="modal fade" id="create-result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Test Results</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createResult" id="test_results">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-7">
                                    <select class="form-control c-select" name="round_id">
                                        <option selected></option>
                                        <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                </div>
                            </div>
                            <div v-for="frm in form">
                                <p class="text-primary">@{{ frm.title }}</p>
                                <hr>
                                <div v-for="fld in frm.fields">
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label" for="title">@{{ fld.title }}:</label>
                                        <div class="col-sm-7">
                                            <div v-if="fld.tag == 1">
                                                <div class="form-checkbox form-checkbox-inline" v-for="option in fld.options">
                                                    <label class="form-checkbox-label">
                                                        <input type="checkbox" :value="option.id" name="field_@{{fld.id}}">
                                                        @{{ option.title }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 2">
                                                <input type="date" name="field_@{{fld.id}}" class="form-control" />
                                            </div>
                                            <div v-if="fld.tag == 3">
                                                <input type="email" name="field_@{{fld.id}}" class="form-control" />
                                            </div>
                                            <div v-if="fld.tag == 4">
                                                <input type="text" name="field_@{{fld.id}}" class="form-control" />
                                            </div>
                                            <div v-if="fld.tag == 5">
                                                <div class="form-radio form-radio-inline" v-for="option in fld.options">
                                                    <label class="form-radio-label">
                                                        <input type="radio" :value="option.id" name="field_@{{fld.id}}" @change="remark('.toggle_@{{fld.id}}', this)">
                                                        @{{ option.title }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 6">
                                                <select class="form-control c-select" name="field_@{{fld.id}}">
                                                    <option selected></option>
                                                    <option v-for="option in fld.options" :value="option.id">@{{ round.title }}</option>   
                                                </select>
                                            </div>
                                            <div v-if="fld.tag == 7">
                                                <textarea name="field_@{{fld.id}}" class="form-control"></textarea>
                                            </div>
                                            <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row toggle_@{{fld.id}}" style="display:none;">
                                    <label class="col-sm-5 form-control-label text-danger font-weight-bold" for="title">Please Specify:</label>
                                    <div class="col-sm-7">
                                        <textarea name="field_@{{fld.id}}" class="form-control" v-model="formInputs.field_@{{fld.id}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row col-sm-offset-5 col-sm-7">
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

    <!-- Edit Test Results Modal -->
    <div class="modal fade" id="edit-result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Test Results</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateResult" id="update_test_results">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-5 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-7">
                                    <select class="form-control c-select" name="round_id">
                                        <option selected></option>
                                        <option v-for="round in rounds" v-bind="{ 'selected': round.id==frmData.pt.round_id}" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                </div>
                            </div>
                            <div v-for="frm in form">
                                <p class="text-primary">@{{ frm.title }}</p>
                                <hr>
                                <div v-for="fld in frm.fields">
                                    <div class="form-group row">
                                        <label class="col-sm-5 form-control-label" for="title">@{{ fld.title }}:</label>
                                        <div class="col-sm-7">
                                            <div v-if="fld.tag == 1">
                                                <div class="form-checkbox form-checkbox-inline" v-for="option in fld.options">
                                                    <label class="form-checkbox-label">
                                                        <input type="checkbox" :value="option.id" name="field_@{{fld.id}}">
                                                        @{{ option.title }}
                                                    </label>
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 2">
                                                <div v-for="dt in frmData.results">
                                                    <input v-if="dt.field_id==fld.id" type="date" name="field_@{{fld.id}}" class="form-control" value="@{{dt.response}}" />
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 3">
                                                <div v-for="dt in frmData.results">
                                                    <input v-if="dt.field_id==fld.id" type="email" name="field_@{{fld.id}}" class="form-control" value="@{{dt.response}}" />
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 4">
                                                <div v-for="dt in frmData.results">
                                                    <input v-if="dt.field_id==fld.id" type="text" name="field_@{{fld.id}}" class="form-control" value="@{{dt.response}}" />
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 5">
                                                <div v-for="dt in frmData.results">
                                                    <div v-if="dt.field_id==fld.id" class="form-radio form-radio-inline" v-for="option in fld.options">
                                                        <label class="form-radio-label">
                                                            <input type="radio" v-bind="{ 'true': option.id==dt.response}" :value="option.id" name="field_@{{fld.id}}" @change="remark('.toggle_@{{fld.id}}', this)">
                                                            @{{ option.title }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 6">
                                                <div v-for="dt in frmData.results">
                                                    <select v-if="dt.field_id==fld.id" class="form-control c-select" name="field_@{{fld.id}}">
                                                        <option selected></option>
                                                        <option v-for="option in fld.options" v-bind="{ 'selected': option.id==dt.response}" :value="option.id">@{{ round.title }}</option>   
                                                    </select>
                                                </div>
                                            </div>
                                            <div v-if="fld.tag == 7">
                                                <div v-for="dt in frmData.results">
                                                    <textarea v-if="dt.field_id==fld.id" name="field_@{{fld.id}}" class="form-control" value="@{{dt.response}}"></textarea>
                                                </div>
                                            </div>
                                            <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row toggle_@{{fld.id}}" style="display:none;">
                                    <label class="col-sm-5 form-control-label text-danger font-weight-bold" for="title">Please Specify:</label>
                                    <div class="col-sm-7">
                                        <textarea name="field_@{{fld.id}}" class="form-control" v-model="formInputs.field_@{{fld.id}}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row col-sm-offset-5 col-sm-7">
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