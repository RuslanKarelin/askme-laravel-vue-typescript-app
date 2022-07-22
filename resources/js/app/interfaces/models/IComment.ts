import {DateTime} from "luxon";

export interface IComment {
    id: number;
    createdAt: string | DateTime | null;
    detail: string | null;
    [key: string]: any;
}