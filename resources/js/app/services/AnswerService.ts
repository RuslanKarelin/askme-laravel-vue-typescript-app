import axios, {AxiosStatic} from "axios";
import {IQueryParameters} from "../interfaces/IQueryParameters";
import {IAnswer} from "../interfaces/models/IAnswer";
import {Answer} from "../models/Answer";
import {IComment} from "../interfaces/models/IComment";
import {Comment} from "../models/Comment";

export class AnswerService {
    client: AxiosStatic;

    constructor() {
        this.client = axios;
    }

    getList(questionId: number, query: IQueryParameters = {}) {
        const params: string = (new URLSearchParams(query)).toString();
        let url = `/questions/${questionId}/answers`;
        if (params.length) {
            url = url + '?' + params;
        }
        return this.client.get(url)
            .then((obj) => {
                return {
                    data: (obj.data.data || []).map(
                        (item: IAnswer) => {
                            let answer: IAnswer = new Answer(item);
                            answer.comments = answer.comments.map((comment: IComment) => new Comment(comment));
                            return answer;
                        }
                    ),
                    meta: obj.data.meta,
                    links: obj.data.links,
                    status: obj.data.status
                };
            });
    }

    create(questionId: number, query: IQueryParameters = {}) {
        let url = `/questions/${questionId}/answers`;
        return this.client.post(url, query)
            .then((obj) => {
                let answer: IAnswer = new Answer(obj.data.data);
                answer.comments = answer.comments.map((comment: IComment) => new Comment(comment));
                return answer;
            });
    }

    update(questionId: number, answerId: number, query: IQueryParameters = {}) {
        let url = `/questions/${questionId}/answers/${answerId}`;
        return this.client.patch(url, query)
            .then((obj) => {
                let answer: IAnswer = new Answer(obj.data.data);
                answer.comments = answer.comments.map((comment: IComment) => new Comment(comment));
                return answer;
            });
    }

    destroy(questionId: number, answerId: number) {
        let url = `/questions/${questionId}/answers/${answerId}`;
        return this.client.delete(url)
            .then((obj) => {
                return obj;
            });
    }

    addLike(answerId: number) {
        let url = `/answers/${answerId}/add-like`;
        return this.client.post(url)
            .then((obj) => {
                return obj.status;
            });
    }
}