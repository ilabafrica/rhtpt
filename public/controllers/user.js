Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-user',

  data: {
    users: [],
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
    fillFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''},
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
  		this.getVueUsers(this.pagination.current_page);
  },

  methods : {

        getVueUsers: function(page){
          this.$http.get('/vueusers?page='+page).then((response) => {
            this.$set('users', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createUser: function(){
		  var input = this.newFacility;
		  this.$http.post('/vueusers',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newFacility = {'name':'','description':'', 'order':'', 'tag':'', 'options':''};
			$("#create-facility").modal('hide');
			toastr.success('Facility Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteUser: function(facility){
        this.$http.delete('/vueusers/'+facility.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Facility Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreUser: function(user){
        this.$http.patch('/vueusers/'+user.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('User Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editUser: function(facility){
          this.fillFacility.name = facility.name;
          this.fillFacility.id = facility.id;
          this.fillFacility.description = facility.description;
          this.fillFacility.order = facility.order;
          this.fillFacility.tag = facility.tag;
          this.fillFacility.options = facility.options;
          $("#edit-facility").modal('show');
      },

      updateUser: function(id){
        var input = this.fillFacility;
        this.$http.put('/vueusers/'+id,input).then((response) => {
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
          this.getVueUsers(page);
      },

      search: function() {
        // Clear the error message.
        this.error = '';
        // Empty the users array so we can fill it with the new users.
        this.users = [];
        // Set the loading property to true, this will display the "Searching..." button.
        this.loading = true;

        // Making a get request to our API and passing the query to it.
        this.$http.get('/api/search_user?q=' + this.query).then((response) => {
            // If there was an error set the error message, if not fill the users array.
            if(response.data.error)
            {
                this.error = response.data.error;
                toastr.error(this.error, 'Search Notification', {timeOut: 5000});
            }
            else
            {
                this.users = response.data.data.data;
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