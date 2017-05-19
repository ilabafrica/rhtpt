Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-user',

  data: {
    users: [],
    roles: [],
    counties: [],
    subs: [],
    programs:[],
    facilities: [],
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
    newUser : {'name':'','username': '','role': '','gender':'', 'phone':'', 'email':'', 'address':''},
    fillUser : {'name':'','username': '','role': '','gender':'', 'phone':'', 'email':'', 'address':'', 'id':''},
    transferUser : {'facility_id':'','program_id':'', 'id':''},
    loading: false,
    error: false,
    query: '',
    formTransErrors:{},
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
        this.loadPrograms();
        this.loadCounties();
        this.loadRoles();
  },

  methods : {

        getVueUsers: function(page){
          this.$http.get('/vueusers?page='+page).then((response) => {
            this.$set('users', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createUser: function(){
    		  var input = this.newUser;
      		  this.$http.post('/vueusers',input).then((response) => {
    		    this.changePage(this.pagination.current_page);
      			this.newUser = {'name':'', 'username': '','gender':'', 'phone':'', 'email':'', 'address':''};
      			$("#create-user").modal('hide');
      			toastr.success('User Created Successfully.', 'Success Alert', {timeOut: 5000});
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

      editUser: function(user){
          this.fillUser.id = user.id;
          this.fillUser.name = user.name;
          this.fillUser.username = user.username;
          this.fillUser.gender = user.gender;
          this.fillUser.phone = user.phone;
          this.fillUser.email = user.email;
          this.fillUser.address = user.address;
          this.fillUser.role = user.rl;
          this.fillUser.program = user.program;
          this.fillUser.uid = user.uid;
          this.fillUser.address = user.address;
          $("#edit-user").modal('show');
      },

      updateUser: function(id){
        var input = this.fillUser;
        this.$http.put('/vueusers/'+id,input).then((response) => {
            //this.changePage(this.pagination.current_page); - @TODO
            this.fillUser = {'name':'','username': '','gender':'', 'phone':'', 'email':'', 'address':''};
            $("#edit-user").modal('hide');
            toastr.success('User Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },
      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueUsers(page);
      },
       //Populate counties from FacilityController
      loadCounties: function() {
        this.$http.get('/cnts').then((response) => {
            this.counties = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      // Populate subcounties from FacilityController
      loadSubcounties: function() {
        let id = $('#county').val();
            console.log(id);
        this.$http.get('/subs/'+id).then((response) => { 
            this.subcounties = response.data;

        }, (response) => {
            // console.log(response);
        });
      },
      
      // Populate facilities from FacilityController
      loadFacilities: function() {
        let id = $('#sub_county').val();
        this.$http.get('/fclts/'+id).then((response) => { 
            this.facilities = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      //    Populate programs from ProgramController
      loadPrograms: function() {
        this.$http.get('/progs').then((response) => { 
            this.programs = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      //    Populate roles from RoleController
      loadRoles: function() {
        this.$http.get('/priv').then((response) => { 
            this.roles = response.data;

        }, (response) => {
            console.log(response);
        });
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
    },
    loadCounties: function() {
        this.$http.get('/cnts').then((response) => {
            this.counties = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      fetchSubs: function() {
        let id = $('#county_id').val();
        this.$http.get('/subs/'+id).then((response) => {
            this.subs = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      fetchFacilities: function() {
        let id = $('#sub_id').val();
        this.$http.get('/fclts/'+id).then((response) => {
            this.facilities = response.data;

        }, (response) => {
            console.log(response);
        });
      },
      populateUser: function(user){
          this.transferUser.id = user.id;
          this.transferUser.facility_id = user.facility;
          this.transferUser.program_id = user.program;
          $("#transfer-user").modal('show');
      },

      transUser: function(id){
        var input = this.transferUser;
        this.$http.put('/transfer/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.transferUser = {'facility_id':'', 'program_id':'','id': ''};
            $("#transfer-user").modal('hide');
            toastr.success('User Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formTransErrors = response.data;
          });
      },

  }

});