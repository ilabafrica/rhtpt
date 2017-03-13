<template>
    <div class="col-md-4">
        <div class="card">
            <img class="card-img-top responsive" src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/ce54bf11889067.562541ef7cde4.png" alt="Card image cap">
            <div class="card-block">
                {{ product.user_id }}
                <h4 class="card-title">{{ product.name }}</h4>
                <p class="card-text">{{ product.price }}</p>
                <a href="#" class="btn btn-secondary">Wish List</a>
                <a href="#" class="btn btn-success">Buy</a>
                <hr>
                <p>
                    <a href="#" v-if="product.user_id==authenticatedUser.id" class="btn btn-danger" role="button" @click="$emit('delete-product')">Delete</a>
                    <router-link class="btn btn-info" :to="'/products/' + product.id + '/edit'">Edit</router-link>
                </p>
            </div>
        </div>
        <br />
    </div>
</template>

<script>
    export default{
        props: ['product', 'authenticatedUser'],
    }
</script>