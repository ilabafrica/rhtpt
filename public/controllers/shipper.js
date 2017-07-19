Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-shipper',

    data: {
        shippers: [],
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
        shipper_type: '',
        newShipper : {'name':'','shipper_type':'','contact':'','phone':'','email':''},
        fillShipper : {'name':'','shipper_type':'','contact':'','phone':'','email':'','id':''},
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
        this.getVueShippers(this.pagination.current_page);
        this.loadShipperTypes();
    },

    methods : {

        getVueShippers: function(page){
            this.$http.get('/vueshippers?page='+page).then((response) => {
                this.shippers = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createShipper: function(scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.newShipper;
                this.$http.post('/vueshippers',input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.newShipper = {'name':'','shipper_type':'','contact':'','phone':'','email':''};
                    $("#create-shipper").modal('hide');
                    toastr.success('Shipper Created Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrors = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        deleteShipper: function(shipper){
            this.$http.delete('/vueshippers/'+shipper.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Shipper Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreShipper: function(shipper){
            this.$http.patch('/vueshippers/'+shipper.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Shipper Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editShipper: function(shipper){
            this.fillShipper.name = shipper.name;
            this.fillShipper.id = shipper.id;
            this.fillShipper.shipper_type = shipper.shipper_type;
            this.fillShipper.contact = shipper.contact;
            this.fillShipper.phone = shipper.phone;
            this.fillShipper.email = shipper.email;
            $("#edit-shipper").modal('show');
        },

        updateShipper: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillShipper;
                this.$http.put('/vueshippers/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillShipper = {'name':'','shipper_type':'','contact':'','phone':'','email':'','id':''};
                    $("#edit-shipper").modal('hide');
                    toastr.success('Shipper Updated Successfully.', 'Success Alert', {timeOut: 5000});
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
            this.getVueShippers(page);
        },

        loadShipperTypes: function() {
            this.$http.get('/st').then((response) => {
                this.options = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the shippers array so we can fill it with the new shippers.
            this.shippers = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_shipper?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the shippers array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.shippers = response.data.data.data;
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