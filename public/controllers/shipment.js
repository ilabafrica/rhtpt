Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

    el: '#manage-shipment',

    data: {
        shipments: [],
        pagination: {
            total: 0, 
            per_page: 2,
            from: 1, 
            to: 0,
            current_page: 1
        },
        offset: 4,
        formErrors: {},
        formErrorsUpdate: {},
        formConsignmentErrors: {},
        newShipment : {'round_id':'','county_id':'','date_prepared':'','date_shipped':'','shipping_method':'','tracker':'','shipper_id':'','panels_shipped':''},
        fillShipment : {'round_id':'','county_id':'','date_prepared':'','date_shipped':'','shipping_agent':'','tracker':'','shipper_id':'','panels_shipped':'','id':''},
        rounds: [],
        counties: [],
        methods: [],
        subs: [],
        facilities: [],
        shippers: [],
        newReceipt : {'shipment_id':'','date_received':'','panels_received':'','condition':'','receiver':''},
        newConsignment : {'shipment_id':'','facility_id':'','tracker':'','total':'','date_picked':'','picked_by':'','contacts':''},
        loading: false,
        error: false,
        query: '',
        consignments: [],
        formReceiptErrors: {},
        fillConsignment : {'shipment_id':'','facility_id':'','tracker':'','total':'','date_picked':'','picked_by':'','contacts':''},
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
        this.getVueShipments(this.pagination.current_page);
        this.loadRounds();
        this.loadCounties();
        this.loadSubs();
        this.loadMethods();
    },

    methods : {

        getVueShipments: function(page){
            this.$http.get('/vueshipments?page='+page).then((response) => {
                this.shipments = response.data.data.data;
                this.consignments = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        saveShipment: function(scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.newShipment;
                this.$http.post('/vueshipments',input).then((response) => {

                if(response.data == 'error')
                {
                    this.error = response.data;
                    toastr.error(this.error, 'Select a valid PT Round', {timeOut: 5000});
                }
                else
                {
                    this.changePage(this.pagination.current_page);
                    this.newShipment = {'pt_round':'','county_id':'','date_prepared':'','date_shipped':'','tracker':'','shipping_method':'','panels_shipped':''};
                    $("#create-shipment").modal('hide');
                    toastr.success('Shipment Created Successfully.', 'Success Alert', {timeOut: 5000});
                }
                }, (response) => {
                    this.formErrors = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });
        },

        deleteShipment: function(shipment){
            this.$http.delete('/vueshipments/'+shipment.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Shipment Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreShipment: function(shipment){
            this.$http.patch('/vueshipments/'+shipment.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Shipment Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editShipment: function(shipment){
            this.fillShipment.round_id = shipment.round_id;
            this.fillShipment.id = shipment.id;
            this.fillShipment.county_id = shipment.county_id;
            this.fillShipment.date_prepared = shipment.date_prepared;
            this.fillShipment.date_shipped = shipment.date_shipped;
            this.fillShipment.tracker = shipment.tracker;
            this.fillShipment.shipper_id = shipment.shipper_id;
            this.fillShipment.shipping_method = shipment.shipping_method;
            this.fillShipment.panels_shipped = shipment.panels_shipped;
            $("#edit-shipment").modal('show');
        },

        updateShipment: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillShipment;
                this.$http.put('/vueshipments/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillShipment = {'pt_round':'','county_id':'','date_prepared':'','date_shipped':'','tracker':'','shipping_method':'','panels_shipped':'','id':''};
                    $("#edit-shipment").modal('hide');
                    toastr.success('Shipment Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueShipments(page);
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

        loadMethods: function() {
            this.$http.get('/st').then((response) => {
                this.methods = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadSubs: function() {
            this.$http.get('/con_subs').then((response) => {
                this.subs = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchFacilities: function() {
            let id = $('#sub_id').val();
            this.$http.get('/fclts/'+id).then((response) => {
                this.facilities = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchShippers: function() {
            let id = $('#shipping_method').val();
            this.$http.get('/shpprs/'+id).then((response) => {
                this.shippers = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        fetchAgents: function() {
            let id = $('#shipping_agent').val();
            this.$http.get('/shpprs/'+id).then((response) => {
                this.shippers = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        receiveShipment: function(){
            var input = this.newReceipt;
            this.$http.post('/receive',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newReceipt = {'shipment_id':'','date_received':'','panels_received':'','condition':'','receiver':''};
                $("#receive-shipment").modal('hide');
                toastr.success('Shipment Received Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formReceiptErrors = response.data;
            });
        },

        distributeShipment: function(){
            var input = this.newConsignment;
            this.$http.post('/distribute', input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newConsignment = {'shipment_id':'','facility_id':'','tracker':'','total':'','date_picked':'','picked_by':'','contacts':''};
                $("#distribute-shipment").modal('hide');
                toastr.success('Distribution done Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formReceiptErrors = response.data;
            });
        },

        loadConsignments: function(shipment) {
            this.$http.get('/consignments/'+shipment.id).then((response) => {
                this.consignments = response.data.data;
                $("#picked-consignments").modal('show');
            }, (response) => {
                // console.log(response);
            });
        },

        search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the shipments array so we can fill it with the new shipments.
            this.shipments = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_shipment?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the shipments array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.shipments = response.data.data.data;
                    this.pagination = response.data.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        },

        receive: function(id){
            this.newReceipt.shipment_id = id;
            $("#receive-shipment").modal('show');
        },

        distribute: function(id){
            this.newConsignment.shipment_id = id;
            $("#distribute-shipment").modal('show');
        },

        editConsignment: function(consignment){
            this.fillConsignment.shipment_id = consignment.shipment_id;
            this.fillConsignment.id = consignment.id;
            this.fillConsignment.facility_id = consignment.facility_id;
            this.fillConsignment.tracker = consignment.tracker;
            this.fillConsignment.total = consignment.total;
            this.fillConsignment.date_picked = consignment.date_picked;
            this.fillConsignment.picked_by = consignment.picked_by;
            this.fillConsignment.contacts = consignment.contacts;
            $("#edit-consignment").modal('show');
        },

        updateConsignment: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillConsignment;
                this.$http.put('/vueshipments/'+id, input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillConsignment = {'shipment_id':'','facility_id':'','tracker':'','total':'','date_picked':'','picked_by':'','contacts':''},
                    $("#edit-consignment").modal('hide');
                    toastr.success('Consignment Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
            });
        },
    }
});