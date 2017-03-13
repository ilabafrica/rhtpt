<template>
  <div id="app">
    <div v-if="authenticatedUser" class="side-bar">
      <leftnav></leftnav>
    </div>
    <section class="content" v-if="authenticatedUser">
      <navbar></navbar>
      <div class="warper container-fluid">
        <router-view></router-view>
      </div>
      <foot></foot>
      <br />
    </section>
    <div class="warper container-fluid" v-if="!authenticatedUser">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
  import Sidebar from './components/Sidebar.vue'
  import Navbar from './components/Navbar.vue'
  import Footer from './components/Footer.vue'

  export default {
    data(){
        return{
            authenticatedUser:'',
        }
    },

    computed: {
        authenticatedUser(){
            return this.authnticatedUser = this.$auth.getAuthenticatedUser()
        }
    },

    components: {
      'leftnav': Sidebar,
      'navbar': Navbar,
      'foot': Footer,
    }
  }
</script>
