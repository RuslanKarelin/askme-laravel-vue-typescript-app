import {AnswerState} from "../../types/AnswersState";

export const state: AnswerState = {
    answers: null,
    meta: {
        total: 0,
        last_page: 0,
        current_page: 0,
        from: 0,
        links: [],
        path: '',
        per_page: 0,
        to: 0
    }
}