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
    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateReport" class="form-inline hidden">
        <div class="col-md-12" style="display: none;">
            <div class="form-group">
                <label class="col-sm-4 form-control-label" for="from">Round:</label>
                <div class="col-sm-8">
                    <select class="form-control c-select" name="from">
                        <option selected></option>
                        <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 form-control-label" for="to">To:</label>
                <div class="col-sm-8">
                    <select class="form-control c-select" name="to">
                        <option selected></option>
                        <option v-for="round in rounds" :value="round.id">@{{ round.value }}</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-refresh'></i> Submit</button>
        </div>
    </form>
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
    <div class="card">
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