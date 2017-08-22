Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-field',

    data: {
        attributes: [],
        tags: [],
        sets: [],
        flds: [],
        options: [],
        selected: '',
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
        newFieldSet: {'title':'','uid':'','tag':'','order':'','field_set_id':'','opts[]':''},
        frmData: {},
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
        this.getVueFields(this.pagination.current_page);
        this.loadFields();
        this.loadSets();
        this.loadTags();
        this.loadOptions();
    },

    methods : {

        getVueFields: function(page){
            this.$http.get('/vuefields?page='+page).then((response) => {
                this.attributes = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createField: function(scope){
            this.$validator.validateAll(scope).then(() => {
                // let myForm = document.getElementById('create_field');
                // let formData = new FormData(myForm);
                var input = this.newFieldSet;
                this.$http.post('/vuefields',input).then((response) => {

                if(response.data == 'error')
                {
                    this.error = response.data;
                    toastr.error(this.error, 'This Field already exists', {timeOut: 5000});
                }
                else
                {
                    this.changePage(this.pagination.current_page);
                    this.newFieldSet= {'title':'','uid':'','tag':'','order':'','field_set_id':'','opts[]':''};
                    $("#create-field").modal('hide');
                    toastr.success('Field Created Successfully.', 'Success Alert', {timeOut: 5000});
                }
                }, (response) => {
                    this.formErrors = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        deleteField: function(field){
            this.$http.delete('/vuefields/'+field.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Field Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreField: function(field){
            this.$http.patch('/vuefields/'+field.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Field Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editField: function(field){
            //    Fetch the result using the id
            let id = field.id;
            this.$http.get('/frmfld/'+id).then((response) => {
                this.frmData = response.data;
            });
            $("#edit-field").modal('show');
        },

        updateField: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillField;
                this.$http.put('/vuefields/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillField = {'uid':'','title':'', 'order':'', 'tag':'', 'field_set_id':'', 'opts[]':'','id':''};
                    $("#edit-field").modal('hide');
                    toastr.success('Field Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        loadTags: function() {
            this.$http.get('/tags').then((response) => {
                this.tags = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadSets: function() {
            this.$http.get('/preceed').then((response) => {
                this.sets = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadFields: function() {
            this.$http.get('/flds').then((response) => {
                this.flds = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadOptions: function() {
          this.$http.get('/opt').then((response) => {
              this.options = response.data;
          }, (response) => {
              // console.log(response);
          });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueFields(page);
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the fields array so we can fill it with the new fields.
            this.attributes = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_field?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the fields array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.attributes = response.data.data.data;
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