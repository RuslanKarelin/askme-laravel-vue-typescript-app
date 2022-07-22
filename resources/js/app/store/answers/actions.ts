import {ActionTree} from "vuex";
import {AnswerService} from '../../services/AnswerService'
import {AnswerState} from "../../types/AnswersState";
import {RootState} from "../../types/RootState";

const answerService = new AnswerService();

export const actions: ActionTree<AnswerState, RootState> = {
    async getList({commit}, payload) {
        try {
            let result = await answerService.getList(payload.questionId, payload.query);
            commit('setAnswers', result.data);
            commit('setMeta', result.meta);
        }
        catch (e) {
            return null;
        }
    },

    async create({commit, getters}, payload) {
        try {
            let result = await answerService.create(payload.questionId, payload.query);
            let answers = getters['answers'];
            let meta = getters['meta'];
            ++meta.total;
            answers.unshift(result);
            commit('setAnswers', answers);
            commit('setMeta', meta);
        }
        catch (e) {
            return null;
        }
    },

    async update({commit, getters}, payload) {
        try {
            let result = await answerService.update(payload.questionId, payload.answerId, payload.query);
            let answers = getters['answers'];
            answers[payload.index] = result;
            commit('setAnswers', answers);
        }
        catch (e) {
            return null;
        }
    },

    async destroy({commit, getters}, payload) {
        try {
            await answerService.destroy(payload.questionId, payload.answerId);
            let answers = getters['answers'];
            let meta = getters['meta'];
            --meta.total;
            answers.splice(payload.index, 1);
            commit('setAnswers', answers);
            commit('setMeta', meta);
        }
        catch (e) {
            return null;
        }
    }
};