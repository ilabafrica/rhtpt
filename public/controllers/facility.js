Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

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
    newFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':''},
    fillFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''}
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
  		this.getVueFacilitys(this.pagination.current_page);
  },

  methods : {

        getVueFacilitys: function(page){
          this.$http.get('/vuefacilitys?page='+page).then((response) => {
            this.$set('facilitys', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createFacility: function(){
		  var input = this.newFacility;
		  this.$http.post('/vuefacilitys',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newFacility = {'name':'','description':'', 'order':'', 'tag':'', 'options':''};
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

      editFacility: function(facility){
          this.fillFacility.name = facility.name;
          this.fillFacility.id = facility.id;
          this.fillFacility.description = facility.description;
          this.fillFacility.order = facility.order;
          this.fillFacility.tag = facility.tag;
          this.fillFacility.options = facility.options;
          $("#edit-facility").modal('show');
      },

      updateFacility: function(id){
        var input = this.fillFacility;
        this.$http.put('/vuefacilitys/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillFacility = {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''};
            $("#edit-facility").modal('hide');
            toastr.success('Facility Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueFacilitys(page);
      }

  }

});