Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

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
    newExpected : {'item_id':'','result':'','tested_by':''},
    fillExpected : {'item_id':'','result':'','tested_by':'','id':''},
    options: [],
    items: []
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
        this.loadItems();
        this.loadResults();
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
			this.newExpected = {'item_id':'','result':'','tested_by':''};
			$("#create-expected").modal('hide');
			toastr.success('Result Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteExpected: function(expected){
        this.$http.delete('/vueexpecteds/'+expected.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Result Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreexpected: function(expected){
        this.$http.patch('/vueexpecteds/'+expected.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Result Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editExpected: function(expected){
          this.fillExpected.item_id = expected.item_id;
          this.fillExpected.id = expected.id;
          this.fillExpected.result = expected.result;
          this.fillExpected.tested_by = expected.tested_by;
          $("#edit-expected").modal('show');
      },

      updateExpected: function(id){
        var input = this.fillExpected;
        this.$http.put('/vueexpecteds/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillExpected = {'item_id':'','result':'','tested_by':'','id':''};
            $("#edit-expected").modal('hide');
            toastr.success('Result Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueExpecteds(page);
      },

      loadItems: function() {
        this.$http.get('/itms').then((response) => {
            this.items = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadResults: function() {
        this.$http.get('/rslts').then((response) => {
            this.options = response.data;

        }, (response) => {
            console.log(response);
        });
      }

  }

});