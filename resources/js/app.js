import './bootstrap';
import { createApp } from 'vue';
import router from "./router.js";

const App = {
    template: '<router-view />'
};

const app = createApp(App);

app.use(router);

app.mount('#app');
