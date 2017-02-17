@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.bulk-sms') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans('messages.settings') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-bulk-sms-settings">
    <!-- Bulk SMS Settings Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans('messages.settings') !!}
                    <button class="btn btn-sm btn-primary" @click.prevent="editSettings(code, username, api_key)"><i class="fa fa-edit"></i> Edit</button>
                
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <td>Code:</td>
            <td>@{{ code }}</td>
        </tr>
        <tr>
            <td>Username:</td>
            <td>@{{ username }}</td>
        </tr>
        <tr>
            <td>API Key:</td>
            <td>@{{ api_key }}</td>
        </tr>
    </table>

    <!-- Edit Settings Modal -->
    <div class="modal fade" id="edit-settings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Bulk SMS Settings</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateSettings()">

                        <div class="col-md-12">
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Code:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="code" class="form-control" v-model="fillSettings.code" />
                                    <span v-if="formErrors['code']" class="error text-danger">@{{ formErrors['code'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="username" class="form-control" v-model="fillSettings.username" />
                                    <span v-if="formErrors['username']" class="error text-danger">@{{ formErrors['username'] }}</span>
                                 </div>
                            </div>
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">API Key:</label>
                                <div class="col-sm-8">
                                    <textarea name="api_key" class="form-control" v-model="fillSettings.api_key"></textarea>
                                    <span v-if="formErrors['api_key']" class="error text-danger">@{{ formErrors['api_key'] }}</span>
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