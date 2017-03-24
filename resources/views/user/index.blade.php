@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-users"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.user', 2) !!}</li>
        </ol>
    </div>
</div>
<div class="" id="manage-user">
    <!-- User Listing -->
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> {!! trans_choice('messages.user', 2) !!}
        
                @permission('create-user')
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-user" >
                        <i class="fa fa-plus-circle"></i>
                        {!! trans('messages.add') !!}
                    </button>
                @endpermission
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                        <i class="fa fa-step-backward"></i>
                        {!! trans('messages.back') !!}
                    </a></h5>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Phone</th>
            <th>U-ID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr v-for="user in users">
            <td>@{{ user.name }}</td>
            <td>@{{ user.gender==0?'Male':'Female' }}</td>
            <td>@{{ user.phone }}</td>
            <td>@{{ user.uid }}</td>
            <td>
                <button v-if="user.deleted_at==NULL" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                <button v-if="user.deleted_at!=NULL" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
            </td>
            <td>	
                <button v-bind="{ 'disabled': user.deleted_at!=NULL}" class="btn btn-sm btn-primary"  @click.prevent="editUser(user)">Edit</button>
                <button v-if="user.deleted_at!=NULL" class="btn btn-sm btn-success" @click.prevent="restoreUser(user)">Enable</button>
                <button v-if="user.deleted_at==NULL" class="btn btn-sm btn-alizarin" @click.prevent="deleteUser(user)">Disable</button>
            </td>
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

    <!-- Create User Modal -->
    <div class="modal fade" id="create-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Create User</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createUser">

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" v-model="newUser.name" placeholder="e.g. John Doe" />
                        <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="username">UID for Tester:</label>
                        <input type="text" name="username" class="form-control" v-model="newUser.username" placeholder="e.g. 123456" />
                        <span v-if="formErrors['username']" class="error text-danger">@{{ formErrors['username'] }}</span>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="male" value="0" v-model="newUser.gender">
                        <label for="male">Male</label>
                        <br>
                        <input type="radio" id="female" value="1" v-model="newUser.gender">
                        <label for="female">Female</label>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" name="phone" class="form-control" v-model="newUser.phone" />
                        <span v-if="formErrors['phone']" class="error text-danger">@{{ formErrors['phone'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" class="form-control" v-model="newUser.email" />
                        <span v-if="formErrors['email']" class="error text-danger">@{{ formErrors['email'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" class="form-control" v-model="newUser.address" />
                        <span v-if="formErrors['address']" class="error text-danger">@{{ formErrors['address'] }}</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>

                </form>
                
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Edit User</h4>
            </div>
            <div class="modal-body">

                <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateUser(fillUser.id)">
                   
                <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" class="form-control" v-model="fillUser.name"/>
                        <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="username">UID for Tester:</label>
                        <input type="text" name="username" class="form-control" v-model="fillUser.username"/>
                        <span v-if="formErrorsUpdate['username']" class="error text-danger">@{{ formErrorsUpdate['username'] }}</span>
                    </div>
                    <div class="form-group">
                        <input type="radio" id="male" value="0" v-model="fillUser.gender">
                        <label for="male">Male</label>
                        <br>
                        <input type="radio" id="female" value="1" v-model="fillUser.gender">
                        <label for="female">Female</label>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" name="phone" class="form-control" v-model="fillUser.phone" />
                        <span v-if="formErrorsUpdate['phone']" class="error text-danger">@{{ formErrorsUpdate['phone'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" class="form-control" v-model="fillUser.email" />
                        <span v-if="formErrorsUpdate['email']" class="error text-danger">@{{ formErrorsUpdate['email'] }}</span>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" class="form-control" v-model="fillUser.address" />
                        <span v-if="formErrorsUpdate['address']" class="error text-danger">@{{ formErrorsUpdate['address'] }}</span>
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