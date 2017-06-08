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
            <div class="pull-left col-md-6">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.shipment', 2) !!}
        
                @permission('create-shipment')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-shipment">
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
    @if(Auth::user()->isSubCountyCoordinator() || Auth::user()->isFacilityInCharge())
    <table class="table table-bordered">
        <tr>
            <th>Round</th>
            <th>Facility</th>
            <th>Tracker</th>
            <th>Panels</th>
            <th>Picked By</th>
            <th>Contacts</th>
            <th>Date Picked</th>
        </tr>
        <tr v-for="consignment in consignments">
            <td>@{{ consignment.rnd }}</td>
            <td>@{{ consignment.fclty }}</td>
            <td>@{{ consignment.tracker }}</td>
            <td>@{{ consignment.total }}</td>
            <td>@{{ consignment.picked_by }}</td>
            <td>@{{ consignment.contacts }}</td>
            <td>@{{ consignment.date_picked }}</td>
        </tr>
    </table>
    @else
    <table class="table table-bordered">
        <tr>
            <th>Round</th>
            <th>County</th>
            <th>Date Shipped</th>
            <th>Shipper</th>
            <th>Panels</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr v-for="shipment in shipments">
            <td>@{{ shipment.rnd }}</td>
            <td>@{{ shipment.cnty }}</td>
            <td>@{{ shipment.date_shipped }}</td>
            <td>@{{ shipment.shppr }}</td>
            <td>@{{ shipment.panels_shipped }}</td>
            <td>
                <button v-if="!shipment.date_received" class="mbtn mbtn-raised mbtn-warning mbtn-xs">Pending</button>
                <button v-if="shipment.date_received" class="mbtn mbtn-raised mbtn-success mbtn-xs">Received</button>
            </td>
            <td>	
            @permission('receive-shipment')
                <button v-bind="{ 'disabled': shipment.date_received}" id="receipt" class="btn btn-sm btn-asbestos receive" data-toggle="modal" data-target="#receive-shipment" :data-fk="shipment.id"><i class="fa fa-download"></i> Receive</button>
            @endpermission
            @permission('update-shipment')
                <button v-bind="{ 'disabled': shipment.date_received}" class="btn btn-sm btn-primary" @click.prevent="editShipment(shipment)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            
                <button v-bind="{ 'disabled': !shipment.date_received}"  id="distribute" data-toggle="modal" data-target="#distribute-shipment" :data-fk="shipment.id" class="btn btn-sm btn-pomegranate distribute"><i class="fa fa-pie-chart"></i> Distribute</button>
            
            @permission('view-distributions')
                <button v-bind="{ 'disabled': shipment.shipment.cons==0}" class="btn btn-sm btn-midnight-blue" @click.prevent="loadConsignments(shipment)"><i class="fa fa-link"></i> Picks</button>
            @endpermission
            </td>
        </tr>
    </table>
    @endif
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
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('pt round') }" for="round">PT Round:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="pt round" :class="{'input': true, 'is-danger': errors.has('pt round') }" v-model="newShipment.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-show="errors.has('pt round')" class="help is-danger">@{{ errors.first('pt round') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('county') }" for="round">County:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="county" :class="{'input': true, 'is-danger': errors.has('county') }" v-model="newShipment.county_id">
                                        <option selected></option>
                                        <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                                    </select>
                                    <span v-show="errors.has('county')" class="help is-danger">@{{ errors.first('county') }}</span>
                                </div>
                            </div>
                            <!--
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
                            -->
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date prepared') }" for="date prepared">Date Prepared:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date prepared') }" name="date prepared" type="date" placeholder="" v-model="newShipment.date_prepared" />
                                    <span v-show="errors.has('date prepared')" class="help is-danger">@{{ errors.first('date prepared') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date shipped') }" for="date shipped">Date Shipped:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date shipped') }" name="date shipped" type="date" placeholder="" v-model="newShipment.date_shipped" />
                                    <span v-show="errors.has('date shipped')" class="help is-danger">@{{ errors.first('date shipped') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('tracker') }" for="tracker">Tracker ID:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|alpha_num'" class="form-control" :class="{'input': true, 'is-danger': errors.has('panels shipped') }" name="tracker" type="text" placeholder="" v-model="newShipment.tracker" />
                                    <span v-show="errors.has('tracker')" class="help is-danger">@{{ errors.first('tracker') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('shipping method') }" for="shipping method">Shipping Method:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" id="shipping_method" name="shipping method" :class="{'input': true, 'is-danger': errors.has('shipping method') }" v-model="newShipment.shipping_method" v-on:change="fetchShippers">
                                        <option selected></option>
                                        <option v-for="method in methods" :value="method.name">@{{ method.title }}</option>
                                    </select>
                                    <span v-show="errors.has('shipping method')" class="help is-danger">@{{ errors.first('shipping method') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('shipper') }" for="shipper">Shipper:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="shipper" :class="{'input': true, 'is-danger': errors.has('shipper') }" v-model="newShipment.shipper_id">
                                        <option selected></option>
                                        <option v-for="shipper in shippers" :value="shipper.id">@{{ shipper.value }}</option>
                                    </select>
                                    <span v-show="errors.has('shipper')" class="help is-danger">@{{ errors.first('shipper') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('panels shipped') }" for="panels shipped">Panels Shipped:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|numeric'" class="form-control" :class="{'input': true, 'is-danger': errors.has('panels shipped') }" name="panels shipped" type="number" placeholder="" v-model="newShipment.panels_shipped" />
                                    <span v-show="errors.has('panels shipped')" class="help is-danger">@{{ errors.first('panels shipped') }}</span>
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
                <div class="row">
                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateShipment(fillShipment.id)">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('pt round') }" for="round">PT Round:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="pt round" :class="{'input': true, 'is-danger': errors.has('pt round') }" v-model="fillShipment.round_id">
                                        <option selected></option>
                                        <option  v-for="round in rounds" :value="round.id">@{{ round.value }}</option>   
                                    </select>
                                    <span v-show="errors.has('pt round')" class="help is-danger">@{{ errors.first('pt round') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('county') }" for="round">County:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="county" :class="{'input': true, 'is-danger': errors.has('county') }" v-model="fillShipment.county_id">
                                        <option selected></option>
                                        <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                                    </select>
                                    <span v-show="errors.has('county')" class="help is-danger">@{{ errors.first('county') }}</span>
                                </div>
                            </div>
                            <!--
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
                            -->
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date prepared') }" for="date prepared">Date Prepared:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date prepared') }" name="date prepared" type="date" placeholder="" v-model="fillShipment.date_prepared" />
                                    <span v-show="errors.has('date prepared')" class="help is-danger">@{{ errors.first('date prepared') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('date shipped') }" for="date shipped">Date Shipped:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('date shipped') }" name="date shipped" type="date" placeholder="" v-model="fillShipment.date_shipped" />
                                    <span v-show="errors.has('date shipped')" class="help is-danger">@{{ errors.first('date shipped') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('tracker') }" for="tracker">Tracker ID:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|alpha_num'" class="form-control" :class="{'input': true, 'is-danger': errors.has('panels shipped') }" name="tracker" type="text" placeholder="" v-model="fillShipment.tracker" />
                                    <span v-show="errors.has('tracker')" class="help is-danger">@{{ errors.first('tracker') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('shipping method') }" for="shipping method">Shipping Method:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" id="shipping_method" name="shipping method" :class="{'input': true, 'is-danger': errors.has('shipping method') }" v-model="fillShipment.shipping_method" v-on:change="fetchShippers">
                                        <option selected></option>
                                        <option v-for="method in methods" :value="method.name">@{{ method.title }}</option>
                                    </select>
                                    <span v-show="errors.has('shipping method')" class="help is-danger">@{{ errors.first('shipping method') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('shipper') }" for="shipper">Shipper:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <select v-validate="'required'" class="form-control c-select" name="shipper" :class="{'input': true, 'is-danger': errors.has('shipper') }" v-model="fillShipment.shipper_id">
                                        <option selected></option>
                                        <option v-for="shipper in shippers" :value="shipper.id">@{{ shipper.value }}</option>
                                    </select>
                                    <span v-show="errors.has('shipper')" class="help is-danger">@{{ errors.first('shipper') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('panels shipped') }" for="panels shipped">Panels Shipped:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|numeric'" class="form-control" :class="{'input': true, 'is-danger': errors.has('panels shipped') }" name="panels shipped" type="number" placeholder="" v-model="fillShipment.panels_shipped" />
                                    <span v-show="errors.has('panels shipped')" class="help is-danger">@{{ errors.first('panels shipped') }}</span>
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

    <!-- Receive Shipment Modal -->
    <div id="receive-shipment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Receive Shipment</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="receiveShipment()" id="frm">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="shipment_id" id="shipment-id" v-model="newReceipt.shipment_id" value=""/>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Received:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_received" class="form-control" v-model="newReceipt.date_received" />
                                        <span v-if="formReceiptErrors['date_received']" class="error text-danger">@{{ formReceiptErrors['date_received'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Panels Received:</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="panels_received" class="form-control" v-model="newReceipt.panels_received" />
                                        <span v-if="formReceiptErrors['panels_received']" class="error text-danger">@{{ formReceiptErrors['panels_received'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Received By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="receiver" class="form-control" v-model="newReceipt.receiver" />
                                        <span v-if="formReceiptErrors['receiver']" class="error text-danger">@{{ formReceiptErrors['receiver'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Condition:</label>
                                    <div class="col-sm-8">
                                        <textarea name="condition" class="form-control" v-model="newReceipt.condition"></textarea>
                                        <span v-if="formReceiptErrors['condition']" class="error text-danger">@{{ formReceiptErrors['condition'] }}</span>
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
    <!-- Distribute Shipment Modal -->
    <div id="distribute-shipment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Distribute Shipment</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="distributeShipment()" id="frm">
                            <div class="col-md-12">
                                <input type="hidden" class="form-control" name="shipment_id" id="shpmnt-id" v-model="newConsignment.shipment_id" value=""/>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" v-model="newConsignment.sub_id" v-on:change="fetchFacilities">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>   
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="newConsignment.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>   
                                        </select>
                                        <span v-if="formConsignmentErrors['facility_id']" class="error text-danger">@{{ formConsignmentErrors['facility_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Tracker ID:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="tracker" class="form-control" v-model="newConsignment.tracker" />
                                        <span v-if="formConsignmentErrors['tracker']" class="error text-danger">@{{ formConsignmentErrors['tracker'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Total Panels:</label>
                                    <div class="col-sm-8">
                                        <input type="number" name="total" class="form-control" v-model="newConsignment.total" />
                                        <span v-if="formConsignmentErrors['total']" class="error text-danger">@{{ formConsignmentErrors['total'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Date Picked:</label>
                                    <div class="col-sm-8">
                                        <input type="date" name="date_picked" class="form-control" v-model="newConsignment.date_picked" />
                                        <span v-if="formConsignmentErrors['date_picked']" class="error text-danger">@{{ formConsignmentErrors['date_picked'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Picked By:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="picked_by" class="form-control" v-model="newConsignment.picked_by" />
                                        <span v-if="formConsignmentErrors['picked_by']" class="error text-danger">@{{ formConsignmentErrors['picked_by'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Picker Phone:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="contacts" class="form-control" v-model="newConsignment.contacts" />
                                        <span v-if="formConsignmentErrors['contacts']" class="error text-danger">@{{ formConsignmentErrors['contacts'] }}</span>
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

    <!-- View picked consignments Modal -->
    <div id="picked-consignments" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Distributed Consignments</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4" style="padding-bottom:10px;">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search for..." v-model="query">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="button" @click="srchEnrol()" v-if="!loading"><i class="fa fa-search"></i></button>
                                    <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" class="form-control" name="round_id" id="round-id" value=""/>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Facility</th>
                                    <th>Panels</th>
                                    <th>Date Picked</th>
                                    <th>Picked By</th>
                                    <th>Contact</th>
                                    <th>Action</th>
                                </tr>
                                <tr v-for="consignment in consignments">
                                    <td>@{{ consignment.fclty }}</td>
                                    <td>@{{ consignment.total }}</td>
                                    <td>@{{ consignment.date_picked }}</td>
                                    <td>@{{ consignment.picked_by }}</td>
                                    <td>@{{ consignment.contacts }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" @click.prevent="editConsignment(consignment)"><i class="fa fa-edit"></i> Edit</button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection