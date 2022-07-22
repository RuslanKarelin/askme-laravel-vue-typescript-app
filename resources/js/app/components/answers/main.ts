import Vue from 'vue';
import Vuelidate from 'vuelidate';
import store from '../../store';

Vue.use(Vuelidate)
Vue.component('answers-component', require('./Answers.vue').default);

new Vue({
    store,
    el: '.answers',
});