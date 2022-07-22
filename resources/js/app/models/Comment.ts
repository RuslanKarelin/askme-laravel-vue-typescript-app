import {DateTime} from "luxon";
import {IComment} from "../interfaces/models/IComment";
import {setDates} from "../helpers/DatesSetter";

export class Comment implements IComment {
    id: number;
    createdAt: string | DateTime | null;
    detail: string | null;
    [key: string]: any;

    constructor(data: IComment) {
        Object.assign(this, data);
        setDates(this, ['created_at', 'updated_at']);
    }
}