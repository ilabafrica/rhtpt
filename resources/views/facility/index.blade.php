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
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.facility-catalog') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.facility', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-facility">
    <!-- Facility Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left col-md-8">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.facility', 2) !!}
        
                @permission('create-facility')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-facility" disabled>
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.add') !!}
                    </button>
                @endpermission
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a>
                    <!-- <a class="btn btn-sm btn-concrete" href="/Facilities.xlsx" disabled>
                        <i class="fa fa-download"></i>
                        Worksheet
                    </a> -->

                    <button class="btn btn-sm btn-nephritis" id="register" data-toggle="modal" data-target="#upload-worksheet"><i class="fa fa-level-up"></i> Upload Worksheet</button>
                </h5>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search for..." v-model="query">
                    <span class="input-group-btn">
                        <button class="btn btn-secondary" type="button" @click="search" v-if="!loading"><i class="fa fa-search"></i></button>
                        <button class="btn btn-secondary" type="button" disabled="disabled" v-if="loading">Searching...</button>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>In Charge</th>
            <th>Sub County</th>
            <th>County</th>
            <th>Action</th>
        </tr>
        <tr v-for="facility in facilitys">
            <td>@{{ facility.code }}</td>
            <td>@{{ facility.name }}</td>
            <td>@{{ facility.in_charge }}</td>
            <td>@{{ facility.sub }}</td>
            <td>@{{ facility.county }}</td>
            <td>	
            <div class="dropdown">
            <a class="dropbtn" onclick="myFunction()"  >View</a>
            <div id="Dropdown" class="dropdown-content">
            @permission('update-facility')
                <a  @click.prevent="editFacility(facility)" disabled> Edit</button>
            @endpermission
            @permission('delete-facility')
                <a @click.prevent="deleteFacility(facility)"> Disable</a>
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
    <!-- Upload worksheet -->
    <div id="upload-worksheet" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Upload Faciliies from Excel</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        
                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="batchImport" id="btch">
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
</div>
@endsection