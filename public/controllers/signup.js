Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-registration',

    data: {
        formErrors:{},
        newParticipant : {'fname':'','oname':'','surname':'','gender':'','email':'','phone':'','address':'','program':'','designation':'','county':'','sub_county':'','mfl_code':'','facility':'','in_charge':'','in_charge_email':'','in_charge_phone':''},
        sexes: [],
        programs: [],
        counties: [],
        designations: [],
        verification: {'code': ''},
        warning: '',
        info: '',
        success: '',
        subs: [],
        facilities: [],
        formErrors:{},
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
                    this.newParticipant = {'fname':'','oname':'','surname':'','gender':'','email':'','phone':'','address':'','program':'','designation':'','county':'','sub_county':'','mfl_code':'','facility':'','in_charge':'','in_charge_email':'','in_charge_phone':''};
                    this.phone = response.data.phone;
                    // location.href = '/2fa';
                    toastr.success('Registered Successfully.', 'Success Alert', {timeOut: 5000});
                    window.location.replace("/2fa");
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
            var input = this.verification;
            this.$http.post('/token', input).then((response) => {
                if(response.data.warning)
                {
                    this.warning = response.data.warning;
                    swal("Alert!", this.warning, "warning")
                    // toastr.warning(this.warning, 'Notification', {timeOut: 5000});
                }
                else if(response.data.info)
                {
                    this.info = response.data.info;
                    swal("Alert!", this.info, "info")
                    // toastr.info(this.warning, 'Notification', {timeOut: 5000});
                }
                else if(response.data.success)
                {
                    this.success = response.data.success;
                    swal({ 
                        title: "Success!",
                        text: this.success,
                        type: "success" 
                        },
                        function(){
                            window.location.replace("/login");
                        }
                    );
                }
            });
        },

        fetchSubs: function() {
            let id = $('#county_id').val();
            // console.log(id);
            this.$http.get('/subs/'+id).then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchFacilities: function() {
            let id = $('#sub_id').val();
            this.$http.get('/mfls/'+id).then((response) => {
                this.facilities = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchFacility: function() {
            let id = $('#mfl').val();
            this.$http.get('/mfl/'+id).then((response) => {
                console.log(response);
                this.newParticipant.facility = response.data.name;
                this.newParticipant.sub_county = response.data.sub_county;
                this.newParticipant.county = response.data.county;
            });
        },

        resendVerificationCode: function() {
            this.$http.get('/resend/').then((response) => {
                // this.newParticipant.facility = response.data;
            });
        },
    }
});