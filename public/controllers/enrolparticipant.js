Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({
    el: '#manage-participant-round',

    data: {
        rounds: [],
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
        newRound : {'name':'','description':'','start_date':'','duration':'','end_date':''},
        fillRound : {'name':'','description':'','start_date':'','duration':'','end_date':'','id':''},
        loading: false,
        error: false,
        checked:false,
        query: '',
        participants: [],
        testerparticipants:[],
        srchloadedprt:'',
        srchParticipant: '',
        enrollments: [],
        esrch: '',
        date: '',
        testers: [],
        durations: [],
        duplicates: [],
        roundId:'',
        uploadify: {id: '', excel: ''}
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
        this.getDurations();
    },

    methods : {  
        getParticipants: function(roundID) {            
            this.$http.get('/loadparticipants/'+roundID ).then((response) => {
                this.testerparticipants = response.data.data;
                console.log(this.testerparticipants); 
                // this.pagination = response.data.pagination;
                this.roundId = roundID;
                this.checked = true;
            }, (response) => {
                // 
            });
        },      
        srchPrtEnrol: function() {
            // Clear the error message.
            this.error = '';
            // Empty the testerparticipants array so we can fill it with the new participants.
            this.testerparticipants = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the srchloadedprt query to it.
            this.$http.get('/api/search_participant?q=' + this.srchloadedprt).then((response) => {
                // If there was an error set the error message, if not fill the participant array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.testerparticipants = response.data.data.data;
                    this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the srchloadedprt query.
                this.srchloadedprt = '';
                // The participants who are checked won't be enrolled;
                this.checked = true;
            });
        },

        enrolParticipants: function(){
		    let myForm = document.getElementById('partFrms');
            let formData = new FormData(myForm);
            console.log(formData);
            this.$http.post('/enrol', formData).then((response) => {
                this.changePage(this.pagination.current_page);
                //$("#enrol-participants").modal('hide');
                $("#load-participants").modal('hide');
                toastr.success('Participant(s) Enrolled Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
  	    },               
    }
});
