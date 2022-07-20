import Vue from 'vue';

Vue.component('question-list-on-main', require('./QuestionListOnMain.vue').default);

new Vue({
    el: '.questions-on-main',
});