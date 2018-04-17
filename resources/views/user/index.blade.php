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
            <div class="pull-left col-md-9">
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
                </h5>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query" v-on:keyup.enter="search()">
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
            <td v-if="user.name!=''">@{{ user.name }}</td>
            <td v-else>@{{ user.first_name }} @{{ user.middle_name }} @{{ user.last_name }}</td>
            <td>@{{ user.gender==0?'Male':'Female' }}</td>
            <td>@{{ user.phone }}</td>
            <td>@{{ user.username }}</td>
            <td>@{{ user.rl }}</td>
            <td>
                <button v-if="!user.deleted_at" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="user.deleted_at" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>
            @permission('update-user')
                <button v-bind="{ 'disabled': user.deleted_at }" class="btn btn-sm btn-primary"  @click.prevent="editUser(user)"><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('restore-user')
                <button v-if="user.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreUser(user)"><i class="fa fa-toggle-on"></i> Enable</button>
            @endpermission
            @permission('delete-user')
                <button v-if="!user.deleted_at" class="btn btn-sm btn-alizarin" @click.prevent="deleteUser(user)"><i class="fa fa-power-off"></i> Disable</button>
            @endpermission
            @permission('transfer-user')
                <button style="display: none;" v-if="user.uid" class="btn btn-sm btn-wet-asphalt"  @click.prevent="populateUser(user)"><i class="fa fa-send"></i> Transfer</button>
            @endpermission
                <button v-if="user.sms_code" v-if="!user.deleted_at" class="btn btn-sm btn-nephritis"  @click.prevent="openUser(user)"><i class="fa fa-user-circle"></i> Approve</button>
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
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createUser('create_user')" data-vv-scope="create_user">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.first_name') }"
                                        for="first_name">First Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('create_user.first_name') }" name="first_name"
                                            type="text" placeholder=""
                                            v-model="newUser.first_name" />
                                        <span v-show="errors.has('create_user.first_name')" class="help is-danger">
                                            @{{ errors.first('create_user.first_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.middle_name') }"
                                        for="middle_name">Middle Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('create_user.middle_name') }" name="middle_name"
                                            type="text" placeholder=""
                                            v-model="newUser.middle_name" />
                                        <span v-show="errors.has('create_user.middle_name')" class="help is-danger">
                                            @{{ errors.first('create_user.middle_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.last_name') }"
                                        for="last_name">Last Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('create_user.last_name') }" name="last_name"
                                            type="text" placeholder=""
                                            v-model="newUser.last_name" />
                                        <span v-show="errors.has('create_user.last_name')" class="help is-danger">
                                            @{{ errors.first('create_user.last_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.gender') }" for="tester id">Gender:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="sex in sexes">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" name="gender" :value="sex.name" :class="{'input': true, 'is-danger': errors.has('create_user.gender') }" v-model="newUser.gender">
                                                @{{ sex.title }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('create_user.gender')" class="help is-danger">@{{ errors.first('create_user.gender') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.phone number') }" for="phone number">Phone Number:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|digits:10'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_user.phone number') }" name="phone number" type="text" v-model="newUser.phone"/>
                                        <span v-show="errors.has('create_user.phone number')" class="help is-danger">@{{ errors.first('create_user.phone number') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.email') }" for="email">Email:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|email'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_user.email') }" name="email" type="text" v-model="newUser.email"/>
                                        <span v-show="errors.has('create_user.email')" class="help is-danger">@{{ errors.first('create_user.email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_user.role') }" for="role">Role:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="role in roles">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" :value="role.id" v-model="newUser.role" name="role" :class="{'input': true, 'is-danger': errors.has('create_user.role') }" v-model="newUser.gender">
                                                @{{ role.value }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('create_user.role')" class="help is-danger">@{{ errors.first('create_user.role') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 3">
                                    <label class="col-sm-4 form-control-label" for="title">
                                        Implementing Partner:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="implementing_partner_id"
                                            v-model="newUser.implementing_partner_id">
                                            <option v-for="implementing_partner in implementing_partners"
                                                :value="implementing_partner.id">
                                                @{{ implementing_partner.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 4 || newUser.role == 6 || newUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id" @change="fetchSubs()" v-model="newUser.county_id">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 6 || newUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" @change="fetchFacilities" v-model="newUser.sub_id">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 6">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="newUser.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 2">
                                    <label class="col-sm-4 form-control-label" for="title">Program:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="program_id" v-model="newUser.program_id">
                                            <option selected></option>
                                            <option v-for="program in programs" :value="program.id">@{{ program.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="newUser.role != 2">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="username" class="form-control" v-model="newUser.username" />
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
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateUser(fillUser.id, 'update_user')" data-vv-validate="update_user">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('first_name') }"
                                        for="first_name">First Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('first_name') }" name="first_name"
                                            type="text" placeholder=""
                                            v-model="fillUser.first_name" />
                                        <span v-show="errors.has('first_name')" class="help is-danger">
                                            @{{ errors.first('first_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('middle_name') }"
                                        for="middle_name">Middle Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('middle_name') }" name="middle_name"
                                            type="text" placeholder=""
                                            v-model="fillUser.middle_name" />
                                        <span v-show="errors.has('middle_name')" class="help is-danger">
                                            @{{ errors.first('middle_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('last_name') }"
                                        for="last_name">Last Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control"
                                            :class="{'input': true,'is-danger': errors.has('last_name') }" name="last_name"
                                            type="text" placeholder=""
                                            v-model="fillUser.last_name" />
                                        <span v-show="errors.has('last_name')" class="help is-danger">
                                            @{{ errors.first('last_name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('gender') }" for="tester id">Gender:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="sex in sexes">
                                            <label class="form-radio-label">
                                                <input v-validate="'required'" type="radio" name="gender" :value="sex.name" :class="{'input': true, 'is-danger': errors.has('gender') }" v-model="fillUser.gender">
                                                @{{ sex.title }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('gender')" class="help is-danger">@{{ errors.first('gender') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('phone number') }" for="phone number">Phone Number:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('phone number') }" name="phone number" type="text" v-model="fillUser.phone"/>
                                        <span v-show="errors.has('phone number')" class="help is-danger">@{{ errors.first('phone number') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('email') }" for="email">Email:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|email'" class="form-control" :class="{'input': true, 'is-danger': errors.has('email') }" name="email" type="text" v-model="fillUser.email"/>
                                        <span v-show="errors.has('email')" class="help is-danger">@{{ errors.first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('gender') }" for="role">Role:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <div class="form-radio radio-inline" v-for="role in roles">
                                            <label class="form-radio-label">
                                                <input v-if="role.id!=2" v-validate="'required'" type="radio" :value="role.id" v-model="fillUser.role" name="role" :class="{'input': true, 'is-danger': errors.has('role') }">
                                                @{{ role.value }}
                                            </label>
                                        </div>
                                        <span v-show="errors.has('role')" class="help is-danger">@{{ errors.first('role') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row" v-if="fillUser.role == 3">
                                    <label class="col-sm-4 form-control-label" for="title">Implementing Partner:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="implementing_partner_id" v-model="fillUser.implementing_partner_id">
                                            <option v-for="implementing_partner in implementing_partners" :value="implementing_partner.id">@{{ implementing_partner.name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 4 || fillUser.role == 6 || fillUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id" @change="fetchSubs()" v-model="fillUser.county_id">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="fillUser.role == 6 || fillUser.role == 7">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="sub_id" id="sub_id" @change="fetchFacilities" v-model="fillUser.sub_id">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 6">
                                    <label class="col-sm-4 form-control-label" for="title">Facility:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="facility_id" v-model="fillUser.facility_id">
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="newUser.role == 2">
                                    <label class="col-sm-4 form-control-label" for="title">Program:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="program_id" v-model="fillUser.program_id">
                                            <option selected></option>
                                            <option v-for="program in programs" :value="program.id">@{{ program.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-if="newUser.role != 2">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="username" class="form-control" v-model="fillUser.username" />
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
    <!-- Upload batch registration worksheet -->
    <div class="modal fade" id="batch-registration" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Worksheet</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="batchReg">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('excel file') }" for="excel file">File:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input type="file" v-validate="'required|ext:xlsx,xls'" class="form-control-file" :class="{'input': true, 'is-danger': errors.has('excel file') }" name="excel file" @change="fileChanged">
                                        <span v-show="errors.has('excel file')" class="help is-danger">@{{ errors.first('excel file') }}</span>
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
    <!-- Import users list -->
    <div class="modal fade" id="import-user-list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Worksheet</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="importUsers('import_users')" data-vv-validate="import_users">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" :class="{'help is-danger': errors.has('excel file') }" for="excel file">File:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input type="file" v-validate="'required|ext:xlsx,xls'" class="form-control-file" :class="{'input': true, 'is-danger': errors.has('users list') }" name="users list" @change="listChanged">
                                        <span v-show="errors.has('users list')" class="help is-danger">@{{ errors.first('users list') }}</span>
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
</div>
@endsection