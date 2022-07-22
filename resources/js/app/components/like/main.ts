import Vue from 'vue';
import store from '../../store';

Vue.component('like-component', require('./Like.vue').default);

new Vue({
    store,
    el: '.like-component',
});