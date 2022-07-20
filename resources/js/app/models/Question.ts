import {DateTime} from "luxon";
import {IQuestion} from "../interfaces/models/IQuestion";
import {setDates} from "../helpers/DatesSetter";


export class Question implements IQuestion {
    id: string;
    createdAt: string | DateTime | null;
    title: string | null;
    detail: string | null;
    status: string | null;
    [key: string]: any;

    constructor(data: IQuestion) {
        Object.assign(this, data);
        setDates(this, ['created_at', 'updated_at']);
    }

    getField(fieldName: string): string | any {
        return this[fieldName] || '';
    }
}