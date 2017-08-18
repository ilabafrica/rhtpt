Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-receipt',

    data: {
        receipts: [],
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
        newReceipt : {'shipment':'','date_received':'','panels_received':'','condition':'','recipient':''},
        fillReceipt : {'shipment':'','date_received':'','panels_received':'','condition':'','recipient':'','id':''}
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
        this.getVueReceipts(this.pagination.current_page);
    },

    methods : {

        getVueReceipts: function(page){
            this.$http.get('/vuereceipts?page='+page).then((response) => {
                this.receipts = response.data.data.data;
                this.pagination = response.data.pagination;
            });
        },

        createReceipt: function(){
            var input = this.newReceipt;
            this.$http.post('/vuereceipts',input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.newReceipt = {'shipment':'','date_received':'','panels_received':'','condition':'','recipient':''};
                $("#create-receipt").modal('hide');
                toastr.success('Receipt Created Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrors = response.data;
            });
        },

        deleteReceipt: function(receipt){
            this.$http.delete('/vuereceipts/'+receipt.id).then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Receipt Deleted Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        restoreReceipt: function(receipt){
            this.$http.patch('/vuereceipts/'+role.id+'/restore').then((response) => {
                this.changePage(this.pagination.current_page);
                toastr.success('Receipt Restored Successfully.', 'Success Alert', {timeOut: 5000});
            });
        },

        editReceipt: function(receipt){
            this.fillReceipt.shipment = receipt.shipment;
            this.fillReceipt.id = receipt.id;
            this.fillReceipt.date_received = receipt.date_received;
            this.fillReceipt.panels_received = receipt.panels_received;
            this.fillReceipt.condition = receipt.condition;
            this.fillReceipt.recipient = receipt.recipient;
            $("#edit-receipt").modal('show');
        },

        updateReceipt: function(id){
            var input = this.fillReceipt;
            this.$http.put('/vuereceipts/'+id,input).then((response) => {
                this.changePage(this.pagination.current_page);
                this.fillReceipt = {'shipment':'','date_received':'','panels_received':'','condition':'','recipient':'','id':''};
                $("#edit-receipt").modal('hide');
                toastr.success('Receipt Updated Successfully.', 'Success Alert', {timeOut: 5000});
            }, (response) => {
                this.formErrorsUpdate = response.data;
            });
        },

        changePage: function (page) {
            this.pagination.current_page = page;
            this.getVueReceipts(page);
        }
    }
});