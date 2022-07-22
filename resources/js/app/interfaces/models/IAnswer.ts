import {DateTime} from "luxon";

export interface IAnswer {
    id: number;
    createdAt: string | DateTime | null;
    detail: string | null;
    [key: string]: any;
}