Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-registration',

    data: {
        formErrors:{},
        newParticipant : {'name':'','gender':'','email':'','phone':'','address':'','program':'','designation':'','county':'','sub_county':'','mfl_code':'','facility':'','in_charge':'','in_charge_email':'','in_charge_phone':''},
        sexes: [],
        programs: [],
        counties: [],
        designations: [],
    },

    mounted : function(){
        this.loadSexes();
        this.loadPrograms();
        this.loadCounties();
        this.loadDesignations();
    },

    methods : {
        createParticipant: function(){
            this.$validator.validateAll().then(() => {
                var input = this.newParticipant;
                this.$http.post('/register', input).then((response) => {
                    this.newParticipant = {'name':'','gender':'','email':'','phone':'','address':'','program':'','designation':'','county':'','sub_county':'','mfl_code':'','facility':'','in_charge':'','in_charge_email':'','in_charge_phone':''};
                    this.$http.get('/signup');
                    toastr.success('Registered Successfully.', 'Success Alert', {timeOut: 5000});
                    $("#verify-phone").modal('show');
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

        loadSexes: function() {
            this.$http.get('/sex').then((response) => {
                this.sexes = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
         //Populate counties from FacilityController
        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        //    Populate programs from ProgramController
        loadPrograms: function() {
            this.$http.get('/progs').then((response) => { 
                this.programs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadDesignations: function() {
            this.$http.get('/des').then((response) => {
                this.designations = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        verifyPhone: function(){
            let myForm = document.getElementById('verify_phone');
            let formData = new FormData(myForm);
            this.$http.post('/user/token', formData).then((response) => {
                $("#verify-phone").modal('hide');
                toastr.success('Phone Verified Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },
    }
});