@extends('app')
@section('content')
<script type="text/javascript">
function myFunction() {
    document.getElementById("Dropdown").classList.toggle("show");
}


window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
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
        
                @permission('create-shipper')
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
                <button v-if="!shipper.deleted_at" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="shipper.deleted_at" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>	
            <div class="dropdown">
            <a class="dropbtn" onclick="myFunction()"  >View</a>
            <div id="Dropdown" class="dropdown-content">
            @permission('update-shipper')
                <a v-bind="{ 'disabled': shipper.deleted_at}" @click.prevent="editShipper(shipper)"> Edit</a>
            @endpermission
            @permission('restore-shipper')
                <a v-if="shipper.deleted_at" @click.prevent="restoreShipper(shipper)"> Enable</a>
            @endpermission
            @permission('delete-shipper')
                <a v-if="!shipper.deleted_at"  @click.prevent="deleteShipper(shipper)"> Disable</button>
            @endpermission
            </div>
            </div>
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
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createShipper('create_shipper')" class="form-horizontal" data-vv-validate="create_shipper" data-vv-scope="create_shipper">

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_shipper.name') }" for="name">Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_shipper.name') }" name="name" type="text" placeholder="" v-model="newShipper.name" />
                                        <span v-show="errors.has('create_shipper.name')" class="help is-danger">@{{ errors.first('create_shipper.name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_shipper.shipper type') }" for="shipper type">Shipper Type:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio form-radio-inline" v-for="option in options">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" name="shipper type"  :value="option.name" :class="{'input': true, 'is-danger': errors.has('create_shipper.shipper type') }" v-model="newShipper.shipper_type">
                                                @{{ option.title }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('create_shipper.shipper type')" class="help is-danger">@{{ errors.first('create_shipper.shipper type') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_shipper.contact') }" for="contact">Contact Person:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_shipper.contact') }" name="contact" type="text" placeholder="" v-model="newShipper.contact" />
                                        <span v-show="errors.has('create_shipper.contact')" class="help is-danger">@{{ errors.first('create_shipper.contact') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_shipper.phone') }" for="phone">Contact Phone:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|digits:10'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_shipper.phone') }" name="phone" type="text" placeholder="" v-model="newShipper.phone" />
                                        <span v-show="errors.has('create_shipper.phone')" class="help is-danger">@{{ errors.first('create_shipper.phone') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_shipper.email') }" for="email">Contact Email:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|email'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_shipper.email') }" name="email" type="text" placeholder="" v-model="newShipper.email" />
                                        <span v-show="errors.has('create_shipper.email')" class="help is-danger">@{{ errors.first('create_shipper.email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
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

                    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateShipper(fillShipper.id, 'update_shipper')" data-vv-validate="update_shipper">
                        <div class="form-group row">
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('name') }" for="name">Name:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('name') }" name="name" type="text" placeholder="" v-model="fillShipper.name" />
                                    <span v-show="errors.has('name')" class="help is-danger">@{{ errors.first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('shipper type') }" for="shipper type">Shipper Type:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <div class="form-radio form-radio-inline" v-for="option in options">
                                        <label class="form-radio-label">
                                            <input v-validate="'required'" type="radio" name="shipper type"  :value="option.name" :class="{'input': true, 'is-danger': errors.has('shipper type') }" v-model="fillShipper.shipper_type">
                                            @{{ option.title }}
                                        </label>
                                    </div>
                                    <span v-show="errors.has('tester id')" class="help is-danger">@{{ errors.first('tester id') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('contact') }" for="contact">Contact Person:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('contact') }" name="contact" type="text" placeholder="" v-model="fillShipper.contact" />
                                    <span v-show="errors.has('contact')" class="help is-danger">@{{ errors.first('contact') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('phone') }" for="phone">Contact Phone:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|digits:10'" class="form-control" :class="{'input': true, 'is-danger': errors.has('phone') }" name="phone" type="text" placeholder="" v-model="fillShipper.phone" />
                                    <span v-show="errors.has('phone')" class="help is-danger">@{{ errors.first('phone') }}</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('email') }" for="email">Contact Email:</label>
                                <div class="col-sm-8" :class="{ 'control': true }">
                                    <input v-validate="'required|email'" class="form-control" :class="{'input': true, 'is-danger': errors.has('email') }" name="email" type="text" placeholder="" v-model="fillShipper.email" />
                                    <span v-show="errors.has('email')" class="help is-danger">@{{ errors.first('email') }}</span>
                                </div>
                            </div>
                            <div class="form-group row col-sm-offset-4 col-sm-8">
                                <button class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
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