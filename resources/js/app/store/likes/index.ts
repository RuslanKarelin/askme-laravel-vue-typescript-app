import { Module } from "vuex";
import {LikeState} from "../../types/LikesState";
import {RootState} from "../../types/RootState";
import { state } from "./state";
import { actions } from "./actions";
import { mutations } from "./mutations";
import { getters } from './getters';

const namespaced = true;

export const likes: Module<LikeState, RootState> = {
    namespaced,
    state,
    actions,
    mutations,
    getters
};