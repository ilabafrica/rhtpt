Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");



new Vue({

  el: '#manage-role',

  data: {
    roles: [],
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
    newRole : {'name':'','display_name':'','description':''},
    fillRole : {'name':'','display_name':'','description':'','id':''},
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
  		this.getVueRoles(this.pagination.current_page);
  },

  methods : {

        getVueRoles: function(page){
          this.$http.get('/vueroles?page='+page).then((response) => {
            this.$set('roles', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createRole: function(){
		  var input = this.newRole;
		  this.$http.post('/vueroles',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newRole = {'name':'','description':''};
			$("#create-role").modal('hide');
			toastr.success('Role Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteRole: function(role){
        this.$http.delete('/vueroles/'+role.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Role Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreRole: function(role){
        this.$http.patch('/vueroles/'+role.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Role Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editRole: function(role){
          this.fillRole.name = role.name;
          this.fillRole.id = role.id;
          this.fillRole.display_name = role.display_name;
          this.fillRole.description = role.description;
          $("#edit-role").modal('show');
      },

      updateRole: function(id){
        var input = this.fillRole;
        this.$http.put('/vueroles/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillRole = {'name':'','display_name':'','description':'','id':''};
            $("#edit-role").modal('hide');
            toastr.success('Role Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueRoles(page);
      },

      search: function() {
        // Clear the error message.
        this.error = '';
        // Empty the roles array so we can fill it with the new roles.
        this.roles = [];
        // Set the loading property to true, this will display the "Searching..." button.
        this.loading = true;

        // Making a get request to our API and passing the query to it.
        this.$http.get('/api/search_role?q=' + this.query).then((response) => {
            // If there was an error set the error message, if not fill the roles array.
            if(response.data.error)
            {
                this.error = response.data.error;
                toastr.error(this.error, 'Search Notification', {timeOut: 5000});
            }
            else
            {
                this.roles = response.data.data.data;
                this.pagination = response.data.data.pagination;
                toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
            }
            // The request is finished, change the loading to false again.
            this.loading = false;
            // Clear the query.
            this.query = '';
        });
    }

  }

});