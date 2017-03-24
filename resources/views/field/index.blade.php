@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.program-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.field', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-field">
    <!-- Field Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.field', 2) !!}
        
                @permission('create-field')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-field" >
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
            <th>UID</th>
            <th>Title</th>
            <th>Tag</th>
            <th>Order</th>
            <th>Action</th>
        </tr>
        <tr v-for="field in fields">
            <td>@{{ field.uid }}</td>
            <td>@{{ field.title }}</td>
            <td>@{{ field.tg }}</td>
            <td>@{{ field.ordr }}</td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editField(field)" disabled><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deleteField(field)"><i class="fa fa-trash-o"></i> Delete</button>
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

    <!-- Create Field Modal -->
    <div class="modal fade" id="create-field" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Field</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createField" id="create_field">

                        <div class="col-md-12">
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" class="form-control" />
                                    <span v-if="formErrors['title']" class="error text-danger">@{{ formErrors['title'] }}</span>
                                 </div>
                            </div>
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">UID:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="uid" class="form-control" />
                                    <span v-if="formErrors['uid']" class="error text-danger">@{{ formErrors['uid'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tag:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="tag" v-model="selected">
                                        <option selected></option>
                                        <option v-for="tag in tags" :value="tag.id">@{{ tag.value }}</option>
                                    </select>
                                    <span v-if="formErrors['tag']" class="error text-danger">@{{ formErrors['tag'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Order:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="order">
                                        <option selected></option>
                                        <option v-for="fld in flds" :value="fld.id">@{{ fld.value }}</option>
                                    </select>
                                    <span v-if="formErrors['order']" class="error text-danger">@{{ formErrors['order'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Field Set:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="field_set_id">
                                        <option selected></option>
                                        <option v-for="set in sets" :value="set.id">@{{ set.value }}</option>
                                    </select>
                                    <span v-if="formErrors['field_set_id']" class="error text-danger">@{{ formErrors['field_set_id'] }}</span>
                                </div>
                            </div>
                            <div v-if="selected === 1 || selected === 5 || selected === 6" class="shhde">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Options:</label>
                                    <div class="col-sm-8">
                                        <div class="card card-block">
                                            <div class="form-checkbox form-checkbox-inline" v-for="option in options">
                                                <label class="form-checkbox-label">
                                                    <input type="checkbox" :value="option.id" name="opts[]">
                                                    @{{ option.value }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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

    <!-- Edit Field Modal -->
    <div class="modal fade" id="edit-field" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Field</h4>
            </div>
            <div class="row">
                <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateField" id="update_field">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" class="form-control" value="frmData.field.title"/>
                                    <span v-if="formErrorsUpdate['title']" class="error text-danger">@{{ formErrorsUpdate['title'] }}</span>
                                </div>
                            </div>
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">UID:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="uid" class="form-control" :value="frmData.field.uid"/>
                                    <span v-if="formErrorsUpdate['uid']" class="error text-danger">@{{ formErrorsUpdate['uid'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Tag:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="tag" v-model="selected">
                                        <option selected></option>
                                        <option v-for="tag in tags" v-bind="{ 'true': tag.id==frmData.field.tag}" :value="tag.id">@{{ tag.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['tag']" class="error text-danger">@{{ formErrorsUpdate['tag'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Order:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="order" >
                                        <option selected></option>
                                        <option v-for="fld in flds" v-bind="{ 'true': fld.id==frmData.field.order}" :value="fld.id">@{{ fld.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['order']" class="error text-danger">@{{ formErrorsUpdate['order'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Field Set:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="field_set_id">
                                        <option selected></option>
                                        <option v-for="set in sets" :value="set.id">@{{ set.value }}</option>
                                    </select>
                                    <span v-if="formErrorsUpdate['field_set_id']" class="error text-danger">@{{ formErrorsUpdate['field_set_id'] }}</span>
                                </div>
                            </div>
                            <div v-if="selected === 1 || selected === 5 || selected === 6" class="shhde">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Options:</label>
                                    <div class="col-sm-8">
                                        <div class="card card-block">
                                            <div class="form-checkbox form-checkbox-inline" v-for="option in options">
                                                <label class="form-checkbox-label">
                                                    <input type="checkbox" v-bind="{ 'true': frmData.opts.includes(option)}" :value="option.id" name="opts[]">
                                                    @{{ option.value }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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