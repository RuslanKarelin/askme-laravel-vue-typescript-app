import {DateTime} from "luxon";
import {IAnswer} from "../interfaces/models/IAnswer";
import {setDates} from "../helpers/DatesSetter";

export class Answer implements IAnswer {
    id: number;
    createdAt: string | DateTime | null;
    detail: string | null;
    [key: string]: any;

    constructor(data: IAnswer) {
        Object.assign(this, data);
        setDates(this, ['created_at', 'updated_at']);
    }
}