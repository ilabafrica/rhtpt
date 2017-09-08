@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-user-circle"></i> {!! trans('messages.user-profile') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-user-profile">
    <!-- User Profile -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans('messages.user-profile') !!}
                    <button class="btn btn-sm btn-primary" @click.prevent="editProfile(userProfile)"><i class="fa fa-edit"></i> Edit Profile</button>

                    <button class="btn btn-sm btn-alizarin" data-toggle="modal" data-target="#update-password"><i class="fa fa-address-card"></i> Update Password</button>

                    <button class="btn btn-sm btn-registered" v-show="userProfile.rl == userProfile.participant" @click="fetchUser(userProfile)"><i class="fa fa-random"></i> Transfer Facility</button>

                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                </h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <img :src="'images/profiles/'+userProfile.image" alt="..." class="img-thumbnail">
        </div>
        <div class="col-md-9">
            <table class="table table-bordered">
                <tr v-if="userProfile.rl == userProfile.participant">
                    <td>Tester Enrollment ID:</td>
                    <td class="text-info"><strong>@{{ userProfile.uid }}</strong></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td class="text-info"><strong>@{{ userProfile.name }}</strong></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td class="text-info"><strong>@{{ userProfile.sex }}</strong></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td class="text-info"><strong>@{{ userProfile.email }}</strong></td>
                </tr>
                <tr>
                    <td>Phone:</td>
                    <td class="text-info"><strong>@{{ userProfile.phone }}</strong></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td class="text-info"><strong>@{{ userProfile.address }}</strong></td>
                </tr>
                <tr v-if="userProfile.rl != userProfile.participant">
                    <td>Username:</td>
                    <td class="text-info"><strong>@{{ userProfile.username }}</strong></td>
                </tr>
                <tr v-if="userProfile.rl == userProfile.participant">
                    <td>Designation:</td>
                    <td class="text-info"><strong>@{{ userProfile.des }}</strong></td>
                </tr>
                <tr v-if="userProfile.rl == userProfile.participant">
                    <td>Program:</td>
                    <td class="text-info"><strong>@{{ userProfile.prog }}</strong></td>
                </tr>
                <tr v-if="userProfile.rl == userProfile.participant">
                    <td>Facility:</td>
                    <td class="text-info"><strong>@{{ userProfile.mfl+' '+userProfile.facility+' ('+userProfile.sub_county+' - '+userProfile.county+' County)' }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">@{{ userProfile.name }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateProfile">

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Profile Photo:</label>
                                    <div class="col-sm-8">
                                        <img :src="'images/profiles/'+userProfile.image" class="img-circle img-responsive" width="100px" height="100px" alt="...">
                                        <br />
                                        <input type="file" @change="imageChanged">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Username:</label>
                                    <div class="col-sm-8">
                                        <label for="facility" class="text-primary"><strong>@{{ userProfile.username }}</strong></label>
                                    </div>
                                </div>
    				            <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Name:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="userProfile.name" />
                                        <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                     </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Gender:</label>
                                    <div class="col-sm-8">
                                        <label for="gender" class="text-primary"><strong>@{{ userProfile.sex }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('phone number') }" for="phone number">Phone Number:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|digits:10'" class="form-control" :class="{'input': true, 'is-danger': errors.has('phone number') }" name="phone number" type="text" v-model="userProfile.phone"/>
                                        <span v-show="errors.has('phone number')" class="help is-danger">@{{ errors.first('phone number') }}</span>
                                        <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('email') }" for="email">Email:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|email'" class="form-control" :class="{'input': true, 'is-danger': errors.has('email') }" name="email" type="text" v-model="userProfile.email"/>
                                        <span v-show="errors.has('email')" class="help is-danger">@{{ errors.first('email') }}</span>
                                        <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="address">Address:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="address" type="text" v-model="userProfile.address"/>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="designation">Designation:</label>
                                    <div class="col-sm-8">
                                        <label for="mfl" class="text-primary"><strong>@{{ userProfile.des }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="program">Program:</label>
                                    <div class="col-sm-8">
                                        <label for="mfl" class="text-primary"><strong>@{{ userProfile.prog }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="mfl code">MFL Code:</label>
                                    <div class="col-sm-8">
                                        <label for="mfl" class="text-primary"><strong>@{{ userProfile.mfl }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="facility name">Facility Name:</label>
                                    <div class="col-sm-8">
                                        <label for="facility" class="text-primary"><strong>@{{ userProfile.facility }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="sub county">Sub County:</label>
                                    <div class="col-sm-8" v-if="userProfile.rl == userProfile.participant">
                                        <label for="sub-county" class="text-primary"><strong>@{{ userProfile.sub_county }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="userProfile.rl == userProfile.participant">
                                    <label class="col-sm-4 form-control-label" for="county">County:</label>
                                    <div class="col-sm-8">
                                        <label for="county" class="text-primary"><strong>@{{ userProfile.county }}</strong></label>
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
    <!-- Change Password Modal -->
    <div class="modal fade" id="update-password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Updating password for @{{ userProfile.name }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updatePassword('update_password')" data-vv-scope="update_password">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('update_password.current-password') }" for="current-password">Current Password:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('update_password.current-password') }" name="current-password" type="password" v-model="userPassword.old"/>
                                        <span v-show="errors.has('update_password.current-password')" class="help is-danger">@{{ errors.first('update_password.current-password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('update_password.new-password') }" for="new-password">New Password:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|min:6|confirmed:confirm-password'" class="form-control" :class="{'input': true, 'is-danger': errors.has('update_password.new-password') }" name="new-password" type="password" v-model="userPassword.new"/>
                                        <span v-show="errors.has('update_password.new-password')" class="help is-danger">@{{ errors.first('update_password.new-password') }}</span>
                                        <span v-if="passwordErrors['new']" class="error text-danger">@{{ passwordErrors['new'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('update_password.confirm-password') }" for="confirm-password">Confirm Password:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('update_password.confirm-password') }" name="confirm-password" type="password" v-model="userPassword.confirm"/>
                                        <span v-show="errors.has('update_password.confirm-password')" class="help is-danger">@{{ errors.first('update_password.confirm-password') }}</span>
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
    <!-- Transfer user to different facility -->
    <div class="modal fade" id="transfer-participant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Transfer Facility</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="transferUser('transfer_facility')" data-vv-scope="transfer_facility">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('transfer_facility.designation') }" for="designation">Designation:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <select v-validate="'required'" class="form-control c-select" name="designation" :class="{'input': true, 'is-danger': errors.has('transfer_facility.designation') }" v-model="transUser.designation">
                                            <option selected></option>
                                            <option v-for="des in designations" :value="des.name">@{{ des.title }}</option>
                                        </select>
                                        <span v-show="errors.has('transfer_facility.designation')" class="help is-danger">@{{ errors.first('transfer_facility.designation') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('transfer_facility.program') }" for="program">Program:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <select v-validate="'required'" class="form-control c-select" name="program" :class="{'input': true, 'is-danger': errors.has('transfer_facility.program') }" v-model="transUser.program">
                                            <option selected></option>
                                            <option v-for="prog in programs" :value="prog.id">@{{ prog.value }}</option>
                                        </select>
                                        <span v-show="errors.has('transfer_facility.program')" class="help is-danger">@{{ errors.first('transfer_facility.program') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('transfer_facility.mfl-code') }" for="mfl code">MFL Code:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|numeric'" class="form-control" :class="{'input': true, 'is-danger': errors.has('transfer_facility.mfl-code') }" name="mfl-code" type="text" v-model="transUser.mfl_code" @change="fetchFacility" id="mfl" />
                                        <span v-show="errors.has('transfer_facility.mfl-code')" class="help is-danger">@{{ errors.first('transfer_facility.mfl-code') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="facility name">Facility Name:</label>
                                    <div class="col-sm-8">
                                        <label for="facility" class="text-primary"><strong>@{{ facility }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="sub county">Sub County:</label>
                                    <div class="col-sm-8">
                                        <label for="sub-county" class="text-primary"><strong>@{{ sub_county }}</strong></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="county">County:</label>
                                    <div class="col-sm-8">
                                        <label for="county" class="text-primary"><strong>@{{ county }}</strong></label>
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