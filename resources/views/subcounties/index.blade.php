@extends('app')
@section('content')
<div class="row">
  <div class="col-sm-12">
      <ol class="breadcrumb">
      	<li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
      	<li class="active"><i class="fa fa-group"></i>{!! trans('messages.facility-catalog') !!}</li>
      	<li class="active"><i class="fa fa-cube"></i>
      	{!! trans('messages.subcounties') !!}</li>
      </ol>
  	
  </div>	
</div>
<!--lits subcounties-->
<div class="" id="sub-county">
	<div class="row">
		<div class="pull-left col-md-8">
		<h5><i class="fa fa-book">	</i>
		 {!! trans_choice('messages.subcounties', 2) !!}
		 <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
             <i class="fa fa-step-backward"></i> {!! trans('messages.back') !!}
            </a>
            <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-subcounty"><i class="fa fa-plus-circle"></i>
            {!! trans('messages.add') !!}
            </button>
		 </h5>			
		</div> 
    <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query" v-on:keyup.enter="search()">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button" @click="search" v-if="!loading"><i class="fa fa-search"></i></button>
                        <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </span>
                </div>
            </div>   		
	</div>
   <div class="col-lg-12 margin-tb">
                <div class="row">
                    <div class="col-sm-3">
                        <label class="col-sm-4 form-control-label" for="title">Counties:</label>
                        <div class="col-sm-6">
                            <select class="form-control" name="county" id="county_ids"  v-model="county">
                                <option selected></option>
                               <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>                         
                            </select>
                        </div>
                    </div>                                       
                    <div class="col-sm-3">
                        <button class="btn btn-sm btn-alizarin" type="submit" @click="filter_by_region()" v-if="!loading">Filter </button>
                        <button class="btn btn-sm btn-alizarin" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </div>                
                </div>
            </div>
	<table class="table table-bordered">      
      <tr>
        <th>County</th>
        <th>Sub County</th>        
        <th>Action</th>
      </tr>  
      <tr v-for="subcounty in subcounties">
        <td>@{{subcounty.counties}}</td>
        <td>@{{subcounty.name}}</td>
        <td>           
                <button v-bind="{ 'disabled': subcounty.deleted_at}" class="btn btn-sm btn-primary" @click.prevent="editSubcounty(subcounty)"><i class="fa fa-edit"></i> Edit</button>
                <button v-if="subcounty.deleted_at" class="btn btn-sm btn-success" @click.prevent="restoreSubcounty(subcounty)"><i class="fa fa-toggle-on"></i> Enable</button>
                <button v-if="!subcounty.deleted_at" class="btn btn-sm btn-danger" @click.prevent="deleteSubcounty(subcounty)"><i class="fa fa-power-off"></i> Disable</button>           
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
	<!-- Create sub Counties Modal-->
 <div class="modal fade" id="create-subcounty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Sub County</h4>
                </div>
                <div class="row">
                    <div class="modal-body">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createSubcounty('create_Subcounty')" id="create_subcounty" data-vv-scope="create_Subcounty">
                            <div class="col-md-12">
                             <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" v-model="newSubcounty.county_id">
                                            <option selected></option>
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                    </div>
                                </div>                            
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label"  :class="{'help is-danger': errors.has('create_Subcounty.name') }" for="name">Name:</label>
                                    <div class="col-sm-8" :class="{ 'control': true }">
                                        <input v-validate="'required|alpha_spaces'" class="form-control" :class="{'input': true, 'is-danger': errors.has('create_Subcounty.name') }" name="name" type="text" placeholder="" v-model="newSubcounty.name" />
                                        <span v-show="errors.has('create_Subcounty.name')" class="help is-danger">@{{ errors.first('create_Subcounty.name') }}</span>
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

	<!--Edit Sub Counties Modal-->
    <div class="modal fade" id="edit-subcounty" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Sub County</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateSubcounty(fillSubcounty.id, 'update_subcounty')" data-vv-scope="update_subcounty">
                            <div class="col-md-12">   
                                 <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">County:</label>
                                    <div class="col-sm-8">
                                        <select class="form-control c-select" name="county_id" id="county_id"  v-model="fillSubcounty.county_id">
                                            <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>   
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Sub County:</label>
                                    <div class="col-sm-8">

                                        <input v-validate="'required'" class="form-control" :class="{'input': true, 'is-danger': errors.has('subcounty') }" name="subcounty" type="text" placeholder="" v-model="fillSubcounty.name" />
                                        <span v-show="errors.has('subcounty')" class="help is-danger">@{{ errors.first('subcounty') }}</span>


                                    </div>
                                </div>                           
                            </div>
                            <div class="form-group row col-sm-offset-4 col-sm-8">
                                <button class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
                                <button type="button" class="btn btn-sm btn-silver" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</span></button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
