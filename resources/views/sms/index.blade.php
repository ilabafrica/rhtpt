 @extends('app')
 @section('content')
 <div class="row">
  <div class="col-sm-12">
      <ol class="breadcrumb">
      	<li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
      	<li class="active"><i class="fa fa-group"></i>{!! trans('messages.sms') !!}</li>      	
      </ol>
  	
  </div>	
</div>
<!--lits sms-->
<div class="" id="manage-sms">
<!-- Sms listing -->
<div class="row">
		<div class="pull-left col-md-8">
		<h5><i class="fa fa-book">	</i>
		 {!! trans_choice('messages.Messages', 2) !!}
		 <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
             <i class="fa fa-step-backward"></i> {!! trans('messages.back') !!}
            </a>
            <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-message"><i class="fa fa-plus-circle"></i>
            {!! trans('messages.add') !!}
            </button>
            <button type="button" class="btn btn-sm btn-wisteria" data-toggle="modal" data-target="#select-users-message"><i class="fa fa-plus-circle"></i>
            Custom Message
            </button>
		 </h5>			
		</div> 
    <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query" v-on:keyup.enter="search()">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button" v-if="!loading"><i class="fa fa-search"></i></button>
                        <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </span>
                </div>
            </div> 
            <!-- </form> -->  		
	</div>
      <table class="table table-bordered">
        <tr>
            <th>Description</th>
            <th>Messages</th>
            <th>Action</th>  
        </tr>
        <tr v-for="message in messages">
            <td>@{{message.description}}</td> 
            <td>@{{message.message}}</td>        
            <td>           
                <button v-bind="{ 'disabled': message.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editMessages(message)"><i class="fa fa-edit"></i> Edit</button>
                <button v-if="message.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreMessages(message)"><i class="fa fa-toggle-on"></i> Enable</button> 
                <button v-if="!message.deleted_at" class="btn btn-sm btn-danger" @click.prevent="deleteMessages(message)"><i class="fa fa-power-off"></i> Disable</button>
                <button v-bind="{ 'disabled': message.deleted_at}" class="btn btn-sm btn-wisteria" @click.prevent="loadmessagesoptions(message)"><i class="fas fa-redo-alt"></i>Resend</button>
            </td>
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

	<!-- Create Sms-->
