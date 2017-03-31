@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.shipper', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-shipper">
    <!-- Shipper Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.shipper', 2) !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-shipper">
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
            <th>Shipper Type</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr v-for="shipper in shippers">
            <td>@{{ shipper.st }}</td>
            <td>@{{ shipper.name }}</td>
            <td>@{{ shipper.contact }}</td>
            <td>@{{ shipper.phone }}</td>
            <td>@{{ shipper.email }}</td>
            <td>
                <button v-if="shipper.deleted_at==NULL" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="shipper.deleted_at!=NULL" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>	
                <button v-bind="{ 'disabled': shipper.deleted_at!=NULL}" class="btn btn-sm btn-primary" @click.prevent="editShipper(shipper)"><i class="fa fa-edit"></i> Edit</button>
                <button v-if="shipper.deleted_at!=NULL" class="btn btn-sm btn-success" @click.prevent="restoreShipper(shipper)">Enable</button>
                <button v-if="shipper.deleted_at==NULL" class="btn btn-sm btn-alizarin" @click.prevent="deleteShipper(shipper)">Disable</button>
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

    <!-- Create Shipper Modal -->
    <div class="modal fade" id="create-shipper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Shipper</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createShipper" class="form-horizontal">

                        <div class="col-md-12">
				            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" class="form-control" v-model="newShipper.name" />
                                    <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Shipper Type:</label>
                                <div class="col-sm-8">

                                    <div class="form-radio form-radio-inline" v-for="option in options">
                                        <label class="form-radio-label">
                                            <input type="radio" :value="option.name" v-model="newShipper.shipper_type">
                                            @{{ option.title }}
                                        </label>
                                    </div>
                                    
                                    <span v-if="formErrors['shipper_type']" class="error text-danger">@{{ formErrors['shipper_type'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Person:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="contact" class="form-control" v-model="newShipper.contact" />
                                    <span v-if="formErrors['contact']" class="error text-danger">@{{ formErrors['contact'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Phone:</label>
                                <div class="col-sm-8">
                                    <input type="number" name="phone" class="form-control" v-model="newShipper.phone" />
                                    <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
                                 </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Email:</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" v-model="newShipper.email" />
                                    <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
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

    <!-- Edit Shipper Modal -->
    <div class="modal fade" id="edit-shipper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Shipper</h4>
                </div>
                <div class="modal-body">

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateShipper(fillShipper.id)">

                        
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Name:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="name" class="form-control" v-model="fillShipper.name" />
                                    <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Shipper Type:</label>
                                <div class="col-sm-8">

                                    <div class="form-radio form-radio-inline" v-for="option in options">
                                        <label class="form-radio-label">
                                            <input type="radio" :value="option.name" v-model="fillShipper.shipper_type">
                                            @{{ option.title }}
                                        </label>
                                    </div>
                                    
                                    <span v-if="formErrors['shipper_type']" class="error text-danger">@{{ formErrors['shipper_type'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Person:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="contact" class="form-control" v-model="fillShipper.contact" />
                                    <span v-if="formErrors['contact']" class="error text-danger">@{{ formErrors['contact'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Phone:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="phone" class="form-control" v-model="fillShipper.phone" />
                                    <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Contact Email:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="email" class="form-control" v-model="fillShipper.email" />
                                    <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                            </div>
                        <br />
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection