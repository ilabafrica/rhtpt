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
    rounds: [],
    frmData: {},
    loading: false,
    error: false,
    query: ''
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
        this.getForm();
  },

  methods : {

        getVueResults: function(page){
          this.$http.get('/vueresults?page='+page).then((response) => {
            this.$set('results', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createResult: function(){
		 let myForm = document.getElementById('test_results');
         let formData = new FormData(myForm);
		  this.$http.post('/vueresults', formData).then((response) => {
		    this.changePage(this.pagination.current_page);
			$("#create-result").modal('hide');
			toastr.success('Result Saved Successfully.', 'Success Alert', {timeOut: 5000});
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
          //    Fetch the result using the id
          let id = result.id;
          this.$http.get('/pt/'+id).then((response) => {
                this.frmData = response.data;
            });
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

      getForm: function(){
        this.$http.get('/form').then((response) => {
            this.form = response.data.sets;

        }, (response) => {
            console.log(response.data.sets);
        });
      },

      loadRounds: function() {
        this.$http.get('/rnds').then((response) => {
            this.rounds = response.data;

        }, (response) => {
            console.log(response);
        });
      },
        /*Function to toggle input fields to specify value if 'other' is selected*/
        remark: function(className, obj)
        {
            var $input = $(obj);
            if($input.val() == 4)
                $(className).show();
            else
                $(className).hide();
        },
        moment: function (date) {
            return moment(date);
        },

  },
  filters: {
    moment: function (date) {
      return moment(date).format('MMMM Do YYYY, h:mm:ss a');
    }
  },

      search: function() {
        // Clear the error message.
        this.error = '';
        // Empty the results array so we can fill it with the new results.
        this.results = [];
        // Set the loading property to true, this will display the "Searching..." button.
        this.loading = true;

        // Making a get request to our API and passing the query to it.
        this.$http.get('/api/search_result?q=' + this.query).then((response) => {
            // If there was an error set the error message, if not fill the results array.
            if(response.data.error)
            {
                this.error = response.data.error;
                toastr.error(this.error, 'Search Notification', {timeOut: 5000});
            }
            else
            {
                this.results = response.data.data.data;
                this.pagination = response.data.data.pagination;
                toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
            }
            // The request is finished, change the loading to false again.
            this.loading = false;
            // Clear the query.
            this.query = '';
        });
    }

});