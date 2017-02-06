<template>
    <div class="col-md-4">
        <div class="card">
            <img class="card-img-top responsive" src="https://mir-s3-cdn-cf.behance.net/project_modules/disp/ce54bf11889067.562541ef7cde4.png" alt="Card image cap">
            <div class="card-block">
                <h4 class="card-title">{{ product.name }}</h4>
                <p class="card-text">{{ product.price }}</p>
                <a href="#" class="btn btn-secondary">Wish List</a>
                <a href="#" class="btn btn-success">Buy</a>
            </div>
        </div>
        <br />
    </div>
</template>

<script>
    export default{
        props: ['product']
    }
</script>