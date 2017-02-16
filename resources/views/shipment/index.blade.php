@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.shipment', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-shipment">
    <!-- Shipment Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.shipment', 2) !!}
        
                @permission('create-role')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-shipment">
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
            <th>PT Round</th>
            <th>Facility</th>
            <th>Date Prepared</th>
            <th>Date Shipped</th>
            <th>Shipping Method</th>
            <th>Panels Shipped</th>
            <th>Action</th>
        </tr>
        <tr v-for="shipment in shipments">
            <td>@{{ shipment.pt_round }}</td>
            <td>@{{ shipment.facility_id }}</td>
            <td>@{{ shipment.date_prepared }}</td>
            <td>@{{ shipment.date_shipped }}</td>
            <td>@{{ shipment.shipping_method }}</td>
            <td>@{{ shipment.panels_shipped }}</td>
            <td>	
                <button class="btn btn-sm btn-primary" @click.prevent="editShipment(shipment)"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" @click.prevent="deleteShipment(shipment)"><i class="fa fa-trash-o"></i> Delete</button>
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

    <!-- Create Shipment Modal -->
    <div class="modal fade" id="create-shipment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Shipment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createShipment">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">PT Round:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="round_id" v-model="newShipment.round_id">
                                        <option selected></option>
                                        <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['round_id']" class="error text-danger">@{{ formErrors['round_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">County:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="county_id" id="county_id" v-model="newShipment.county_id" @change="fetchSubs">
                                        <option selected></option>
                                        <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['county_id']" class="error text-danger">@{{ formErrors['county_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="sub_id" id="sub_id" v-model="newShipment.sub_id" v-on:change="fetchFacilities">
                                        <option selected></option>
                                        <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['sub_id']" class="error text-danger">@{{ formErrors['sub_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="facility_id" v-model="newShipment.facility_id">
                                        <option selected></option>
                                        <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['facility_id']" class="error text-danger">@{{ formErrors['facility_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Date Prepared:</label>
                                <div class="col-sm-8">
                                    <input type="date" name="date_prepapred" class="form-control" v-model="newShipment.date_prepapred" />
                                    <span v-if="formErrors['date_prepapred']" class="error text-danger">@{{ formErrors['date_prepapred'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Date Shipped:</label>
                                <div class="col-sm-8">
                                    <input type="date" name="date_shipped" class="form-control" v-model="newShipment.date_shipped" />
                                    <span v-if="formErrors['date_shipped']" class="error text-danger">@{{ formErrors['date_shipped'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Shipping Method:</label>
                                <div class="col-sm-8">
                                    <div class="form-radio form-radio-inline" v-for="method in methods">
                                        <label class="form-radio-label">
                                            <input type="radio" :value="method.name" v-model="newShipment.shipping_method" name="shipping_method">
                                            @{{ method.title }}
                                        </label>
                                    </div>
                                </div>
                                <span v-if="formErrors['shipping_method']" class="error text-danger">@{{ formErrors['shipping_method'] }}</span>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Shipper:</label>
                                <div class="col-sm-8">
                                    <select class="form-control c-select" name="item_id" v-model="newShipment.item_id">
                                        <option selected></option>
                                        <option v-for="item in items" :value="item.id">@{{ item.value }}</option>   
                                    </select>
                                    <span v-if="formErrors['item_id']" class="error text-danger">@{{ formErrors['item_id'] }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" for="title">Panels Shipped:</label>
                                <div class="col-sm-8">
                                    <input type="number" name="panels_shipped" class="form-control" v-model="newShipment.panels_shipped" />
                                    <span v-if="formErrors['panels_shipped']" class="error text-danger">@{{ formErrors['panels_shipped'] }}</span>
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

    <!-- Edit Shipment Modal -->
    <div class="modal fade" id="edit-shipment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Shipment</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateShipment(fillShipment.id)">

                    <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="name" class="form-control" v-model="fillShipment.name" />
                    <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="fillShipment.description"></textarea>
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