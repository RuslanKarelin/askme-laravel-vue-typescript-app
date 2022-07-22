import Vue from 'vue'
import Vuex from 'vuex'

import {answers} from './answers/index'
import {likes} from './likes/index'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        answers,
        likes
    },
    state: {},
    actions: {},
    getters: {},
    mutations: {}
})

