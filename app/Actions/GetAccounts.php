<?php

declare(strict_types=1);

namespace App\Actions;

use App\DTOs\FilterDto;
use App\Models\Account;

final class GetAccounts
{
    /**
     * handle getting accounts with filters.
     *
     *
     * @return array{
     *    tree: array<int, array{
     *        id: int,
     *        code: string,
     *        name: string,
     *        parent_id: int|null,
     *        account_type_id: int,
     *        is_summary: bool,
     *        children: array<int, array<string, mixed>>
     *    }>,
     *    filters: FilterDto,
     * }
     */
    public function handle(FilterDto $filters): array
    {
        /**
         * @var array<int, array{id: int, code: string, name: string, parent_id: int|null,  account_type_id: int, is_summary: bool }> $accounts
         */
        $accounts = Account::query()
            ->select(['id', 'code', 'name', 'parent_id', 'account_type_id', 'is_summary'])
            ->orderBy('code')
            ->get()->toArray();

        $tree = $this->buildTree($accounts);

        return ['tree' => $tree, 'filters' => $filters];
    }

    /**
     * Build a hierarchical tree
     *
     * @param array<int, array{
     *      id: int,
     *      code: string,
     *      name: string,
     *      parent_id: int|null,
     *      account_type_id: int,
     *      is_summary: bool
     * }> $accounts
     * @return array<int, array{
     *      id: int,
     *      code: string,
     *      name: string,
     *      parent_id: int|null,
     *      account_type_id: int,
     *      is_summary: bool,
     *      children: array<int, array<string, mixed>>
     * }>
     */
    private function buildTree(array $accounts): array
    {
        $items = [];
        $tree = [];

        foreach ($accounts as $account) {
            $account['children'] = [];
            $items[$account['id']] = $account;
        }

        foreach ($items as &$node) {
            if ($node['parent_id']) {
                $items[$node['parent_id']]['children'][] = &$node;
            } else {
                $tree[] = &$node;
            }
        }

        return $tree;
    }
}
