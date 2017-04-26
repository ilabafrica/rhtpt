<template>
    <div class="card">
        <div class="card-block">
            <form @submit.prevent="create">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input name="name" type="text" class="form-control" v-validate="'required'" v-model="product.name" placeholder="">
                        <span v-show="errors.has('name')">{{ errors.first('name') }}</span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <input name="price" type="number" class="form-control" v-validate="'max_value:50|min_value:1'" v-model="product.price" placeholder="">
                        <span v-show="errors.has('price')">{{ errors.first('price') }}</span>
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
                        <input type="submit" class="btn btn-success pull-right" value="Create"
                        <button @click="create" class="btn btn-success pull-right">Create</button>
                    </div>
                </div>
            </form>
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
                this.$validator.updateDictionary({
                    'al': {
                        atributes: {
                            name: 'emri'
                        }
                    }
                })
                this.$validator.setLocale('al')
                this.$validator.validateAll().then(() => {
                    this.$http.post('api/products', this.product)
                    .then(response => {
                        this.$router.push('/feed')
                    })
                })
            }
        }
    }
</script>