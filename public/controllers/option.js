new Vue({

  el: '#manage-option',

  data: {
    options: [],
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
    newOption : {'name':'','label':'','description':''},
    fillOption : {'name':'','label':'','description':'','id':''}
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
  		this.getVueOptions(this.pagination.current_page);
  },

  methods : {

        getVueOptions: function(page){
          this.$http.get('/vueoptions?page='+page).then((response) => {
            this.$set('options', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createOption: function(){
		  var input = this.newOption;
		  this.$http.post('/vueoptions',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newOption = {'name':'','label':'','description':''};
			$("#create-option").modal('hide');
			toastr.success('Option Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteOption: function(option){
        this.$http.delete('/vueoptions/'+option.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Option Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editOption: function(option){
          this.fillOption.name = option.name;
          this.fillOption.id = option.id;
          this.fillOption.label = option.label;
          this.fillOption.description = option.description;
          $("#edit-option").modal('show');
      },

      updateOption: function(id){
        var input = this.fillOption;
        this.$http.put('/vueoptions/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillOption = {'name':'','label':'','description':'','id':''};
            $("#edit-option").modal('hide');
            toastr.success('Option Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueOptions(page);
      }

  }

});