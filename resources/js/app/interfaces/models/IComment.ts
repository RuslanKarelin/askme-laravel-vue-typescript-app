import {DateTime} from "luxon";

export interface IComment {
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
}