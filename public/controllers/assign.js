Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-assignments',

    data: {
        roles: [],
        users: [],
        checks: [],
        counties: [],
        partners: [],
        selected:'',
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
        this.getVueAssignments(this.pagination.current_page);
        this.loadCounties();
        // this.loadSubcounties();
        this.loadPartners();
        this.checkedRole();
        // this.loadPrograms();
    },

    methods : {

        getVueAssignments: function(page){
            this.$http.get('/vueassigns?page='+page).then((response) => {
                this.users = response.data.users.data;
                this.roles = response.data.roles;
                this.checks = response.data.checks;
                this.pagination = response.data.pagination;
            });
        },

        createAssignment: function(page){
            let myForm = document.getElementById('update_assignments');
            let formData = new FormData(myForm);
            this.$http.post('/vueassigns', formData).then((response) => {
                toastr.success('Roles Assigned Successfully.', 'Success Alert', {timeOut: 5000});
                this.getVueAssignments();
            }, (response) => {
                this.formErrors = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueAssignments(page);
        },
        //Populate counties from FacilityController
        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        //Populate partners from ShipperController
        loadPartners: function() {
            this.$http.get('/shpprs/2').then((response) => { 
                this.partners = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        checkedRole: function(id) {
            if (id ==3) {
                $('#county').hide();
                $('#partner').show();
            }else if(id ==4){
                $('#county').show();
                $('#partner').hide();
            }else{
                $('#county').hide();
                $('#partner').hide();
            }        
        },
    }
});