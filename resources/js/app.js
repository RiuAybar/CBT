
import './bootstrap';

// import './asset/css/app.css';
import './asset/css/light.css';
import './asset/js/settings.js';
// import './asset/js/app.js';

// import 'bootstrap';
// import 'bootstrap/dist/css/bootstrap.min.css'

import Swal from 'sweetalert2';

import { createApp } from 'vue';
import App from './Layout/App.vue';
import router from './router';
import store from './store';

const app = createApp(App);

// ✅ Define SweetAlert como propiedad global
app.config.globalProperties.$swal = Swal;

app
.use(router)
.use(store)
.mount('#app');
