Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

	el:'#sub-county',

	data: {
		subcounties: [],
        pagination: {
            total: 0, 
            per_page: 2,
            from: 1, 
            to: 0,
            current_page: 1
        },
        offset:4,
        formErrors:{},
        formErrorsUpdate:{},
        newSubcounty: {'name':'', 'county_id':''},
        fillSubcounty: {'name':'', 'county_id':''},
        counties:[],
        query: '',
        loading:false,
        error:false,  
        subs:[], 
        sub_county: '', 
        county:'', 
        subcntys: [],       
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

mounted : function() {
    this.getVueSubcounty(this.pagination.current_page);
    this.loadCounties();
},

methods: {
       
      createSubcounty:function(){

      	var input = this.newSubcounty;
      	this.$http.post('/vuesubcounty',input).then((response) => {
      		this.changePage(this.pagination.current_page);
      		this.newSubcounty = {'name':'','county_id':''};
      		$("#create-subcounty").modal('hide');
      		toastr.success('Sub County Created Successfully','Success Alert', {timeOut: 5000}); 
      	}, (response) => {
      		this.formErrors = response.data;
      	});
      },
	  getVueSubcounty: function (page) {
	       this.$http.get('/vuesubcounty?page='+page).then((response) => {       	  	   
	  	     if(response.data.data)
	  	    {
              this.subcounties = response.data.data.data;
		      this.pagination = response.data.pagination;
	        }
	        else {
	   	        swal("No data found for Sub County", "", "info");
	       }
	    });
     },

     loadCounties: function() {
            this.$http.get('/cnts').then((response) => {            	
                this.counties = response.data;
            }, (response) => {
                // console.log(response);
            });
     },
     deleteSubcounty: function(subcounty){
            this.$http.delete('/vuesubcounty/'+subcounty.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Sub County Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

     restoreSubcounty: function(subcounty){
            this.$http.patch('/vuesubcounty/'+subcounty.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Sub County Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

     changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueSubcounty(page);
        },

      /*editSubcounty: function(subcounty){
            this.fillSubcounty.name = subcounty.name; 
            this.fillSubcounty.county_id = subcounty.counties;
            console.log( this.fillSubcounty.name, this.fillSubcounty.county_id)           
            $("#edit-subcounty").modal('show');
        },*/
        editSubcounty: function(subcounty){        	
            this.fillSubcounty = subcounty;
            // this.fillSubcounty.county_id = subcounty.counties;
            // this.fillSubcounty.name = subcounty.name;
            $("#edit-subcounty").modal('show');
            console.log(subcounty);
            
        },

        
      updateSubcounty: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillSubcounty;
                this.$http.put('/vuesubcounty/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillSubcounty = {'name':'', 'county_id':'' };
                    $("#edit-subcounty").modal('hide');
                    toastr.success('Sub County Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        search: function() 
        {
            // Clear the error message.
            this.error = '';
            // Empty the subcounties array so we can fill it with the new subcounties.
            this.subcounties = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the query to it.
            this.$http.get('/api/search_subcounty?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the subcounty array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.subcounty = response.data.data.data;
                   // this.pagination = response.data.pagination;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.query = '';
            });
        },  

        filter_by_region: function() {
            // Clear the error message.
            this.error = '';
            // Empty the users array so we can fill it with the new users.
            
            this.loading = true;
            
            this.subcounties = [];
            // Making a get request to our API and passing the query to it.
             //get users filtered by facility
             
                this.$http.get('/api/search_subcounty?county='+ this.county).then((response) => {
                    // If there was an error set the error message, if not fill the users array.
                    if(response.data.error)
                    {
                        this.error = response.data.error;
                        toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                    }
                    else
                    {
                    	console.log(response);
                        this.subcounties = response.data.data.data;
                        this.pagination = response.data.pagination;
                        toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                    }
                    // The request is finished, change the loading to false again.
                    this.loading = false;
                    // Clear the query.
                    this.county = '';
                });
            
        },
   }
});