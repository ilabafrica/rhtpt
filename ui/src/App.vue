<template>
  <div class="container-fluid" id="app">
    <navbar></navbar>
    <router-view></router-view>
    <br />
  </div>
</template>

<script>
  import Navbar from './components/Navbar.vue'
  export default {
    components: {
      'navbar': Navbar,
    }
  }
</script>

<style>
</style>