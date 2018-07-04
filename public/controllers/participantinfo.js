Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({
    el: '#manage-participant-info',

    data: {
        participants:[],
        pagination: {
            total: 0, 
            per_page: 2,
            from: 1, 
            to: 0,
            current_page: 1
        },        
        loading: false,
        error: false,
        query: '',
        facility: '',
        sub_county: '',
        county: '',
        role: '',
        tier: '',
        counties: [],
        subs: [],
        facilities: [],        
        roundId:'',
        total_participants: '',        
        active_participants: '',        
        enrolled_participants: '',        
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
    	this.getParticipants(this.pagination.current_page);
        this.loadCounties();
    },

    methods : {  
        getParticipants: function() {  
        var round_id = _.last( window.location.pathname.split( '/' ) ); 
            this.$http.get('/loadparticipantsinfo/'+round_id ).then((response) => {
                if(response.data.data){
                    this.participants = response.data.data;
                    this.roundId = round_id;
                    this.role = response.data.role;
                    this.tier = response.data.tier;
                    this.total_participants = response.data.total_participants;
                    this.active_participants = response.data.active_participants;
                    this.enrolled_participants = response.data.enrolled_participants;
                    this.pagination = response.data.pagination;
                    
                    if (this.role == 4) {
                        let id = this.tier;
                        this.$http.get('/subs/'+id).then((response) => {
                            this.subs = response.data;
                        }, (response) => {
                            // console.log(response);
                        });
                    }
                    if (this.role == 7) {
                        let id =this.tier;
                        this.$http.get('/fclts/'+id).then((response) => {
                            this.facilities = response.data;
                        }, (response) => {
                            // console.log(response);
                        });
                    }
                }
                else
                {
                    swal("No data found for Participants.","","info");
                }
            }, (response) => {
                // 
            });
        },      
        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the participants array so we can fill it with the new participants.
            this.participants = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the search query to it.
            this.$http.get('/api/search_participant_info/'+this.roundId+'?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the participant array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.participants = response.data.data.data;
                    this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the search query.
                this.query = '';
                // The participants who are checked won't be enrolled;
                this.checked = true;
            });
        },
        
        filter_by_region: function() {
            // Clear the error message.
            this.error = '';
            // Empty the users array so we can fill it with the new users.
            this.participants = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
             //get users filtered by facility
             if (this.facility) {
                this.$http.get('/api/search_participant_info/'+this.roundId+'?facility='+ this.facility).then((response) => {
                    // If there was an error set the error message, if not fill the users array.
                    if(response.data.error)
                    {
                        this.error = response.data.error;
                        toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                    }
                    else
                    {
                        this.participants = response.data.data;
                        this.pagination = response.data.pagination;
                        this.total_participants = response.data.total_participants;
                        this.active_participants = response.data.active_participants;
                        this.enrolled_participants = response.data.enrolled_participants;
                        toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                    }
                    // The request is finished, change the loading to false again.
                    this.loading = false;
                    // Clear the query.
                    this.facility = '';
                });
            }
            
            //get users filtered by sub county

            else if (this.sub_county) {
                this.$http.get('/api/search_participant_info/'+this.roundId+'?sub_county='+ this.sub_county).then((response) => {
                    // If there was an error set the error message, if not fill the users array.
                    if(response.data.error)
                    {
                        this.error = response.data.error;
                        toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                    }
                    else
                    {
                        this.participants = response.data.data;
                        this.pagination = response.data.pagination;
                        this.total_participants = response.data.total_participants;
                        this.active_participants = response.data.active_participants;
                        this.enrolled_participants = response.data.enrolled_participants;
                        toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                    }
                    // The request is finished, change the loading to false again.
                    this.loading = false;
                    // Clear the query.
                    this.sub_county = '';
                });
            }

            //get users filtered by county

            else if (this.county) {
                this.$http.get('/api/search_participant_info/'+this.roundId+'?county=' + this.county ).then((response) => {
                    // If there was an error set the error message, if not fill the users array.
                    if(response.data.error)
                    {
                        this.error = response.data.error;
                        toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                    }
                    else
                    {
                        this.participants = response.data.data;
                        this.pagination = response.data.pagination;
                        this.total_participants = response.data.total_participants;
                        this.active_participants = response.data.active_participants;
                        this.enrolled_participants = response.data.enrolled_participants;
                        toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                    }
                    // The request is finished, change the loading to false again.
                    this.loading = false;
                    // Clear the query.
                    this.county = '';
                });
            }
           
        },
        //Populate counties from FacilityController
        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;
                this.jimbo = response.data;
            }, (response) => {
                // console.log(response);
            });
        },        
        // fetch subcounties in after selecting a county
        fetchSubs: function() {
            let id = $('#county_id').val();
            this.$http.get('/subs/'+id).then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        }, 
        // fetch facilities in one sub county
        fetchFacilities: function() {
            let id = $('#sub_id').val();
            this.$http.get('/fclts/'+id).then((response) => {
                this.facilities = response.data;
            }, (response) => {
                // console.log(response);
            });
        },          
    }
});
