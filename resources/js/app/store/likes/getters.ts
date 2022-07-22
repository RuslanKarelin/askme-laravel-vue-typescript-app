import {GetterTree} from "vuex";
import {LikeState} from "../../types/LikesState";
import {RootState} from "../../types/RootState";

const getters: GetterTree<LikeState, RootState> = {
    answers(state) {
        return state.answers;
    },
    questions(state) {
        return state.questions;
    }
};

export { getters }