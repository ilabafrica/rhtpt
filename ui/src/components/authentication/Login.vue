<template>
    <div class="card">
        <div class="card-block">
            <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" v-model="email" placeholder="Email">
            </div>
            </div>
            <div class="form-group row">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" v-model="password" placeholder="Password">
            </div>
            </div>
            <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button @click="login" class="btn btn-primary">Sign in</button>
            </div>
            </div>
        <pre>
            {{ $data }}
        </pre>
        </div>
    </div>
</template>

<script>
    export default{
        data(){
            return{
                email:'',
                password:''
            }
        },
        methods: {
            login(){
                var data = {
                    client_id: 2,
                    client_secret: '3p5pX0ASRBnuNYWpo2ZTHIBdfEkBX2cLhwig06EY',
                    grant_type: 'password',
                    username: this.email,
                    password: this.password
                }
                this.$http.post('oauth/token', data)
                    .then(response => {
                        this.$auth.setToken(response.body.access_token, response.body.expires_in + Date.now())
                        this.$router.push("/feed")
                        console.log(response)
                    })
            }
        }
    }
</script>

<style></style>