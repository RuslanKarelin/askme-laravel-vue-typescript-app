import axios, {AxiosStatic} from "axios";
import {IQueryParameters} from "../interfaces/IQueryParameters";
import {IQuestion} from "../interfaces/models/IQuestion";
import {Question} from "../models/Question";

export class QuestionService {
    client: AxiosStatic;
    url = '/questions/getList';

    constructor() {
        this.client = axios;
    }

    getList(query: IQueryParameters = {}) {
        const params: string = (new URLSearchParams(query)).toString();
        let url: string = this.url
        if (params.length) {
            url = url + '?' + params;
        }
        return this.client.get(url)
            .then((obj) => {
                return {
                    data: (obj.data.data || []).map((item: IQuestion) => new Question(item)),
                    meta: obj.data.meta,
                    links: obj.data.links
                };
            });
    }

    addLike(questionId: number) {
        let url = `/questions/${questionId}/add-like`;
        return this.client.post(url)
            .then((obj) => {
                return obj.status;
            });
    }
}