import {DateTime} from "luxon";
import {IComment} from "../interfaces/models/IComment";
import {setDates} from "../helpers/DatesSetter";

export class Comment implements IComment {
    id: number;
    detail: string | null;
    createdAt: string | DateTime | null;
    updated_at: string | DateTime | null;
    can: {update: boolean, destroy: boolean};
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

    constructor(data: IComment) {
        Object.assign(this, data);
        setDates(this, ['created_at', 'updated_at']);
    }
}