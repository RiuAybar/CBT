<template>
  <div class="wrapper">
    <Sidebar />
    <div class="main">
      <Navbar />
      <main>
        <router-view />
      </main>
    </div>
  </div>
</template>

<script>
import { onMounted, nextTick } from 'vue';
import Navbar from '../../components/Navbar.vue';
import Sidebar from '../../components/Sidebar.vue';

export default {
  components: {
    Navbar,
    Sidebar,
  },
  setup() {
    onMounted(() => {
      nextTick(() => {
        // Dinámicamente importa el script
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