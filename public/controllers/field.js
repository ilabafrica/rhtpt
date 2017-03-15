Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-field',

  data: {
    fields: [],
    tags: [],
    sets: [],
    flds: [],
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
    frmData: {}
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
  		this.getVueFields(this.pagination.current_page);
        this.loadFields();
        this.loadSets();
        this.loadTags();
        this.loadOptions();
  },

  methods : {

        getVueFields: function(page){
          this.$http.get('/vuefields?page='+page).then((response) => {
            this.$set('fields', response.data.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

        createField: function(){
		  let myForm = document.getElementById('test_results');
          let formData = new FormData(myForm);
		  this.$http.post('/vuefields',formData).then((response) => {
		    this.changePage(this.pagination.current_page);
			$("#create-field").modal('hide');
			toastr.success('Field Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteField: function(field){
        this.$http.delete('/vuefields/'+field.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Field Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      restoreField: function(field){
        this.$http.patch('/vuefields/'+role.id+'/restore').then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Field Restored Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editField: function(field){
          this.fillField.name = field.name;
          this.fillField.id = field.id;
          this.fillField.description = field.description;
          this.fillField.order = field.order;
          this.fillField.tag = field.tag;
          this.fillField.options = field.options;
          $("#edit-field").modal('show');
      },

      updateField: function(id){
        var input = this.fillField;
        this.$http.put('/vuefields/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillField = {'uid':'','title':'', 'order':'', 'tag':'', 'field_set_id':'', 'opts[]':'','id':''};
            $("#edit-field").modal('hide');
            toastr.success('Field Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      loadTags: function() {
        this.$http.get('/tags').then((response) => {
            this.tags = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadSets: function() {
        this.$http.get('/preceed').then((response) => {
            this.sets = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadFields: function() {
        this.$http.get('/flds').then((response) => {
            this.flds = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      loadOptions: function() {
        this.$http.get('/opt').then((response) => {
            this.options = response.data;

        }, (response) => {
            console.log(response);
        });
      },

      showOptions: function(className, obj){
          alert($(obj).val());
        var $input = $(obj);
        if($input.val() == 1 || $input.val() == 5 || $input.val() == 6)
            $(className).show();
        else
            $(className).hide();
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueFields(page);
      }

  }

});