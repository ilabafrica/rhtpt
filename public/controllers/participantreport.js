Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});

new Vue({

    el: '#participant-report',

    data: {
        usercounts: [],
        counties: [],
        subcounties: [],
        facilities: [],
        loading: false,
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
        total: '',
        facility: '',
        sub_county: '',
        county: '',
        role: '',

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
        this.getRegisteredParticipants(this.pagination.current_page);
        this.loadCounties();

    },

    methods : {

        getRegisteredParticipants: function(page){
            var input = {'page': page, 'county': this.county, 'subcounty': this.sub_county, 'facility': this.facility};
            this.loading = true;

            this.$http.post('/getparticipantcounts',input).then((response) => {
                if(response.data.data)
                {
                    this.usercounts = response.data.data.data;
                    this.role = response.data.role;
                    this.total = response.data.total_users;
                    this.pagination = response.data.pagination;
                }
                this.loading = false;
            }, (response) => {
                this.formErrors = response.data;
                this.loading = false;
            });
        },
        
        changePage: function (page) {
            this.pagination.current_page = page;
            this.getRegisteredParticipants(page);
        },

        //Populate counties from FacilityController
        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;
                this.jimbo = response.data;
            }, (response) => {
            });
        },

        // Populate subcounties from FacilityController
        loadSubcounties: function() {
            console.log('County ID: ' + this.county);
            this.$http.get('/subs/'+ this.county).then((response) => { 
                this.subcounties = response.data;
            }, (response) => {
            });
        }, 

        // Populate facilities from FacilityController
        loadFacilities: function() {
            this.$http.get('/fclts/' + this.sub_county).then((response) => { 
                this.facilities = response.data;
            }, (response) => {
            });
        },

    }
});