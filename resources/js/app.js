
import './bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';


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



import 'ag-grid-community/styles/ag-grid.css';
import 'ag-grid-community/styles/ag-theme-alpine.css';

// import { ModuleRegistry, ClientSideRowModelModule } from 'ag-grid-community';

// ModuleRegistry.registerModules([ClientSideRowModelModule]);

const app = createApp(App);

// ✅ Define SweetAlert como propiedad global
app.config.globalProperties.$swal = Swal;

app
.use(router)
.use(store)
.mount('#app');
