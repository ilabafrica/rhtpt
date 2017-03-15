Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-set',

  data: {
    sets: [],
    questionnaires: [],
    ordrs: [],
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
    newSet : {'title':'','description':'','order':'','questionnaire_id':''},
    fillSet : {'title':'','description':'','order':'','questionnaire_id':'','id':''}
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
        this.loadQuests();
        this.loadSets();
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
			this.newSet = {'title':'','description':'','order':'','questionnaire_id':''};
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
          this.fillSet.title = set.title;
          this.fillSet.id = set.id;
          this.fillSet.description = set.description;
          this.fillSet.order = set.order;
          this.fillSet.questionnaire_id = set.questionnaire_id;
          $("#edit-set").modal('show');
      },

      updateSet: function(id){
        var input = this.fillSet;
        this.$http.put('/vuesets/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillSet = {'title':'','description':'','order':'','questionnaire_id':'','id':''};
            $("#edit-set").modal('hide');
            toastr.success('Field Set Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      loadQuests: function() {
        this.$http.get('/quest').then((response) => {
            this.questionnaires = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadSets: function() {
        this.$http.get('/preceed').then((response) => {
            this.ordrs = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueSets(page);
      }

  }

});