@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.user', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-user">
    <!-- User Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-8">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.user', 2) !!}
        
                @permission('create-user')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-user" >
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.add') !!}
                    </button>
                @endpermission
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                @permission('create-user')
                    <a class="btn btn-sm btn-concrete" href="/Registration.xlsx">
                        <i class="fa fa-download"></i>
                        Worksheet
                    </a>
                    <button class="btn btn-sm btn-nephritis" id="register" data-toggle="modal" data-target="#upload-worksheet"><i class="fa fa-level-up"></i> Upload Worksheet</button>
                @endpermission
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
            <th>Name</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr v-for="user in users">
            <td>@{{ user.name }}</td>
            <td>@{{ user.gender==0?'Male':'Female' }}</td>
            <td>@{{ user.phone }}</td>
            <td>@{{ user.username }}</td>
            <td>@{{ user.role }}</td>
            <td>
                <button v-if="user.deleted_at==NULL" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="user.deleted_at!=NULL" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>
            @permission('update-user')	
                <button v-bind="{ 'disabled': user.deleted_at!=NULL}" class="btn btn-sm btn-primary"  @click.prevent="editUser(user)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-user') 
                <button v-if="user.deleted_at!=NULL" class="btn btn-sm btn-success" @click.prevent="restoreUser(user)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-user') 
                <button v-if="user.deleted_at==NULL" class="btn btn-sm btn-alizarin" @click.prevent="deleteUser(user)"><i class="fa fa-power-off"></i> Disable</button>
            @endpermission
            @permission('transfer-user') 
                <button v-if="user.uid!=NULL" class="btn btn-sm btn-wet-asphalt"  @click.prevent="populateUser(user)"><i class="fa fa-send"></i> Transfer</button>
            @endpermission
            </td>
        </tr>
    </table>
    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <li v-if="pagination.current_page > 1"  class="page-item">
                <a class="page-link" href="#" aria-label="Previous"
                    @click.prevent="changePage(pagination.current_page - 1)">
                    <span aria-hidden="true">«</span>
                </a>
            </li>
            <li v-for="page in pagesNumber"  class="page-item"
                v-bind:class="[ page == isActived ? 'active' : '']">
                <a class="page-link" href="#"
                    @click.prevent="changePage(page)">@{{ page }}</a>
            </li>
            <li v-if="pagination.current_page < pagination.last_page"  class="page-item">
                <a class="page-link" href="#" aria-label="Next"
                    @click.prevent="changePage(pagination.current_page + 1)">
                    <span aria-hidden="true">»</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Create User Modal -->
    <div class="modal fade" id="create-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create User</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createUser">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="newUser.name" placeholder="e.g. John Doe" />
                                        <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Gender:</label>
                                    <div class="col-sm-8">
                                        <input type="radio" id="male" value="0" v-model="newUser.gender">
                                        <label for="male">Male</label>
                                        <br />
                                        <input type="radio" id="female" value="1" v-model="newUser.gender">
                                        <label for="female">Female</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Phone Number:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phone" class="form-control" v-model="newUser.phone" />
                                        <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Email:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" class="form-control" v-model="newUser.email" />
                                        <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control" v-model="newUser.address" />
                                        <span v-if="formErrors['address']" class="error text-danger">@{{ formErrors['address'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Role:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio radio-inline" v-for="role in roles">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="role.id" v-model="newUser.role" name="role">
                                                @{{ role.value }}
                                            </label>
                                        </div>
                                        <span v-if="formErrors['role']" class="error text-danger">@{{ formErrors['role'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 3">
                                    <label class="col-sm-4 form-control-label" for="title">Counties:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="jimbo" multiple v-model="newUser.jimbo">
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['jimbo']" class="error text-danger">@{{ formErrors['jimbo'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 4 || newUser.role == 6 || newUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id" @change="fetchSubs()" v-model="newUser.county_id">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['county_id']" class="error text-danger">@{{ formErrors['county_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 6 || newUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" @change="fetchFacilities" v-model="newUser.sub_id">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['sub_id']" class="error text-danger">@{{ formErrors['sub_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 6">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="newUser.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['facility_id']" class="error text-danger">@{{ formErrors['facility_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 2">
                                    <label class="col-sm-4 form-control-label" for="title">Program:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="program_id" v-model="newUser.program_id">
                                            <option selected></option>
                                            <option v-for="program in programs" :value="program.id">@{{ program.value }}</option>   
                                        </select>
                                        <span v-if="formErrors['program_id']" class="error text-danger">@{{ formErrors['program_id'] }}</span>
                                    </div>
                                </div>
                                <div v-if="newUser.role != 2">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="username" class="form-control" v-model="newUser.username" />
                                            <span v-if="formErrors['username']" class="error text-danger">@{{ formErrors['username'] }}</span>
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit User</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateUser(fillUser.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="fillUser.name"/>
                                        <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Gender:</label>
                                    <div class="col-sm-8">
                                        <input type="radio" id="male" value="0" v-model="fillUser.gender">
                                        <label for="male">Male</label>
                                        <br />
                                        <input type="radio" id="female" value="1" v-model="fillUser.gender">
                                        <label for="female">Female</label>
                                        <span v-if="formErrorsUpdate['gender']" class="error text-danger">@{{ formErrorsUpdate['gender'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Phone Number:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="phone" class="form-control" v-model="fillUser.phone" />
                                        <span v-if="formErrorsUpdate['phone']" class="error text-danger">@{{ formErrorsUpdate['phone'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Email:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="email" class="form-control" v-model="fillUser.email" />
                                        <span v-if="formErrorsUpdate['email']" class="error text-danger">@{{ formErrorsUpdate['email'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Address:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="address" class="form-control" v-model="fillUser.address" />
                                        <span v-if="formErrorsUpdate['address']" class="error text-danger">@{{ formErrorsUpdate['address'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Role:</label>
                                    <div class="col-sm-8">
                                        <div class="form-radio radio-inline" v-for="role in roles">
                                            <label class="form-radio-label">
                                                <input type="radio" :value="role.id" v-model="fillUser.role" name="role">
                                                @{{ role.value }}
                                            </label>
                                        </div>
                                        <span v-if="formErrorsUpdate['role']" class="error text-danger">@{{ formErrorsUpdate['role'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 3">
                                    <label class="col-sm-4 form-control-label" for="title">Counties:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="jimbo" multiple v-model="fillUser.jimbo">
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['jimbo']" class="error text-danger">@{{ formErrorsUpdate['jimbo'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 4 || fillUser.role == 6 || fillUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id" @change="fetchSubs()" v-model="fillUser.county_id">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['county_id']" class="error text-danger">@{{ formErrorsUpdate['county_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 6 || fillUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" @change="fetchFacilities" v-model="fillUser.sub_id">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['sub_id']" class="error text-danger">@{{ formErrorsUpdate['sub_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 6">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="fillUser.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['facility_id']" class="error text-danger">@{{ formErrorsUpdate['facility_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 2">
                                    <label class="col-sm-4 form-control-label" for="title">Program:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="program_id" v-model="fillUser.program_id">
                                            <option selected></option>
                                            <option v-for="program in programs" :value="program.id">@{{ program.value }}</option>   
                                        </select>
                                        <span v-if="formErrorsUpdate['program_id']" class="error text-danger">@{{ formErrorsUpdate['program_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="username" class="form-control" v-model="fillUser.username" />
                                        <span v-if="formErrorsUpdate['username']" class="error text-danger">@{{ formErrorsUpdate['username'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                    <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                                </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Transfer user to different facility -->
    <div class="modal fade" id="transfer-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Transfer Participant</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="transUser(transferUser.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id" @change="fetchSubs()">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" @change="fetchFacilities">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>   
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="newUser.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>   
                                        </select>
                                        <span v-if="formTransErrors['facility_id']" class="error text-danger">@{{ formTransErrors['facility_id'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Program:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="program_id" v-model="newUser.program_id">
                                            <option selected></option>
                                            <option v-for="program in programs" :value="program.id">@{{ program.value }}</option>   
                                        </select>
                                        <span v-if="formTransErrors['program_id']" class="error text-danger">@{{ formTransErrors['program_id'] }}</span>
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
    <!-- Upload batch registration worksheet -->
    <div class="modal fade" id="upload-worksheet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Upload Worksheet</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form enctype="multipart/form-data" route="batch.registration" method="POST">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="exampleInputFile" class="col-sm-4 col-form-label">Select Worksheet</label>
                                <div class="col-sm-8">
                                    <input type="file" id="worksheet" name="worksheet" class="form-control" v-model="batchWorksheet.worksheet">
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
@endsection