import {Question as QuestionModel} from "../models/Question";
import {Meta} from "./Meta";

export interface DataFromResponse {
    data: Array<QuestionModel>;
    meta: Meta;
    links?: {
        first: string|null;
        last: string|null;
        next: string|null;
        prev: string|null;
    }
    status: boolean;
}