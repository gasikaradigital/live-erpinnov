import './bootstrap';

import { createApp } from 'vue';
import App from './components/App.vue';

// Styles
import '../css/app.css';

// Création et montage de l'application Vue
const app = createApp(App);
app.mount('#app');
