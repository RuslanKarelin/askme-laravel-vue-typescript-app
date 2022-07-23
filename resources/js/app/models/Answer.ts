import {DateTime} from "luxon";
import {IAnswer} from "../interfaces/models/IAnswer";
import {setDates} from "../helpers/DatesSetter";
import {IComment} from "../interfaces/models/IComment";

export class Answer implements IAnswer {
    id: number;
    detail: string | null;
    createdAt: string | DateTime | null;
    updated_at: string | DateTime | null;
    can: {update: boolean, destroy: boolean};
    likes_count: number;
    user: {
        id: number;
        profile: {
            first_name: string;
            last_name: string;
            about: string;
            avatar: string;
            fullName: string;
        }
        roles: {id: number, title: string}[];
    };
    comments: IComment[];

    constructor(data: IAnswer) {
        Object.assign(this, data);
        setDates(this, ['created_at', 'updated_at']);
    }
}