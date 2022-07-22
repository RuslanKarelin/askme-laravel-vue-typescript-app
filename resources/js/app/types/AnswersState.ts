import {Answer} from "../models/Answer";
import {Meta} from "../types/Meta";

export interface AnswerState {
    answers: Answer[] | null;
    meta: Meta | null;
}