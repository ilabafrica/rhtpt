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
                    <button @click="create" class="btn btn-success pull-right" v-show="product.name && product.price && product.description">Create</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                product: {
                    name: '',
                    price: 0,
                    description: ''
                }
            }
        },

        methods: {
            create(){
                this.$http.post('api/products', this.product)
                .then(response => {
                    this.$router.push('/feed')
                })
            }
        }
    }
</script>