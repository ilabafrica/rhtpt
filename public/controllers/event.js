Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-event',

  data: {
    events: [],
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
    newEvent : {'title':'','description':''},
    fillEvent : {'title':'','description':'','id':''}
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
  		this.getVueEvents(this.pagination.current_page);
  },

  methods : {

        getVueEvents: function(page){
          this.$http.get('/vueevents?page='+page).then((response) => {
            this.$set('events', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createEvent: function(){
		  var input = this.newEvent;
		  this.$http.post('/vueevents',input).then((response) => {
		    this.changePage(this.pagination.current_page);
			this.newEvent = {'title':'','description':''};
			$("#create-event").modal('hide');
			toastr.success('Event Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteEvent: function(event){
        this.$http.delete('/vueevents/'+event.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Event Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editEvent: function(event){
          this.fillEvent.title = event.title;
          this.fillEvent.id = event.id;
          this.fillEvent.description = event.description;
          $("#edit-event").modal('show');
      },

      updateEvent: function(id){
        var input = this.fillEvent;
        this.$http.put('/vueevents/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillEvent = {'title':'','description':'','id':''};
            $("#edit-event").modal('hide');
            toastr.success('Event Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueEvents(page);
      }

  }

});