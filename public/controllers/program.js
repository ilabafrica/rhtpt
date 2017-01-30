new Vue({

  el: '#manage-program',

  data: {
    programs: [],
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
    newProgram : {'name':'','description':''},
    fillProgram : {'name':'','description':'','id':''}
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
  		this.getVuePrograms(this.pagination.current_page);
  },

  methods : {

        getVuePrograms: function(page){
          this.$http.get('/vueprograms?page='+page).then((response) => {
            this.$set('programs', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createProgram: function(){
		  var input = this.newProgram;
		  this.$http.post('/vueprograms',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newProgram = {'name':'','description':''};
			$("#create-program").modal('hide');
			toastr.success('Program Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteProgram: function(program){
        this.$http.delete('/vueprograms/'+program.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Program Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editProgram: function(program){
          this.fillProgram.name = program.name;
          this.fillProgram.id = program.id;
          this.fillProgram.description = program.description;
          $("#edit-program").modal('show');
      },

      updateProgram: function(id){
        var input = this.fillProgram;
        this.$http.put('/vueprograms/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillProgram = {'name':'','description':'','id':''};
            $("#edit-program").modal('hide');
            toastr.success('Program Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVuePrograms(page);
      }

  }

});