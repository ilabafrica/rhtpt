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
    <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateReport" class="form-inline">
        <div class="col-md-12">
            <div class="form-group">
                <label class="col-sm-4 form-control-label" for="from">From:</label>
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
                <tr>
                    <td>Round 13</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Round 14</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Round 15</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Round 16</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <div id="talliesContainer" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <h4 class="card-title">Percentiles go here</h4>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            <h4 class="card-title">Unsatisfactiry Responses go here</h4>
        </div>
    </div>
</div>
@endsection