import { ActiveStatus, PaginationLinks, PaginationMeta } from '.';

export interface AccountTypeForm {
    code: string;
    name: string;
    normal_balance_debit: boolean;
    is_active: string | null;

    [key: string]: any;
}

export interface AccountType {
    code: string;
    name: string;
    normal_balance_debit: boolean;
    is_writable: boolean;
    is_active: ActiveStatus;
    created_at: string;
    updated_at: string;

    [key: string]: any;
}

export interface AccountTypeResponse {
    data: AccountType[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
