Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-program',

    data: {
      programs: [],
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
      fillProgram : {'name':'','description':'','id':''},
      loading: false,
      error: false,
      query: ''
    },

    computed: {
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

    mounted : function(){
        this.getVuePrograms(this.pagination.current_page);
    },

    methods : {
        getVuePrograms: function(page){
            this.$http.get('/vueprograms?page='+page).then((response) => {
                this.programs = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createProgram: function(scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.newProgram;
                this.$http.post('/vueprograms',input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.newProgram = {'name':'','description':''};
                    $("#create-program").modal('hide');
                    toastr.success('Program Created Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrors = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
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

        updateProgram: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillProgram;
                this.$http.put('/vueprograms/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillProgram = {'name':'','description':'','id':''};
                    $("#edit-program").modal('hide');
                    toastr.success('Program Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVuePrograms(page);
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the programs array so we can fill it with the new programs.
            this.programs = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_program?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the programs array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.programs = response.data.data.data;
                    this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        }
    }
});