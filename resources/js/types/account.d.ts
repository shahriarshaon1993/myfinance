import { ActiveStatus } from '.';

interface AccountNode {
    id: number;
    code: string;
    name: string;
    parent_id: number | null;
    account_type_id: number;
    is_summary: boolean;
    children: AccountNode[];
}

export interface AccountForm {
    code: string;
    name: string;
    account_type_id: number|null;
    parent_id: number|null;
    is_summary: boolean;
    description: string;
    opening_balance: number;
    opening_balance_type: string;
    opening_balance_date: string|null;
    is_active: ActiveStatus;
}
