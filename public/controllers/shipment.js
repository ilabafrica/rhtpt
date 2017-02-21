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
    formErrors:{},
    formErrorsUpdate:{},
    newShipment : {'round_id':'','facility_id':'','date_prepared':'','date_shipped':'','shipping_method':'','shipper_id':'','panels_shipped':''},
    fillShipment : {'round_id':'','facility_id':'','date_prepared':'','date_shipped':'','shipping_method':'','shipper_id':'','panels_shipped':'','id':''},
    rounds: [],
    counties: [],
    methods: [],
    subs: [],
    facilities: [],
    shippers: [],
    newReceipt : {'shipment_id':'','date_received':'','panels_received':'','condition':'','receiver':''}
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

  ready : function(){
  		this.getVueShipments(this.pagination.current_page);
        this.loadRounds();
        this.loadCounties();
        this.loadMethods();
  },

  computed: {
    fk: function () {
      return parent.children('.receive').attr('data-fk');
    }
  },

  methods : {

        getVueShipments: function(page){
          this.$http.get('/vueshipments?page='+page).then((response) => {
            this.$set('shipments', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createShipment: function(){
		  var input = this.newShipment;
		  this.$http.post('/vueshipments',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newShipment = {'pt_round':'','facility_id':'','date_prepared':'','date_shipped':'','shipping_method':'','panels_shipped':''};
			$("#create-shipment").modal('hide');
			toastr.success('Shipment Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteShipment: function(shipment){
        this.$http.delete('/vueshipments/'+shipment.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Shipment Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreShipment: function(shipment){
        this.$http.patch('/vueshipments/'+role.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Shipment Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editShipment: function(shipment){
          this.fillShipment.pt_round = shipment.pt_round;
          this.fillShipment.id = shipment.id;
          this.fillShipment.facility_id = shipment.facility_id;
          this.fillShipment.date_prepared = shipment.date_prepared;
          this.fillShipment.date_shipped = shipment.date_shipped;
          this.fillShipment.shipping_method = shipment.shipping_method;
          this.fillShipment.panels_shipped = shipment.panels_shipped;
          $("#edit-shipment").modal('show');
      },

      updateShipment: function(id){
        var input = this.fillShipment;
        this.$http.put('/vueshipments/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillShipment = {'pt_round':'','facility_id':'','date_prepared':'','date_shipped':'','shipping_method':'','panels_shipped':'','id':''};
            $("#edit-shipment").modal('hide');
            toastr.success('Shipment Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
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
            console.log(response);
        });
      },

      loadCounties: function() {
        this.$http.get('/cnts').then((response) => {
            this.counties = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadMethods: function() {
        this.$http.get('/st').then((response) => {
            this.methods = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      fetchSubs: function() {
        let id = $('#county_id').val();
        console.log(id);
        this.$http.get('/subs/'+id).then((response) => {
            this.subs = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      fetchFacilities: function() {
        let id = $('#sub_id').val();
        this.$http.get('/fclts/'+id).then((response) => {
            this.facilities = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      fetchShippers: function() {
        let id = $('#shipping_method').val();
        this.$http.get('/shpprs/'+id).then((response) => {
            this.shippers = response.data;

        }, (response) => {
            console.log(response);
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
	}

  }

});