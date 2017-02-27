Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-assignments',

  data: {
    roles: [],
    users: [],
    checks: [],
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
    fillFacility : {'name':'','description':'', 'order':'', 'tag':'', 'options':'','id':''}
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
  		this.getVueAssignments(this.pagination.current_page);
  },

  methods : {

        getVueAssignments: function(page){
          this.$http.get('/vueassigns?page='+page).then((response) => {
            this.$set('users', response.data.users.data);
            this.$set('roles', response.data.roles);
            this.$set('checks', response.data.checks);
            this.$set('pagination', response.data.pagination);
          });
        },

        createAssignment: function(page){
		    let myForm = document.getElementById('update_assignments');
            let formData = new FormData(myForm);
            this.$http.post('/vueassigns', formData).then((response) => {
                toastr.success('Roles Assigned Successfully.', 'Success Alert', {timeOut: 5000});
                this.getVueAssignments();
            }, (response) => {
                this.formErrors = response.data;
            });
	},
      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueAssignments(page);
      }

  }

});