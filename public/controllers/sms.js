Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-sms',

    data: {
        messages: [],
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
        newMessage : {'message':''},
        fillMessage : {'message':''},  
        loading: false,
        error: false,
        checked:false,
        facility_checked:false,
        query: '',      
        error: false,
        query: '', 
        select_county:'',
        roles: [],
        users:'',  
        county: '',
        subcounty:'',
        search_participant: '',
        participant:'',
        counties:[],
        participants: [],
        subcounties:[],
        subs:[],
        facilities:[],
        message: '',
        type:'',
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

    mounted : function(){
    	
    	this.loadCounties();
    	this.loadRoles();
        this.loadMessages();
        this.getVueMessages(this.pagination.current_page);
    },

    methods : {
        getVueMessages: function(page){
            this.$http.get('/vuesms?page='+page).then((response) => {
                if(response.data.data)
                {
                	this.messages = response.data.data.data;
                	this.pagination = response.data.pagination;
                }
                else
                {
                    swal("No data found for Messages.","","info");
                }

            });
        },

        createMessages: function(){
            
            var input = this.newMessage;
            this.$http.post('/vuesms',input).then((response) => {
                
                this.changePage(this.pagination.current_page);
                this.newMessage = {'message':''};
                $("create-message").modal('hide');
                toastr.success('Message Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
              });
            
        },

        deleteMessages: function(message){
            this.$http.delete('/vuesms/'+message.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Message Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreMessages: function(message){
            this.$http.patch('/vuesms/'+message.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Message Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editMessages: function(message){
            this.fillMessage= message;            
            $("#edit-message").modal('show');
        },

        updateMessages: function(id, scope){
            this.$validator.validateAll(scope).then(() => {
                var input = this.fillMessage;
                this.$http.put('/vuesms/'+id,input).then((response) => {
                    this.changePage(this.pagination.current_page);
                    this.fillMessage = {'message':''};
                    $("#edit-message").modal('hide');
                    toastr.success('Message Updated Successfully.', 'Success Alert', {timeOut: 5000});
                }, (response) => {
                    this.formErrorsUpdate = response.data;
                });
            }).catch(() => {
                toastr.error('Please fill in the fields as required.', 'Validation Failed', {timeOut: 5000});
                return false;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueMessages(page);
        },

        loadMessages: function() {
            this.$http.get('/cnts').then((response) => {
                this.messages = response.data;
            }, (response) => {
                // console.log(response);
            });
        },

        loadRoles: function() {
        	this.$http.get('/rls').then((response) => {

        		this.roles = response.data.data.data;
        		this.pagination = response.data.pagination;
        	},(response) => {

        	});
        },

        loadmessagesoptions: function(message){
             this.users = ''; 
             this.county = '';
             this.participant = '';
             this.message = message.message;
             this.type = message.template;
            $("#resend-message").modal('show');           
        },

        resendMessages : function() {
          let myForm = document.getElementById('resend_message');
         
          let formData = new FormData(myForm);
          console.log(formData);
          this.$http.post('/sendmessage', formData ).then((response) => {
          console.log(response);
          $("#resend-message").modal('hide');
          toastr.success('Message Sent Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
                 this.formErrors = response.data;
          });
        
        },

        loadCounties: function() {
            this.$http.get('/cnts').then((response) => {            	
                this.counties = response.data;
                /*this.checked = false;*/
            }, (response) => {
                // console.log(response);
            }); 
        },

        // Populate subcounties from FacilityController
        fetchSubs: function() {
            let id = $('#county_id').val();
            /*this.checked = $("#subcounty").is(":checked");*/
            
            this.$http.get('/subs/'+id).then((response) => {
                this.subs = response.data;
                this.facility_checked = false;
            }, (response) => {
                // console.log(response);
            });
        },   
        // Populate facilities from FacilityController
        fetchFacilities: function() {
            let id = $('#sub_id').val();
            this.$http.get('/fclts/'+id).then((response) => {
                this.facilities = response.data;
            }, (response) => {
                // console.log(response);
            });
        },
        
         search: function() {
            // Clear the error message.
            this.error = '';
            // Empty the testerparticipants array so we can fill it with the new participants.
            this.participants = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;

            // Making a get request to our API and passing the search_participant query to it.
            this.$http.get('/api/search_participant?q=' + this.query).then((response) => {
                // If there was an error set the error message, if not fill the participant array.
                if(response.data.error)
                {
                    this.error = response.data.error;
                    toastr.error(this.error, 'Search Notification', {timeOut: 5000});
                }
                else
                {
                    this.participants = response.data.data.data;
                    toastr.success('The search results below were obtained.', 'Search Notification', {timeOut: 5000});
                }
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the search_participant query.
                this.search_participant = '';
                
            });
            $('#table').click(function(){
			   $(this).addClass('selected').siblings().removeClass('selected');    
			   var value=$(this).find('td:nth-child(4)').html();
			   toastr.success('Message Sent Successfully.' + value, 'Success Alert', {timeOut: 5000});
			});

        },

    }

});


