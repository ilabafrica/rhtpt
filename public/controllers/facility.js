Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-facility',

    data: {
        facilitys: [],
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
        newFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':''},
        fillFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''},
        loading: false,
        error: false,
        query: '',
        uploadify: {excel: ''}
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
        this.getVueFacilitys(this.pagination.current_page);
    },

    methods : {
        getVueFacilitys: function(page){
            this.$http.get('/vuefacilitys?page='+page).then((response) => {
                this.facilitys = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createFacility: function(){
            var input = this.newFacility;
            this.$http.post('/vuefacilitys',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newFacility = {'name':'','description':'', 'order':'', 'tag':'', 'options':''};
                $("#create-facility").modal('hide');
                toastr.success('Facility Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },

        deleteFacility: function(facility){
            this.$http.delete('/vuefacilitys/'+facility.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Facility Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreFacility: function(facility){
            this.$http.patch('/vuefacilitys/'+facility.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Facility Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editFacility: function(facility){
            this.fillFacility.name = facility.name;
            this.fillFacility.id = facility.id;
            this.fillFacility.description = facility.description;
            this.fillFacility.order = facility.order;
            this.fillFacility.tag = facility.tag;
            this.fillFacility.options = facility.options;
            $("#edit-facility").modal('show');
        },

        updateFacility: function(id){
            var input = this.fillFacility;
            this.$http.put('/vuefacilitys/'+id,input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.fillFacility = {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''};
                $("#edit-facility").modal('hide');
                toastr.success('Facility Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueFacilitys(page);
        },

        search: function() 
        {
            // Clear the error message.
            this.error = '';
            // Empty the facilitys array so we can fill it with the new facilitys.
            this.facilitys = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_facility?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the facilitys array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.facilitys = response.data.data.data;
                    this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        },        

        batchImport()
        {
            // this.$validator.validateAll().then(() => {
                var input = this.uploadify;
                this.$http.post('/batch/facilities', input).then((response) => {
                    this.uploadify = {'excel':''};
                    $("#upload-worksheet").modal('hide');
                    toastr.success('Facilities Loaded Successfully.', 'Success Alert', {timeOut: 5000});
                    this.errors.clear();
                }, (response) => {
                    // 
                });
            /*}).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });*/
        },

        fileChanged(e){
            console.log(e.target.files[0]);
            var fileReader = new FileReader();
            fileReader.readAsDataURL(e.target.files[0]);
            fileReader.onload = (e) => {
                this.uploadify.excel = e.target.result;
            }
        },
    }
});