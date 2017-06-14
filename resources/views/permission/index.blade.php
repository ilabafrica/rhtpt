@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.permission', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-permission">
    <!-- Round Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.permission', 2) !!}
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
        </div>
    </div>
    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createPrivilege" id="update_privileges">
        <table class="table table-bordered">
            <tr>
                <th>Permissions</th>
                <th colspan="@{{roles.length}}">Roles</th>
            </tr>
            <tr>
                <td></td>

                <td v-for="role in roles">@{{ role.name }}</td>
            </tr>
            <tr v-for="permission in permissions">
                <td>@{{ permission.name }}</td>
                <td v-for="role in roles">
                    <label v-if="role.id==1" class="form-checkbox-label">
                        <i class="fa fa-lock"></i>
                        <input type="checkbox" value="1" name="permissionRoles[@{{permission.id}}][@{{role.id}}]" v-bind="{ 'checked': checks[permission.id][role.id].checked}" style="display:none;">
                    </label>
                    <label v-else="role.id!=1" class="form-checkbox-label">
                        <input type="checkbox" value="1" name="permissionRoles[@{{permission.id}}][@{{role.id}}]" v-bind="{ 'checked': checks[permission.id][role.id].checked}">
                    </label>
                </td>
            </tr>
        </table>
        <div class="form-group row col-sm-offset-4 col-sm-8">
            <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
            <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
        </div>
    </form>
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

    <!-- Create Round Modal -->
    <div class="modal fade" id="create-round" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Round</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createRound">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="newRound.name" />
                                        <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="newRound.description"></textarea>
                                        <span v-if="formErrors['description']" class="error text-danger">@{{ formErrors['description'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Start Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="start_date" class="form-control" v-model="newRound.start_date" />
                                        <span v-if="formErrors['start_date']" class="error text-danger">@{{ formErrors['start_date'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">End Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="end_date" class="form-control" v-model="newRound.end_date" />
                                        <span v-if="formErrors['end_date']" class="error text-danger">@{{ formErrors['end_date'] }}</span>
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

    <!-- Edit Round Modal -->
    <div class="modal fade" id="edit-round" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Round</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateRound(fillRound.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="fillRound.name" />
                                        <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="fillRound.description"></textarea>
                                        <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Start Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="start_date" class="form-control" v-model="fillRound.start_date" />
                                        <span v-if="formErrorsUpdate['start_date']" class="error text-danger">@{{ formErrorsUpdate['start_date'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">End Date:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="end_date" class="form-control" v-model="fillRound.end_date" />
                                        <span v-if="formErrorsUpdate['end_date']" class="error text-danger">@{{ formErrorsUpdate['end_date'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection