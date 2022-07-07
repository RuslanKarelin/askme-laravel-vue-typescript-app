//import './bootstrap';


//window.Vue = require('vue').default;
import Vue from 'vue';
import ExampleComponent from './components/ExampleComponent';

export default new Vue({
    render: h => h(ExampleComponent)
});
 /*
 Vue.component('example-component', require('./components/ExampleComponent.vue').default);
 const app = new Vue({
   el: '#app',
});*/