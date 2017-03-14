<template>
    <div class="card col-sm-5" style="margin:auto; float:none">
        <div class="card-block">
            <div class="row" style="padding:20px">
                <div class="col-md-12  text-md-center">
                    <img src='../../assets/img/coa.png' class="rounded mx-auto d-block" height="75px"/>
                    <h5 class="text-primary">Kenya Serology HIV PT</h5>
                </div>
            </div>
            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="username" placeholder="Username">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" v-model="password" placeholder="Password">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-offset-2 col-sm-10">
                    <button @click="login" class="btn btn-success btn-block">Sign in</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                username:'',
                password:''
            }
        },
        methods: {
            login(){
                var data = {
                    client_id: 2,
                    client_secret: 'lKbOFTwQEmeGX3egKQO29yeJbtcGPqULXO58z7Jf',
                    grant_type: 'password',
                    username: this.username,
                    password: this.password
                }
                this.$http.post('oauth/token', data)
                    .then(response => {
                        this.$auth.setToken(response.body.access_token, response.body.expires_in + Date.now())
                        this.$router.push("/home")
                        console.log(response)
                    })
            }
        }
    }
</script>