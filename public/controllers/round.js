new Vue({

  el: '#manage-round',

  data: {
    rounds: [],
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
    newRound : {'name':'','description':'','start_date':'','end_date':''},
    fillRound : {'name':'','description':'','start_date':'','end_date':'','id':''}
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
  		this.getVueRounds(this.pagination.current_page);
  },

  methods : {

        getVueRounds: function(page){
          this.$http.get('/vuerounds?page='+page).then((response) => {
            this.$set('rounds', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createRound: function(){
		  var input = this.newRound;
		  this.$http.post('/vuerounds',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newRound = {'name':'','description':'','start_date':'','end_date':''};
			$("#create-round").modal('hide');
			toastr.success('Round Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteRound: function(round){
        this.$http.delete('/vuerounds/'+round.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Round Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editRound: function(round){
          this.fillRound.name = round.name;
          this.fillRound.id = round.id;
          this.fillRound.description = round.description;
          this.fillRound.start_date = round.start_date;
          this.fillRound.end_date = round.end_date;
          $("#edit-round").modal('show');
      },

      updateRound: function(id){
        var input = this.fillRound;
        this.$http.put('/vuerounds/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillRound = {'name':'','description':'','start_date':'','end_date':'','id':''};
            $("#edit-round").modal('hide');
            toastr.success('Round Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueRounds(page);
      }

  }

});