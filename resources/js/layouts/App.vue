<template>
  <NavBar />
  <AsideBar />
  <div class="content-wrapper"><router-view></router-view></div>
  <Footer />
</template>
<script>
import NavBar from "../partials/NavBar.vue";
import AsideBar from "../partials/AsideBar.vue";
import Footer from "../partials/Footer.vue";
export default {
  components: {
    NavBar,
    AsideBar,
    Footer,
  },
};
</script>
