import './bootstrap';
import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import {createApp} from 'vue';
import App from './layouts/App.vue';
import App2 from './layouts/App2.vue';

import Router from './routes.js';


createApp(App)
.use(Router)
.mount('#app');
createApp(App2)
.use(Router)
.mount('#app2');
