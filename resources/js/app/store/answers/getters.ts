import {GetterTree} from "vuex";
import {AnswerState} from "../../types/AnswersState";
import {RootState} from "../../types/RootState";

const getters: GetterTree<AnswerState, RootState> = {
    answers(state) {
        return state.answers;
    },
    meta(state) {
        return state.meta;
    }
};

export { getters }