<template>
    <div id="manage-programs">
    <div class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li><a href="home"><i class="fa fa-home"></i> Home</a></li>
                <li class="active"><i class="fa fa-cubes"></i> Proficiency Testing</li>
                <li class="active"><i class="fa fa-cube"></i> Programs</li>
            </ol>
        </div>
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h5><i class="fa fa-book"></i> Programs
                    <button type="button" class="btn btn-sm btn-belize-hole" data-toggle="modal" data-target="#create-program">
                        <i class="fa fa-plus-circle"></i> Add New
                    </button>
                    <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="Back" title="Back">
                        <i class="fa fa-step-backward"></i> Back
                    </a>
                </h5>
            </div>
        </div>
        <table class="table table-bordered">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <tr v-for="program in programs">
                <td>@{{ program.name }}</td>
                <td>@{{ program.description }}</td>
                <td>
                    <button v-if="program.deleted_at==NULL" class="mbtn mbtn-raised mbtn-success mbtn-xs">Active</button>
                    <button v-if="program.deleted_at!=NULL" class="mbtn mbtn-raised mbtn-primary mbtn-xs">Inactive</button>
                </td>
                <td>	
                    <button v-bind="{ 'disabled': program.deleted_at!=NULL}" class="btn btn-sm btn-primary" @click.prevent="editProgram(program)"><i class="fa fa-edit"></i> Edit</button>
                    <button v-if="program.deleted_at!=NULL" class="btn btn-sm btn-success" @click.prevent="restoreProgram(program)">Enable</button>
                    <button v-if="program.deleted_at==NULL" class="btn btn-sm btn-alizarin" @click.prevent="deleteProgram(program)">Disable</button>
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
        <!-- Create Program Modal -->
        <div class="modal fade" id="create-program" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title" id="myModalLabel">Create Program</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="createProgram">

                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="name" class="form-control" v-model="newProgram.name" />
                                            <span v-if="formErrors['name']" class="error text-danger">@{{ formErrors['name'] }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                        <div class="col-sm-8">
                                            <textarea name="description" class="form-control" v-model="newProgram.description"></textarea>
                                            <span v-if="formErrors['description']" class="error text-danger">@{{ formErrors['description'] }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group row col-sm-offset-4 col-sm-8">
                                        <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
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
    <!-- Edit Program Modal -->
    <div class="modal fade" id="edit-program" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Program</h4>
                </div>
                <div class="row">
                    <div class="modal-body">

                        <form method="POST" enctype="multipart/form-data" v-on:submit.prevent="updateProgram(fillProgram.id)">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Title:</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="name" class="form-control" v-model="fillProgram.name" />
                                        <span v-if="formErrorsUpdate['name']" class="error text-danger">@{{ formErrorsUpdate['name'] }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 form-control-label" for="title">Description:</label>
                                    <div class="col-sm-8">
                                        <textarea name="description" class="form-control" v-model="fillProgram.description"></textarea>
                                        <span v-if="formErrorsUpdate['description']" class="error text-danger">@{{ formErrorsUpdate['description'] }}</span>
                                    </div>
                                </div>
                                <div class="form-group row col-sm-offset-4 col-sm-8">
                                    <button type="submit" class="btn btn-sm btn-success"><i class='fa fa-plus-circle'></i> Submit</button>
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
</template>

<script>
    import swal from 'sweetalert'

    export default{
        data(){
            return{
                programs:[],
                pagination: {
                    total: 0, 
                    per_page: 2,
                    from: 1, 
                    to: 0,
                    current_page: 1
                },
                offset: 4,
                formErrors:{},
                formErrorsUpdate:{},
                newProgram : {'name':'','description':''},
                fillProgram : {'name':'','description':'','id':''}
            }
        },

        computed: {
            authenticatedUser(){
                return this.$auth.getAuthenticatedUser()
            },
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },

        mounted(){
            this.getPrograms(this.pagination.current_page);
        },

        methods: {
            getPrograms: function(page){
                this.$http.get('/programs').then(response => {
                    this.$set('programs', response.data.data.data);
                    this.$set('pagination', response.data.pagination);
                    console.log(response);
                });
            },
            deleteProgram: function(program){
                this.$http.delete('/vueprograms/'+program.id).then((response) => {
                    this.changePage(this.pagination.current_page);
                    toastr.success('Program Deleted Successfully.', 'Success Alert', {timeOut: 5000});
                });
            },

            restoreProgram: function(program){
                this.$http.patch('/vueprograms/'+program.id+'/restore').then((response) => {
                    this.changePage(this.pagination.current_page);
                    toastr.success('Program Restored Successfully.', 'Success Alert', {timeOut: 5000});
                });
            },

            editProgram: function(program){
                this.fillProgram.name = program.name;
                this.fillProgram.id = program.id;
                this.fillProgram.description = program.description;
                $("#edit-program").modal('show');
            },

            updateProgram: function(id){
                var input = this.fillProgram;
                this.$http.put('/vueprograms/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillProgram = {'name':'','description':'','id':''};
                    $("#edit-program").modal('hide');
                    toastr.success('Program Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            },
            deleteProgram(program){
                swal({   
                    title: "Are you sure?",   
                    text: "You will not be able to recover this peoduct!",   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Yes, delete it!",   
                    closeOnConfirm: false 
                }, 
                function(){   
                    this.$http.delete('api/programs/' + program.id).then(response => {
                        let index = this.programs.indexOf(program);
                        this.programs.splice(index, 1)
                        swal("Deleted!", "Your Program has been deleted.", "success"); 
                    });
                }.bind(this));
            }
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getPrograms(page);
        }
    }
</script>