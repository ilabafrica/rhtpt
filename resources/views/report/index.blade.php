@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.reports') !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-report">
    <div class="row" v-if = "role !=2" >
        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="" class="form-inline hidden">
            <div class="col-lg-12 margin-tb">
                <div class="row">
                    <div class="pull-left col-sm-12">
                        <label>Filter by: </label>
                        <button data-toggle="collapse" class="btn btn-success btn-sm" data-target="#round">Round</button>
                        <button data-toggle="collapse" class="btn btn-success btn-sm" data-target="#region">Region</button>
                        <!-- <button data-toggle="collapse" class="btn btn-success btn-sm" data-target="#feedback_status_">Feedback</button> -->
                        <button class="btn btn-sm btn-alizarin" type="submit" @click="filter(1)" v-if="!loading">Filter </button>
                        <button class="btn btn-sm btn-alizarin" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </div>
                </div>
                <div id="round" class="collapse">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="round_start">Round:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="round_start" v-model="round_start">
                                    <option selected></option>
                                    <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="round_end">To:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="round_end" v-model="round_end">
                                    <option selected></option>
                                    <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="region" class="collapse">
                    <div class="row">
                        <div v-if = "role == 1 || role ==3" class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="title">Counties:</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="county" id="county_id" @change="loadSubcounties()" v-model="county">
                                    <option selected></option>
                                   <option v-for="county in counties" :value="county.id">@{{ county.value }}</option>                         
                                </select>
                            </div>
                        </div>
                        <div v-if = "role == 1 || role ==3 || role == 4" class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="title">Sub Counties:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="sub_county" id="sub_id" @change="loadFacilities()" v-model="sub_county">
                                    <option selected></option>
                                   <option  v-for="sub in subcounties" :value="sub.id">@{{ sub.value }}</option>                         
                                </select>
                            </div>
                        </div>
                        <div v-if = "role == 1 || role ==3 || role == 4 || role ==7" class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="title">Facilities:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="facility" v-model="facility">
                                    <option selected></option>
                                    <option v-for="facility in facilities" :value="facility.id">@{{ facility.value }}</option> 
                                </select>
                            </div>
                        </div>                                
                    </div>
                </div> 
                <div id="feedback_status_" class="collapse">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="col-sm-4 form-control-label" for="title">Feedback:</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="feedback_status" v-model = "feedback_status" id="feedback_status_id">
                                    <option selected></option>
                                       <option value="0">Satisfactory</option>                         
                                       <option value="1">Unsatisfactory</option>                         
                                </select>
                            </div>
                        </div>                    
                                       
                    </div>
                </div>
            </div>
        </form>
    </div>
    <br />
    <br />
    <div class="card">
        <div class="card-block">
            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th>Enrollment</th>
                    <th>Response</th>
                    <th>Satisfactory</th>
                    <th>Unsatisfactory</th>
                </tr>
                <tr v-for="tally in tallies">
                    <td>@{{ tally.round }}</td>
                    <td>@{{ tally.enrolment }}</td>
                    <td>@{{ tally.response }} (@{{ (tally.response / tally.enrolment * 100).toFixed(2) }}%)</td>
                    <td>@{{ tally.satisfactory }} (@{{ (tally.satisfactory / tally.response * 100).toFixed(2) }}%) </td>
                    <td>@{{ tally.unsatisfactory }}  (@{{ (tally.unsatisfactory / tally.response * 100).toFixed(2) }}%) </td>
                </tr>
            </table>
            <div id="talliesContainer" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div class="card" style="display: none;">
        <div class="card-block">
            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th>Enrollment</th>
                    <th>Response</th>
                    <th>Satisfactory</th>
                </tr>
                <tr v-for="percentile in percentiles">
                    <td>@{{ percentile.round }}</td>
                    <td>@{{ percentile.enrolment }}</td>
                    <td>@{{ percentile.response }}</td>
                    <td>@{{ percentile.satisfactory }}</td>
                </tr>
            </table>
            <div id="persContainer" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <div class="card-block">
            <table class="table table-bordered">
                <tr>
                    <th></th>
                    <th>Incrorrect Results</th>
                    <th>Wrong Algorithm</th>
                    <th>Incompete Kit Data</th>
                    <th>Deviation from Procedure</th>
                    <th>Incomplete Other Information</th>
                    <th>Use of Expired Kits</th>
                    <th>Invalid Results</th>
                    <th>Incomplete Results</th>
                </tr>
                <tr v-for="unsperf in uns">
                    <td>@{{ unsperf.round }}</td>
                    <td>@{{ unsperf.incorrect_results }}</td>
                    <td>@{{ unsperf.wrong_algorithm }}</td>
                    <td>@{{ unsperf.incomplete_kit_data }}</td>
                    <td>@{{ unsperf.deviation_from_procedure }}</td>
                    <td>@{{ unsperf.incomplete_other_information }}</td>
                    <td>@{{ unsperf.use_of_expired_kits }}</td>
                    <td>@{{ unsperf.invalid_results }}</td>
                    <td>@{{ unsperf.incomplete_results }}</td>
                </tr>
            </table>
            <div id="unsPerfContainer" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
        </div>
    </div>
</div>
@endsection