Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");



new Vue({

  el: '#manage-result',

  data: {
    results: [],
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
    newResult : {'round_id':'','field_id[]':'','response[]':'','comment[]':''},
    fillResult : {'round_id':'','field_id[]':'','response[]':'','comment[]':'','id':''},
    form: [],
    rounds: []
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
  		this.getVueResults(this.pagination.current_page);
        this.loadRounds();
        this.getFields();
  },

  methods : {

        getVueResults: function(page){
          this.$http.get('/vueresults?page='+page).then((response) => {
            this.$set('results', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createResult: function(){
		  var input = this.newResult;
		  this.$http.post('/vueresults',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newResult = {'round_id':'','field_id[]':'','response[]':'','comment[]':''};
			$("#create-result").modal('hide');
			toastr.success('Result Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteResult: function(result){
        this.$http.delete('/vueresults/'+result.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Result Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreResult: function(result){
        this.$http.patch('/vueresults/'+result.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Result Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editResult: function(result){
          this.fillResult.name = result.name;
          this.fillResult.id = result.id;
          this.fillResult.display_name = result.display_name;
          this.fillResult.description = result.description;
          $("#edit-result").modal('show');
      },

      updateResult: function(id){
        var input = this.fillResult;
        this.$http.put('/vueresults/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillResult = {'name':'','display_name':'','description':'','id':''};
            $("#edit-result").modal('hide');
            toastr.success('Result Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueResults(page);
      },

      getFields: function(){
        this.$http.get('/form').then((response) => {
            this.form = response.data;
            console.log(response.data);

        }, (response) => {
            console.log(response.data.length);
        });
      },

      loadRounds: function() {
        this.$http.get('/rnds').then((response) => {
            this.rounds = response.data;
            console.log(response.data);

        }, (response) => {
            console.log(response);
        });
      }

  }

});