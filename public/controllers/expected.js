new Vue({

  el: '#manage-expected',

  data: {
    expecteds: [],
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
    newExpected : {'name':'','description':''},
    fillExpected : {'name':'','description':'','id':''}
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
  		this.getVueExpecteds(this.pagination.current_page);
  },

  methods : {

        getVueExpecteds: function(page){
          this.$http.get('/vueexpecteds?page='+page).then((response) => {
            this.$set('expecteds', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createExpected: function(){
		  var input = this.newExpected;
		  this.$http.post('/vueexpecteds',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newExpected = {'name':'','description':''};
			$("#create-expected").modal('hide');
			toastr.success('Expected Results Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteExpected: function(expected){
        this.$http.delete('/vueexpecteds/'+expected.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Expected Results Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editExpected: function(expected){
          this.fillExpected.name = expected.name;
          this.fillExpected.id = expected.id;
          this.fillExpected.description = expected.description;
          $("#edit-expected").modal('show');
      },

      updateExpected: function(id){
        var input = this.fillExpected;
        this.$http.put('/vueexpecteds/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillExpected = {'name':'','description':'','id':''};
            $("#edit-expected").modal('hide');
            toastr.success('Expected Results Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueExpecteds(page);
      }

  }

});