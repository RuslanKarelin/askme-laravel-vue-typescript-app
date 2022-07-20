import {DateTime} from "luxon";

export interface IQuestion {
    id: string;
    createdAt: string | DateTime | null;
    title: string | null;
    detail: string | null;
    status: string | null;
    [key: string]: any;
}