<div class="modal fade" id="create-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Create Messages</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createMessages('create_message')" id="create_message" data-vv-scope="create_message">
                            <div class="col-md-12"> 
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="description">Description:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="description" type="text" placeholder="Activated Account" v-model="newMessage.description" />
                                        <span v-if="formErrors['Message']" class="error text-danger">@{{ formErrors['Message'] }}</span>
                                    </div>
                                </div>                               
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="Message">Message</label>
                                    <div class="col-sm-8">
                                        <textarea name="Message" class="form-control" v-model="newMessage.message"></textarea>
                                        <span v-if="formErrors['Message']" class="error text-danger">@{{ formErrors['Message'] }}</span>
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
   <!-- Edit Messages-->
   <div class="modal fade" id="edit-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Messages</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateMessages(fillMessage.id,'create_message')" id="create_message" data-vv-scope="create_message">
                            <div class="col-md-12">     
                                 <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="description">Description:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="description" type="text" placeholder="" v-model="fillMessage.description" />
                                        <span v-if="formErrors['Message']" class="error text-danger">@{{ formErrors['Message'] }}</span>
                                    </div>
                                </div>                              
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="Message">Message</label>
                                    <div class="col-sm-8">
                                        <textarea name="Message" class="form-control" v-model="fillMessage.message"></textarea>
                                        <span v-if="formErrors['Message']" class="error text-danger">@{{ formErrors['Message'] }}</span>
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

   <!-- Select Users-->
    <div class="modal fade" id="select-users-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
             <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Message Users</h4>
                <h5 class="modal-title" id="myModalLabel">Step One: Select Users</h5>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="select_users_message()" id="select_users_message" data-vv-scope="select_users_message">
                         <input type="hidden" name="message_id" v-bind:value="sendMessage.id">
                               <div class="col-md-12">                                
                                <div class="form-group row" style="text-align:center;">
                                    <div class="form-radio radio-inline" >
                                    <label class="form-radio-label">
                                        <input type="radio" :value="0" v-model="user_type" name="user_type" />All users   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="3" v-model="user_type" name="user_type" />Partners &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="4" v-model="user_type" name="user_type" />County Coordinator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="7" v-model="user_type" name="user_type" />Sub-County Coordinator &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="2" v-model="user_type" name="user_type" />Participant
                                    </label>
                                   </div>
                                </div>
                                 <div class="form-group row" v-if="user_type == 4 || user_type==7">
                                    <label class="col-sm-4 form-control-label" for="title" v-if="user_type == 4">
                                        County Coordinators</label><br>
                                         <label class="col-sm-4 form-control-label" for="title" v-if="user_type==7">
                                        Sub County Coordinators</label>                                        
                                    <div class="col-sm-8">
                                        <input type="radio" :value="0" v-model="user_group" name="user_group" />All &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="1" v-model="user_group" name="user_group" /> Select County  
                                  </div>
                                 </div>
                                 <div class="form-group row" v-if="user_type == 3">    
                                    <div class="col-sm-8">
                                        <input type="radio" :value="0" v-model="partner" name="partner" />All  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="1" v-model="partner" name="partner" /> Select Partners  
                                    </div>
                                 </div>
                                 <div class="form-group row" v-if="user_type == 2">
                                    <label class="col-sm-4 form-control-label" for="title"> Participants</label>
                                    <div class="col-sm-8">
                                        <input type="radio" :value="0" v-model="participant" name="participant" /> All  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="1" v-model="participant" name="participant" /> Select County  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" :value="2" v-model="participant" name="participant" /> Search                                         
                                    </div>
                                 </div>
                                 <div class="form-group row" v-if="user_type == 3 && partner == 1">
                                    <label class="col-sm-4 form-control-label" for="title"> Partners:</label><br>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="partner_id" id="partner_id">
                                            <option selected></option>
                                            <option v-for="implementing_partner in implementing_partners" :value="implementing_partner.id">@{{ implementing_partner.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="(user_type==7 && user_group ==1) ||(user_type== 4 && user_group ==1) || (user_type == 2 && participant == 1)">
                                    <label class="col-sm-4 form-control-label" for="title"> County:</label><br>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id"  @change="fetchSubs()">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row" v-if="(user_type==7 && user_group ==1) || (user_type==2 && participant ==1) ">
                                  <label class="col-sm-4 form-control-label" for="title"> Sub County:</label>
                                    <div class="col-sm-8" >
                                        <select class="form-control c-select" name="sub_county_id" id="sub_county_id"  @change="fetchFacilities()">
                                            <option selected></option>
                                            <option v-for="sub in subs" :value="sub.id">@{{ sub.value }}</option>
                                        </select>
                                   </div>
                                  </div>
                                  <div  class="form-group row" v-if="user_type == 2 && participant==1 ">
                                    <label class="col-sm-4 form-control-label" for="title"> Facilities:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="facility_id" >
                                            <option selected></option>
                                            <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option> 
                                        </select>
                                   </div>
                                </div>
                                <div class="form-group row" v-show="user_type == 2 && participant == 2">
                                    <div class="col-md-12" style="text-align: right;">
                                        <label class="col-sm-4 form-control-label" for="title">Search</label>
                                        <div class="col-md-4" style="padding-bottom:10px;">
        		                            <div class="input-group input-group-sm">
        		                                <input type="text" class="form-control" placeholder="Search for..." v-model="query" v-on:keyup.enter="search()">
        		                                <span class="input-group-btn">
        		                                    <button class="btn btn-secondary" type="button" @click="search()" v-if="!loading"><i class="fa fa-search"></i></button>
        		                                    <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
        		                                </span>
        		                            </div>
        		                        </div>
                                        <table id ="table" class="table table-bordered table-responsive">
                                            <tr>                                               
                                                <th>Participant</th>
                                                <th>UID</th>
                                                <th>Facility</th>                                        
                                                <th>Phone</th>                                        
                                            </tr>
                                            <tr v-for="participant in participants">
                                                <td><input type="radio"  :value="participant.id" v-model="participant_id" name="participant_id" ></td>
                                                <td>@{{ participant.name }}</td>
                                                <td>@{{ participant.uid }}</td>
                                                <td>@{{ participant.fac }}</td>                                       
                                                <td>@{{ participant.phone }}</td>          
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                 <div class="form-group row col-sm-offset-4 col-sm-8" >
                                    <button  class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Next</button>
                                    <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                                </div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resend Messages-->
    <div class="modal fade" id="resend-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Message Users</h4>
                    <h5 class="modal-title" id="myModalLabel">Step Two: Send Message</h5>
                </div>
                <div class="modal-body">
                    <div class="row" >
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="resendMessages()" id="resend_message" data-vv-scope="resend_message">
                            <input type="hidden" name="phone_numbers[]" v-bind:value="phone_numbers">
                            <div class="col-md-12">    
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title"> From</label>                                                               
                                    <div class="col-sm-8">@{{from}}</div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title"> To</label>                                                              
                                    <div class="col-sm-8">@{{to}}</div>                       
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title"> Message</label>                                                               
                                    <div class="col-sm-8">
                                        <textarea class="form-group form-control" v-model="message_to_send" name="message_to_send">@{{message_to_send}}</textarea>
                                    </div>
                                </div> 
                                 <div class="form-group row col-sm-offset-4 col-sm-8" >
                                    <button  class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Send</button>
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
<style >
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}

.selected {
    background-color: #808080;
    color: #FFF;
}
</style>

@endsection
