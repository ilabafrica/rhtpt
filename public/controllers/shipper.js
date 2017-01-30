new Vue({

  el: '#manage-shipper',

  data: {
    shippers: [],
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
    newShipper : {'name':'','description':'','start_date':'','end_date':''},
    fillShipper : {'name':'','description':'','start_date':'','end_date':'','id':''}
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
  		this.getVueShippers(this.pagination.current_page);
  },

  methods : {

        getVueShippers: function(page){
          this.$http.get('/vueshippers?page='+page).then((response) => {
            this.$set('shippers', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createShipper: function(){
		  var input = this.newShipper;
		  this.$http.post('/vueshippers',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newShipper = {'name':'','description':'','start_date':'','end_date':''};
			$("#create-shipper").modal('hide');
			toastr.success('Shipper Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteShipper: function(shipper){
        this.$http.delete('/vueshippers/'+shipper.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Shipper Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editShipper: function(shipper){
          this.fillShipper.name = shipper.name;
          this.fillShipper.id = shipper.id;
          this.fillShipper.description = shipper.description;
          this.fillShipper.start_date = shipper.start_date;
          this.fillShipper.end_date = shipper.end_date;
          $("#edit-shipper").modal('show');
      },

      updateShipper: function(id){
        var input = this.fillShipper;
        this.$http.put('/vueshippers/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillShipper = {'name':'','description':'','start_date':'','end_date':'','id':''};
            $("#edit-shipper").modal('hide');
            toastr.success('Shipper Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueshippers(page);
      }

  }

});