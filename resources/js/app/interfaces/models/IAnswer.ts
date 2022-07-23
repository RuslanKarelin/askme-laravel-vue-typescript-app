import {DateTime} from "luxon";
import {IComment} from "./IComment";

export interface IAnswer {
    id: number;
    detail: string | null;
    createdAt: string | DateTime | null;
    updated_at: string | DateTime | null;
    can: {update: boolean, destroy: boolean};
    likes_count: number;
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
    comments: IComment[];
}