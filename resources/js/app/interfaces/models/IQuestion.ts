import {DateTime} from "luxon";

export interface IQuestion {
    id: number;
    createdAt: string | DateTime | null;
    title: string | null;
    detail: string | null;
    answers_count: number;
    can: {update: boolean, destroy: boolean};
    state: {views: number};
    status: {id: number, title: string};
    tags: {id: number, title: string}[];
    updated_at: string | DateTime | null;
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
}