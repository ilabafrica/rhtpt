@extends('app')
@section('content')
<div class="" id="manage-event">
    <!-- Event Listing -->
    
      <div class="card">
        <div class="card-header">
          Table
        </div>
        <div class="card-body">
            <div class="pull-left">
                <h2>Laravel Vue JS Event CRUD</h2>
            </div>
            <div class="pull-right">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-event">
                Create Event
            </button>
            </div>
            <table class="table table-bordered table-responsive" width="100%">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                <tr v-for="event in events">
                    <td>@{{ event.title }}</td>
                    <td>@{{ event.description }}</td>
                    <td>	
                        <button class="btn btn-primary" @click.prevent="editEvent(event)">Edit</button>
                        <button class="btn btn-danger" @click.prevent="deleteEvent(event)">Delete</button>
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

    <!-- Create Event Modal -->
    <div class="modal fade" id="create-event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create Event</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createEvent">

                    <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" v-model="newEvent.title" />
                    <span v-if="formErrors['title']" class="error text-danger">@{{ formErrors['title'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="newEvent.description"></textarea>
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

    <!-- Edit Event Modal -->
    <div class="modal fade" id="edit-event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit Event</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateEvent(fillEvent.id)">

                    <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" class="form-control" v-model="fillEvent.title" />
                    <span v-if="formErrorsUpdate['title']" class="error text-danger">@{{ formErrorsUpdate['title'] }}</span>
                </div>

                <div class="form-group">
                    <label for="title">Description:</label>
                    <textarea name="description" class="form-control" v-model="fillEvent.description"></textarea>
                    <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
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