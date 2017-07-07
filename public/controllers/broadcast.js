Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-broadcasts',

        data: {
        broadcasts: [],
        rounds: [],
        notifications: [],
        counties: [],
        template: '',
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
        newSMS : {'round_id':'','notification_id':'','text':'','county':[]},
        fillSMS : {'round_id':'','notification_id':'','text':'','county':[]},
        loading: false,
        error: false,
        query: '',
        srch: '',
        users: []    
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
  		this.getVueBroadcasts(this.pagination.current_page);
        this.loadRounds();
        this.loadCounties();
        this.loadNotifications();
    },

    methods : {

        getVueBroadcasts: function(page){
            this.$http.get('/vuebroadcasts?page='+page).then((response) => {
                this.broadcasts = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },
        broadcastSMS: function(){
            var input = this.newSMS;
            this.$http.post('/vuebroadcasts',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newSMS = {'round_id':'','notification_id':'','text':'','county':[]};
                $("#compose-sms").modal('hide');
                toastr.success('SMS Sent Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },

        editSettings: function(code, username, api_key){
            this.fillSettings.code = code;
            this.fillSettings.username = username;
            this.fillSettings.api_key = api_key;
            $("#edit-settings").modal('show');
        },

        updateSettings: function(){
            var input = this.fillSettings;
            this.$http.post('/bulk/api', input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.fillSettings = {'code':'','username':'','api_key':''};
                $("#edit-settings").modal('hide');
            toastr.success('Bulk SMS Settings Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueBroadcasts(page);
        },

        loadRounds: function() {
            this.$http.get('/rnds').then((response) => {
                this.rounds = response.data;

            }, (response) => {
                // console.log(response);
            });
        },

        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;

            }, (response) => {
                // console.log(response);
            });
        },

        loadNotifications: function() {
            this.$http.get('/ntfctns').then((response) => {
                this.notifications = response.data;

            }, (response) => {
                // console.log(response);
            });
        },

        loadTemplate: function() {
            let id = this.newSMS.notification_id;
            this.$http.get('/tmplt/'+id).then((response) => {
                this.template = response.data;
                $( "#text" ).val(this.template);
                $( "#text" ).trigger('change');

            }, (response) => {
                // console.log(response);
            });
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the broadcasts array so we can fill it with the new broadcasts.
            this.broadcasts = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_broadcast?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the broadcasts array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.broadcasts = response.data.data.data;
                    this.pagination = response.data.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        },

        find: function() {
            // Clear the error message.
            this.error = '';
            // Empty the users array so we can fill it with the new users.
            this.users = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_participant?q=' + this.srch).then((response) => {
                // If there was an error set the error message, if not fill the users array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.users = response.data.data.data;
                    this.pagination = response.data.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.srch = '';
            });
        }
    }
});