Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-nonperf',

    data: {
        nonperfs: [],
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
        newNonperf : {'title':'','description':''},
        fillNonperf : {'title':'','description':'','id':''},
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
        this.getVueNonperfs(this.pagination.current_page);
    },

    methods : {

        getVueNonperfs: function(page){
            this.$http.get('/vuenonperfs?page='+page).then((response) => {
                this.nonperfs = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createNonperf: function(){
            var input = this.newNonperf;
            this.$http.post('/vuenonperfs',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newNonperf = {'title':'','description':''};
                $("#create-nonperf").modal('hide');
                toastr.success('Non-performance reason Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },

        deleteNonperf: function(nonperf){
            this.$http.delete('/vuenonperfs/'+nonperf.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Non-performancw reason Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreNonperf: function(nonperf){
            this.$http.patch('/vuenonperfs/'+nonperf.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Non-performance reason Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editNonperf: function(nonperf){
            this.fillNonperf.title = nonperf.title;
            this.fillNonperf.id = nonperf.id;
            this.fillNonperf.description = nonperf.description;
            $("#edit-nonperf").modal('show');
        },

        updateNonperf: function(id){
            var input = this.fillNonperf;
            this.$http.put('/vuenonperfs/'+id,input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.fillNonperf = {'title':'','description':'','id':''};
                $("#edit-nonperf").modal('hide');
                toastr.success('Non-performance reason Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueNonperfs(page);
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the nonperfs array so we can fill it with the new nonperfs.
            this.nonperfs = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_nonperf?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the nonperfs array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.nonperfs = response.data.data.data;
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