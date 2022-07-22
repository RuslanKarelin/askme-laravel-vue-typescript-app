import {ActionTree} from "vuex";
import {AnswerService} from '../../services/AnswerService'
import {QuestionService} from '../../services/QuestionService'
import {LikeState} from "../../types/LikesState";
import {RootState} from "../../types/RootState";

const answerService = new AnswerService();
const questionService = new QuestionService();

export const actions: ActionTree<LikeState, RootState> = {
    async answerAddLike({commit, getters}, payload) {
        try {
            let status: number = await answerService.addLike(payload.answerId);
            let answers = getters['answers'];
            let id: number = payload.answerId;

            if (!Object.prototype.hasOwnProperty.call(answers, id)) {
                answers[id] = payload.count;
            }

            if (status === 201) {
                ++answers[id];
            } else {
                --answers[id];
            }

            commit('setAnswers', answers);
        }
        catch (e) {
            return null;
        }
    },

    async questionAddLike({commit, getters}, payload) {
        try {
            let status: number = await questionService.addLike(payload.questionId);
            let questions = getters['questions'];
            let id: number = payload.questionId;

            if (!Object.prototype.hasOwnProperty.call(questions, id)) {
                questions[id] = payload.count;
            }

            if (status === 201) {
                ++questions[id];
            } else {
                --questions[id];
            }

            commit('setQuestions', questions);
        }
        catch (e) {
            return null;
        }
    }
};