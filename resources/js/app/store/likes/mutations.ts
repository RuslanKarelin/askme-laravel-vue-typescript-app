import {MutationTree} from "vuex";
import {LikeState} from "../../types/LikesState";

export const mutations: MutationTree<LikeState> = {
    setAnswers(state, payload) {
        state.answers[payload.id] = payload.count;
    },

    setQuestions(state, payload) {
        state.questions[payload.id] = payload.count;
    }
}