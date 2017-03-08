<template>
    <header class="top-head container-fluid">
        <nav class="navbar-default" role="navigation">
            <div class="collapse navbar-toggleable-xs" id="collapsingNavbar">
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item active">
                        <a class="nav-link text-primary" href="#">Date</a>
                    </li>
                </ul>
                
            </div>
        </nav>
    <!-- Header Ends -->
    </header>
</template>

<script>
  export default{
    data(){
      return{
        isAuth:null
      }
    },

    created(){
      this.isAuth = this.$auth.isAuthenticated()
      this.setAuthenticatedUser()
    },
    
    methods: {
      setAuthenticatedUser(){
        this.$http.get('api/user')
        .then(response => {
          this.$auth.setAuthenticatedUser(response.body)
          console.log(this.$auth.getAuthenticatedUser())
        })
      }
    }
  }
</script>