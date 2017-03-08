<template>
  <div class="trial">
  <footer class="container-fluid footer">
        	Copyright © 2014 <a href="http://freakpixels.com/" target="_blank">FreakPixels</a>
            <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
        </footer>
  <header class="top-head container-fluid"><nav role="navigation" class="navbar-default"><div id="collapsingNavbar" class="collapse navbar-toggleable-xs"><ul class="nav navbar-nav pull-right"><li class="nav-item active"><a href="#" class="nav-link text-primary">Date</a></li></ul></div></nav></header></div>
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