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
        newFacility : {'code':'', 'name':'','registration_number':'','mailing_address':'', 'in_charge':'', 'in_charge_phone':'', 'in_charge_email':'','sub_county_id':''},
        fillFacility : {'registration_number':'', 'name':'', 'mailing_address':'', 'in_charge':'', 'in_charge_phone':'', 'in_charge_email':'','sub_id':''},
        loading: false,
        error: false,
        query: '',
        uploadify: {excel: ''},
        sub_county: '',
        county: '',
        role: '',
        tier: '',
        counties: [],
        subs: [],
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
        this.loadCounties();
        this.getVueFacilitys(this.pagination.current_page);
    },

    methods : {
        getVueFacilitys: function(page){
            this.$http.get('/vuefacilitys?page='+page).then((response) => {
                if(response.data.data)
                {
                    this.facilitys = response.data.data.data;
                    this.role = response.data.role;
                    this.tier = response.data.tier;
                    this.pagination = response.data.pagination;

                    if (this.role == 3) {
                        let id = this.tier;
                        this.$http.get('/partner_counties/'+id).then((response) => {
                            this.counties = response.data;
                        }, (response) => {
                            console.log(response);
                        });
                    }
                    if (this.role == 4) {
                        let id = this.tier;
                        this.$http.get('/subs/'+id).then((response) => {
                            this.subs = response.data;
                        }, (response) => {
                            // console.log(response);
                        });
                    }
                }
                else
                {
                    swal("No data found for Facilities.","","info");
                }

            });
        },

        createFacility: function(){
            
            var input = this.newFacility;
            this.$http.post('/vuefacilitys',input).then((response) => {
                
                this.changePage(this.pagination.current_page);
                this.newFacility = {'code':'', 'name':'','registration_number':'','mailing_address':'', 'in_charge':'', 'in_charge_phone':'', 'in_charge_email':'','sub_county_id':''};                
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
            this.fillFacility = facility;
            this.subs = facility.subs;
            $("#edit-facility").modal('show');
        },

        updateFacility: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillFacility;
                this.$http.put('/vuefacilitys/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillFacility = {'registration_number':'', 'name':'', 'mailing_address':'', 'in_charge':'', 'in_charge_phone':'', 'in_charge_email':'','sub_id':''};
                    $("#edit-facility").modal('hide');
                    toastr.success('Facility Updated Successfully.', 'Success Alert', {timeOut: 5000});
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
                   // this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        },

         filter_by_region: function() {
            // Clear the error message.
            this.error = '';
            // Empty the facilitys array so we can fill it with the new facilitys.
            this.facilitys = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
           
            //get facilitys filtered by sub county

             if (this.sub_county) {
                this.$http.get('/api/search_facility?sub_county='+ this.sub_county).then((response) => {
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
                    this.sub_county = '';
                });
            }

            //get facilitys filtered by county

            else if (this.county) {
                this.$http.get('/api/search_facility?county=' + this.county ).then((response) => {
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
                    this.county = '';
                });
            }
           
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

        //Populate counties from FacilityController
        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {
                this.counties = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchSubs: function() {
            let id = $('#county_id').val();
            this.$http.get('/subs/'+ id).then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        fetchsSubs: function() {
            let id = $('#county_ids').val();
            this.$http.get('/subs/'+ id).then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        fetchFilterSubs: function() {
            let id = $('#county_id_filter').val();
            this.$http.get('/subs/'+ id).then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        fetchFacility: function() {
            let id = $('#codeMfl').val();
            this.$http.get('/mfl/'+id).then((response) => {
                if(this.newFacility.code == response.data.code){
                    swal("Facility already exists!", "Enter another valid MFL Code.", "info");
                    this.newFacility.code = '';
                }
            });
        },
    }
});