Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-bulk-sms-settings',

    data: {
        code: '',
        username: '',
        api_key: '',
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
        fillSettings : {'code':'','username':'','api_key':''},      
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
        this.getVueSettings(this.pagination.current_page);
    },

    methods : {
        getVueSettings: function(page){
            this.$http.get('/bulk/key?page='+page).then((response) => {
                this.code = response.data.data.data[0].code;
                this.username = response.data.data.data[0].username;
                this.api_key = response.data.data.data[0].api_key;
                this.pagination = response.data.pagination;
            });
        },

        editSettings: function(code, username, api_key){
            this.fillSettings.code = code;
            this.fillSettings.username = username;
            this.fillSettings.api_key = api_key;
            $("#edit-settings").modal('show');
        },

        updateSettings: function(){
            var input = this.fillSettings;
            this.$http.post('/bulk/api', input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.fillSettings = {'code':'','username':'','api_key':''};
                $("#edit-settings").modal('hide');
                toastr.success('Bulk SMS Settings Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueSettings(page);
        }
    }
});