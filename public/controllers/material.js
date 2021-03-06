Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-material',

    data: {
        materials: [],
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
        newMaterial : {'batch':'','date_prepared':'','expiry_date':'','material_type':'','original_source':'','date_collected':'','prepared_by':''},
        fillMaterial : {'batch':'','date_prepared':'','expiry_date':'','material_type':'','original_source':'','date_collected':'','prepared_by':'','id':''},
        options: [],
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
        this.getVueMaterials(this.pagination.current_page);
        this.loadMaterialTypes();
    },

    methods : {

        getVueMaterials: function(page){
            this.$http.get('/vuematerials?page='+page).then((response) => {
                if(response.data.data)
                {
                    this.materials = response.data.data.data;
                    this.pagination = response.data.pagination;
                }                
                else
                {
                    swal("No data found for PT samples.", "", "info");
                }
            });
        },

        createMaterial: function(scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.newMaterial;
                this.$http.post('/vuematerials',input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.newMaterial = {'batch':'','date_prepared':'','expiry_date':'','material_type':'','original_source':'','date_collected':'','prepared_by':''};
                    $("#create-material").modal('hide');
                    toastr.success('Material Created Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrors = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });
        },

        deleteMaterial: function(material){
            this.$http.delete('/vuematerials/'+material.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Material Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreMaterial: function(material){
            this.$http.patch('/vuematerials/'+material.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Material Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editMaterial: function(material){
            this.fillMaterial.batch = material.batch;
            this.fillMaterial.id = material.id;
            this.fillMaterial.date_prepared = material.date_prepared;
            this.fillMaterial.expiry_date = material.expiry_date;
            this.fillMaterial.material_type = material.material_type;
            this.fillMaterial.original_source = material.original_source;
            this.fillMaterial.date_collected = material.date_collected;
            this.fillMaterial.prepared_by = material.prepared_by;
            $("#edit-material").modal('show');
        },

        updateMaterial: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillMaterial;
                this.$http.put('/vuematerials/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillMaterial = {'batch':'','date_prepared':'','expiry_date':'','material_type':'','original_source':'','date_collected':'','prepare_by':'','id':''};
                    $("#edit-material").modal('hide');
                    toastr.success('Material Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueMaterials(page);
        },

        loadMaterialTypes: function() {
            this.$http.get('/mt').then((response) => {
                this.options = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the materials array so we can fill it with the new materials.
            this.materials = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_material?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the materials array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.materials = response.data.data.data;
                    this.pagination = response.data.data.pagination;
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