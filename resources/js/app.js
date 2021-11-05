/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('create-campaign-requets', require('./components/campaigns/create.vue').default);
Vue.component('edit-campaign', require('./components/campaigns/edit.vue').default);
Vue.component('finance-campaign', require('./components/campaigns/finance.vue').default);
Vue.component('asign-buses', require('./components/campaigns/buses.vue').default);
Vue.component('design-buses', require('./components/campaigns/design.vue').default);
Vue.component('asign-task', require('./components/campaigns/task.vue').default);
Vue.component('designs_approve', require('./components/campaigns/ejecutivo_active.vue').default);
Vue.component('router_manager', require('./components/developer/routes_manager.vue').default);
Vue.component('router_buses', require('./components/developer/routes_buses.vue').default);

Vue.component('change_bus', require('./components/task/change_bus.vue').default);
Vue.component('progress_task', require('./components/task/progress.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
