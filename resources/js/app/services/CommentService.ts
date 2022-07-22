import axios, {AxiosStatic} from "axios";
import {IQueryParameters} from "../interfaces/IQueryParameters";
import {Comment} from "../models/Comment";

export class CommentService {
    client: AxiosStatic;

    constructor() {
        this.client = axios;
    }

    create(answerId: number, query: IQueryParameters = {}) {
        try {
            let url = `/answers/${answerId}/comments`;
            return this.client.post(url, query)
                .then((obj) => {
                    return new Comment(obj.data.data);
                });
        } catch (e) {
            console.log(e)
        }
    }

    update(commentId: number, answerId: number, query: IQueryParameters = {}) {
        try {
            let url = `/answers/${answerId}/comments/${commentId}`;
            return this.client.patch(url, query)
                .then((obj) => {
                    return new Comment(obj.data.data);
                });
        } catch (e) {
            console.log(e)
        }
    }

    destroy(commentId: number, answerId: number) {
        try {
            let url = `/answers/${answerId}/comments/${commentId}`;
            return this.client.delete(url)
                .then((obj) => {
                    return obj;
                });
        } catch (e) {
            console.log(e)
        }
    }
}