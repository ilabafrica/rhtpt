<template>
  <div class="presenter">
  <!-- Preloader -->
  <div class="loading-container">
      <div class="loading">
          <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
          <span class="sr-only">Loading...</span>
      </div>
  </div>
  <!-- Preloader -->
  <!-- Sidebar -->
  <aside v-if="isAuthenticated" class="left-panel">
    <div class="user text-xs-center">
        <img src="/src/assets/coa.png" class="img-circle" alt="...">
        <h4 class="user-name text-warning">Kenya Serology Rapid HIV PT</h4>
    </div>
    <nav class="navigation">
        <ul class="list-unstyled">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>
        </ul>
    </nav>
  </aside>
  <!-- Sidebar -->
  <!-- Content -->
  <section class="content">
    <header class="top-head container-fluid">
        <nav class="navbar-default" role="navigation">
            <div class="collapse navbar-toggleable-xs" id="collapsingNavbar">
                <ul class="nav navbar-nav pull-right">
                    <li class="nav-item active">
                        <a class="nav-link text-primary" href="#">Today's Date</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Kitsao</a>
                        <div class="dropdown-menu  dropdown-menu-right">
                            <a class="dropdown-item" href="#">Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Sign Out</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    <!-- Header Ends -->
    </header>

    <div class="warper container-fluid" id="app">
        <router-view></router-view>
        <br />
    </div>
    <!-- Warper Ends Here (working area) --><hr>
    <div id="footer" class="">
        <div class="container-fluid">
            <p class="text-muted gem-h7">
                <strong>
                    &copy; 2017
                    <span style="float:right">
                        Designed by @iLabAfrica&nbsp;&nbsp;&nbsp;
                        <a href="#" class="pull-right scrollToTop"><i class="fa fa-chevron-up"></i></a>
                    </span>
                </strong>
            </p>
        </div>
    </div>
  </section>
  <!-- Content -->
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
