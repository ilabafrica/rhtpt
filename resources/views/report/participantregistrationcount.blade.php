@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> Participants</li>
        </ol>
    </div>
</div>
<div class="" id="participant-report">
    <!-- User Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <h5><i class="fa fa-book"></i> Participants</h5>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-9">
                <h6><b>Service Providers</b>: Total Registered - @{{total}} Active - @{{active}} Enrolled - @{{enrolled}}</h6> 
            </div>
        </div>
    </div>
    <br/>
    @if(session()->has('error'))
        <div class="alert alert-info">{!! session()->get('error') !!}</div>
    @endif
     <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="row">
                    <form @submit.prevent="getRegisteredParticipants(1)">
                    <div v-if = "role == 1 || role == 3" class="col-sm-3">
                        <label class="col-sm-4 form-control-label" for="title">Counties:</label>
                        <div class="col-sm-6">
                            <select v-if = "role == 1" class="form-control" name="county" id="county_id" @change="loadSubcounties" v-model="county" required>
                                <option selected></option>
                                <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                            </select>
                            <select v-if = "role == 3" class="form-control" name="county" id="county_id" @change="loadSubcounties" v-model="county" required>
                                <option selected></option>
                                <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>
                            </select>
                        </div>
                    </div>
                    <div v-if = "role == 1 || role == 3 || role == 4" class="col-sm-3">
                        <label class="col-sm-4 form-control-label" for="title">Sub Counties:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="sub_county" id="sub_id" @change="loadFacilities" v-model="sub_county">
                                <option selected></option>
                               <option  v-for="sub in subcounties" :value="sub.id">@{{ sub.value }}</option>                         
                            </select>
                        </div>
                    </div>
                    <div v-if = "role == 1 || role == 3 || role == 4 || role ==7" class="col-sm-3">
                        <label class="col-sm-4 form-control-label" for="title">Facilities:</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="facility" v-model="facility">
                                <option selected></option>
                                <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option> 
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-sm btn-alizarin" type="submit" v-if="!loading">Filter </button>
                        <button class="btn btn-sm btn-alizarin" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </div>
                    </form>                
                </div>
            </div>
    </div>

    <div class="my-loading-container" v-if="loading">
        <div class="loading">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <span class="sr-only">Loading...</span>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">County</th>
            <th rowspan="2">Sub-county</th>
            <th colspan="3">Service Providers</th>
        </tr>
        <tr>
            <th>Total</th>
            <th>Active</th>
            <th>Current Enrolment</th>
        </tr>
        <tr v-for="(subcounty, key) in usercounts">
            <td>@{{ key + 1 + ((pagination.current_page - 1) * pagination.per_page) }}</td>
            <td>@{{ subcounty.county }}</td>
            <td>@{{ subcounty.subcounty }}</td>
            <td>@{{ subcounty.total}}</td>
            <td>@{{ subcounty.active}}</td>
            <td>@{{ subcounty.current_enrolment}}</td>
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
</div>
@endsection
