<template>
  <div id="app">
    <div class="side-bar">
      <leftnav></leftnav>
    </div>
    <section class="content">
      <navbar></navbar>
      <div class="warper container-fluid">
        <router-view></router-view>
      </div>
      <foot></foot>
      <br />
    </section>
  </div>
</template>

<script>
  import Sidebar from './components/Sidebar.vue'
  import Navbar from './components/Navbar.vue'
  import Footer from './components/Footer.vue'

  export default {
    components: {
      'leftnav': Sidebar,
      'navbar': Navbar,
      'foot': Footer,
    }
  }
</script>
