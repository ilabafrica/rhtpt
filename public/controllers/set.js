Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-set',

  data: {
    sets: [],
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
    newSet : {'name':'','description':''},
    fillSet : {'name':'','description':'','id':''}
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
  		this.getVueSets(this.pagination.current_page);
  },

  methods : {

        getVueSets: function(page){
          this.$http.get('/vuesets?page='+page).then((response) => {
            this.$set('sets', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createSet: function(){
		  var input = this.newSet;
		  this.$http.post('/vuesets',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newSet = {'name':'','description':''};
			$("#create-set").modal('hide');
			toastr.success('Field Set Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteSet: function(set){
        this.$http.delete('/vuesets/'+set.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Field Set Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreSet: function(set){
        this.$http.patch('/vuesets/'+role.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Field Set Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editSet: function(set){
          this.fillSet.name = set.name;
          this.fillSet.id = set.id;
          this.fillSet.description = set.description;
          $("#edit-set").modal('show');
      },

      updateSet: function(id){
        var input = this.fillSet;
        this.$http.put('/vuesets/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillSet = {'name':'','description':'','id':''};
            $("#edit-set").modal('hide');
            toastr.success('Field Set Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueSets(page);
      }

  }

});