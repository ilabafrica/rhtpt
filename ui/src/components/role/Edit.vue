<template>
    <div class="card">
        <div class="card-block">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="product.name" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Price</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" v-model="product.price" placeholder="">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" v-model="product.description" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group row">
                <div class="offset-sm-2 col-sm-10">
                    <button @click="update" class="btn btn-success pull-right" v-show="product.name && product.price && product.description">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import swal from 'sweetalert'
    export default{
        created() {
            this.getProduct();
        },

        data(){
            return{
                product: {}
            }
        },

        methods: {
            getProduct(){
                this.$http.get('api/products/' + this.$route.params.product)
                .then(response => {
                    this.product = response.body
                })
            },

            update(){
                this.$http.put('api/products/' + this.$route.params.product, this.product)
                .then(response => {
                    swal("Updated!", "Your product has been updated.", "success")
                    this.$router.push('/feed')
                })
            }
        }
    }
</script>