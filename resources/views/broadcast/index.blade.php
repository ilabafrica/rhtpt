@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.bulk-sms') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans('messages.broadcast') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-broadcasts">
    <!-- Program Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans('messages.broadcast') !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#compose-sms">
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
            <th>Round</th>
            <th>Notification</th>
            <th>Date Sent</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        <tr v-for="broadcast in broadcasts">
            <td>@{{ broadcast.rnd }}</td>
            <td>@{{ broadcast.ntfctn }}</td>
            <td>@{{ broadcast.date_sent }}</td>
            <td>@{{ broadcast.text }}</td>
            <td>	
                <button v-bind="{ 'disabled': program.deleted_at!=NULL}" class="btn btn-sm btn-primary" @click.prevent="editBroadcast(broadcast)"><i class="fa fa-edit"></i> Edit</button>
                <button v-if="program.deleted_at!=NULL" class="btn btn-sm btn-success" @click.prevent="restoreBroadcast(broadcast)">Enable</button>
                <button v-if="program.deleted_at==NULL" class="btn btn-sm btn-alizarin" @click.prevent="deleteBroadcast(broadcast)">Disable</button>
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

    <!-- Compose SMS Modal -->
    <div class="modal fade" id="compose-sms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Compose SMS</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="broadcastSMS">

                        <div class="col-md-12">
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Round ID:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="newSMS.round_id">
                                        <option selected></option>
                                        <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Notification:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="notification_id" v-model="newSMS.notification_id" @change="loadTemplate">
                                        <option selected></option>
                                        <option v-for="notification in notifications" :value="notification.id">@{{ notification.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['notification_id']" class="error text-danger">@{{ formErrors['notification_id'] }}</span>
                                 </div>
                            </div>
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                <div class="col-sm-8">
                                    <textarea name="text" value="" class="form-control" v-model="newSMS.text" rows="4" id="text"></textarea>
                                    <span v-if="formErrors['text']" class="error text-danger">@{{ formErrors['text'] }}</span>
                                </div>
                            </div>
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">County:</label>
                                <div class="col-sm-8">
                                    <div class="form-checkbox form-checkbox-inline" v-for="county in counties">
                                        <label class="form-checkbox-label">
                                            <input type="checkbox" class="form-checkbox-input" :value="county.id" name="county[]" v-model="newSMS.county">
                                            @{{ county.value }}
                                        </label>
                                    </div>
                                    <span v-if="formErrors['county']" class="error text-danger">@{{ formErrors['county'] }}</span>
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

    <!-- Edit Program Modal -->
    <div class="modal fade" id="edit-program" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Program</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateProgram(fillProgram.id)">

                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" name="name" class="form-control" v-model="fillProgram.name" />
                        <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                    </div>

                    <div class="form-group">
                        <label for="title">Description:</label>
                        <textarea name="description" class="form-control" v-model="fillProgram.description"></textarea>
                        <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>

                </form>

            </div>
        </div>
        </div>
    </div>

</div>
@endsection