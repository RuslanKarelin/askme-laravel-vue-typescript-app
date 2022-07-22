import { Module } from "vuex";
import {AnswerState} from "../../types/AnswersState";
import {RootState} from "../../types/RootState";
import { state } from "./state";
import { actions } from "./actions";
import { mutations } from "./mutations";
import { getters } from './getters';

const namespaced = true;

export const answers: Module<AnswerState, RootState> = {
    namespaced,
    state,
    actions,
    mutations,
    getters
};