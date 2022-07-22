import {MutationTree} from "vuex";
import {AnswerState} from "../../types/AnswersState";
import {Meta} from "../../types/Meta";
import {Answer} from "../../models/Answer";

export const mutations: MutationTree<AnswerState> = {
    setAnswers(state, payload: Answer[]) {
        state.answers = payload;
    },
    setMeta(state, payload: Meta) {
        state.meta = payload;
    }
}