import {DateTime} from "luxon";

export interface IQuestion {
    id: number;
    createdAt: string | DateTime | null;
    title: string | null;
    detail: string | null;
    status: string | null;
    [key: string]: any;
}