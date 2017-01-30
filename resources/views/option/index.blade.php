@extends('app')
@section('content')
<div class="" id="manage-option">
    <!-- Option Listing -->
    
      <div class="card">
        <div class="card-header">
          Table
        </div>
        <div class="card-body">
            <div class="pull-left">
                <h2>Laravel Vue JS Option CRUD</h2>
            </div>
            <div class="pull-right">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-option">
                <i class="fa fa-plus-circle"></i> Create Option
            </button>
            </div>
            <table class="table table-bordered table-responsive" width="100%">
                <tr>
                    <th>Title</th>
                    <th>Label</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tr v-for="option in options">
                    <td>@{{ option.name }}</td>
                    <td>@{{ option.label }}</td>
                    <td>@{{ option.description }}</td>
                    <td>	
                        <button class="btn btn-sm btn-primary" @click.prevent="editOption(option)"><i class="fa fa-edit"></i> Edit</button>
                        <button class="btn btn-sm btn-danger" @click.prevent="deleteOption(option)"><i class="fa fa-trash-o"></i> Delete</button>
                    </td>
                </tr>
            </table>
            <hr>
            <!-- Pagination -->
            <nav>
                <ul class="pagination">
                    <li v-if="pagination.current_page > 1">
                        <a href="#" aria-label="Previous"
                            @click.prevent="changePage(pagination.current_page - 1)">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <li v-for="page in pagesNumber"
                        v-bind:class="[ page == isActived ? 'active' : '']">
                        <a href="#"
                            @click.prevent="changePage(page)">@{{ page }}</a>
                    </li>
                    <li v-if="pagination.current_page < pagination.last_page">
                        <a href="#" aria-label="Next"
                            @click.prevent="changePage(pagination.current_page + 1)">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
      </div>

    <!-- Create Option Modal -->
    <div class="modal fade" id="create-option" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Option</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createOption">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="name" class="form-control" v-model="newOption.name" />
                    <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Label:</label>
                    <input type="text" name="label" class="form-control" v-model="newOption.label" />
                    <span v-if="formErrors['label']" class="error text-danger">@{{ formErrors['label'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="newOption.description"></textarea>
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

    <!-- Edit Option Modal -->
    <div class="modal fade" id="edit-option" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Option</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateOption(fillOption.id)">

                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="name" class="form-control" v-model="fillOption.name" />
                    <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="fillOption.description"></textarea>
                    <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Label:</label>
                    <input type="text" name="label" class="form-control" v-model="fillOption.label" />
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