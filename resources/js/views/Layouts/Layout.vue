<template>
  <div class="wrapper">
    <Sidebar />
    <div class="main">
      <Navbar />
      <main class="content">
        <router-view />
      </main>
      <Footer />

      
    </div>
  </div>
</template>

<script>
import { onMounted, nextTick } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Sidebar from '../../components/Sidebar.vue';

import Footer from '../../components/Footer.vue';

export default {
  components: {
    Navbar,
    Sidebar,
    Footer,
  },
  setup() {
    onMounted(() => {
      nextTick(() => {
        // Dinámicamente importa el script
        // import('../../asset/css/app.css');
        import('../../asset/css/light.css');
        import('../../asset/js/app.js');
        // import('../../asset/js/settings.js');
        // import('../../asset/js/Modals_AdminKit.js');
      });
    });
  },
};
</script>

<style>
body {
  opacity: 0;
}
</style>