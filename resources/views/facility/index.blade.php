@extends('app')
@section('content')
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
            <div class="pull-left col-md-6">
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
            @permission('update-facility')
                <button class="btn btn-sm btn-primary" @click.prevent="editFacility(facility)" disabled><i class="fa fa-edit"></i> Edit</button>
            @endpermission
            @permission('delete-facility')
                <button class="btn btn-sm btn-danger" @click.prevent="deleteFacility(facility)"><i class="fa fa-power-off"></i> Disable</button>
            @endpermission
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

    <!-- Create Facility Modal -->
    <div class="modal fade" id="create-facility" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Facility</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createv">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="name" class="form-control" v-model="newFacility.name" />
                    <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Label:</label>
                    <input type="text" name="label" class="form-control" v-model="newFacility.label" />
                    <span v-if="formErrors['label']" class="error text-danger">@{{ formErrors['label'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="newFacility.description"></textarea>
                    <span v-if="formErrors['description']" class="error text-danger">@{{ formErrors['description'] }}</span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

                </form>

            
            </div>
        </div>
        </div>
    </div>

    <!-- Edit Facility Modal -->
    <div class="modal fade" id="edit-facility" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Facility</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateFacility(fillFacility.id)">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="name" class="form-control" v-model="fillFacility.name" />
                    <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="fillFacility.description"></textarea>
                    <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Label:</label>
                    <input type="text" name="label" class="form-control" v-model="fillFacility.label" />
                    <span v-if="formErrorsUpdate['label']" class="error text-danger">@{{ formErrorsUpdate['label'] }}</span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>

                </form>

            </div>
        </div>
        </div>
    </div>

</div>

@endsection