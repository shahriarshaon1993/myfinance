<script setup lang="ts">
import DeleteButton from '@/components/app/DeleteButton.vue';
import DropdownAction from '@/components/app/DropdownAction.vue';
import RowsPerPageSelect from '@/components/app/RowsPerPageSelect.vue';
import SortTableHeader from '@/components/app/SortableHeader.vue';
import TableDataNotFound from '@/components/app/TableDataNotFound.vue';
import TablePagination from '@/components/app/TablePagination.vue';
import Badge from '@/components/ui/badge/Badge.vue';
import Button from '@/components/ui/button/Button.vue';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenuGroup, DropdownMenuItem, DropdownMenuSeparator } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { useAuthorization } from '@/composables/useAuthorization';
import { getSerial, getVariant } from '@/lib/utils';
import { destroy, index, bulkDestroy } from '@/routes/accounting/types';
import { Filters, SharedData } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { Search, SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';
import { AccountTypeResponse } from '@/types/accountType';

interface Props {
    types: AccountTypeResponse;
    myFilters: Filters;
}

const props = defineProps<Props>();

const { hasPermission, hasPermissions } = useAuthorization();

const isConfirmOpen = ref(false);
const isDeleteLoading = ref(false);
const isBulkConfirmOpen = ref(false);
const isBulkDeleteLoading = ref(false);
const typeId = ref<number | null>(null);

const page = usePage<SharedData>();
const selectedRows = ref<number[]>([]);
const filters = ref<Filters>(props.myFilters);

const emit = defineEmits<{
    (e: 'handleEdit', id: number): void;
}>();

const updateFilters = () => {
    router.get(index(), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};

const debouncedSearch = debounce(() => {
    updateFilters();
}, 500);

const confirmDelete = (id: number) => {
    typeId.value = id;
    isConfirmOpen.value = true;
};

const confirmBulkDelete = () => (isBulkConfirmOpen.value = true);

const handleDelete = () => {
    isDeleteLoading.value = true;
    router.delete(destroy(typeId.value!), {
        preserveState: true,
        onSuccess: () => {
            typeId.value = null;
            toast('Success!', {
                description: page.props.flash.success,
            });
            isDeleteLoading.value = false;
            isConfirmOpen.value = false;
        },
    });
};

const handleBulkDelete = () => {
    isBulkDeleteLoading.value = true;
    if (selectedRows.value.length > 0) {
        router.delete(bulkDestroy(), {
            data: { ids: selectedRows.value },
            preserveState: true,
            onSuccess: () => {
                selectedRows.value = [];
                toast('Success!', {
                    description: page.props.flash.success,
                });
                isBulkDeleteLoading.value = false;
                isBulkConfirmOpen.value = false;
            },
        });
    }
};

const toggleSelectAll = (checked: boolean): void => {
    if (checked) {
        selectedRows.value = [...writableIds.value];
    } else {
        selectedRows.value = [];
    }
};

const toggleRow = (id: number) => {
    if (!writableIds.value.includes(id)) return;

    const index = selectedRows.value.indexOf(id);

    if (index === -1) {
        selectedRows.value.push(id);
    } else {
        selectedRows.value.splice(index, 1);
    }
};

const sort = (column: string): void => {
    if (filters.value.sort_field === column) {
        filters.value.sort_order =
            filters.value.sort_order === 'asc' ? 'desc' : 'asc';
    } else {
        filters.value.sort_field = column;
        filters.value.sort_order = 'desc';
    }
    updateFilters();
};

const updatePerPage = (value: string): void => {
    filters.value.per_page = parseInt(value);
    updateFilters();
};

const changePage = (page: number) => {
    router.get(
        index(),
        { ...filters.value, page },
        {
            preserveState: true,
            preserveScroll: true,
        },
    );
};

const handleEdit = (id: number) => emit('handleEdit', id);

const selectAll = computed({
    get: () =>
        writableIds.value.length > 0 &&
        selectedRows.value.length === writableIds.value.length,
    set: (value) => toggleSelectAll(value),
});

const writableIds = computed(() =>
    props.types.data
        .filter((b: any) => b.is_writable)
        .map((b: any) => b.id)
);
</script>

<template>
    <!-- Toolbar -->
    <div class="flex items-center justify-between space-x-2">
        <div class="relative w-full items-center lg:w-[250px]">
            <Input
                type="text"
                v-model="filters.search"
                placeholder="Search..."
                @input="debouncedSearch"
                class="pl-8"
            />
            <span class="absolute inset-y-0 start-0 flex items-center justify-center px-2">
                <Search class="size-4 text-muted-foreground" />
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <Button
                v-if="hasPermission('delete type')"
                :disabled="selectedRows.length < 1"
                variant="destructive"
                class="cursor-pointer"
                @click="confirmBulkDelete"
            >
                <Trash2 class="h-4 w-4" />
                ({{ selectedRows.length }})
            </Button>
        </div>
    </div>

    <!-- Table -->
    <div class="rounded-md border">
        <div class="relative overflow-auto">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-if="hasPermission('delete type')" class="w-6">
                            <Checkbox :model-value="selectAll" @update:model-value="toggleSelectAll((selectAll = !selectAll))"/>
                        </TableHead>
                        <SortTableHeader
                            label="#"
                            sort-key="id"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                            class="w-8 text-center"
                        />
                        <SortTableHeader
                            label="Code"
                            sort-key="code"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Name"
                            sort-key="name"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Normal Balance"
                            sort-key="normal_balance_debit"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Active"
                            sort-key="is_active"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Created at"
                            sort-key="created_at"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <SortTableHeader
                            label="Updated at"
                            sort-key="updated_at"
                            :active-sort-key="filters.sort_field"
                            :sort-order="filters.sort_order"
                            @sort="sort"
                        />
                        <TableHead v-if="hasPermissions(['update type', 'delete type'])" class="text-right">
                            Actions
                        </TableHead>
                    </TableRow>
                </TableHeader>

                <TableBody>
                    <template v-if="types.data.length > 0">
                        <TableRow v-for="(type, index) in types.data" @click="toggleRow(type.id)" :key="type.id" class="cursor-pointer">
                            <TableCell v-if="hasPermissions(['delete type', 'export type'])" @click.stop>
                                <Checkbox :model-value="selectedRows.includes(type.id)" @update:model-value="toggleRow(type.id)" />
                            </TableCell>
                            <TableCell class="text-center">
                                {{ getSerial(index, types.meta) }}
                            </TableCell>
                            <TableCell class="text-left">
                                {{ type.code }}
                            </TableCell>
                            <TableCell class="text-left">
                                {{ type.name }}
                            </TableCell>
                            <TableCell class="text-left">
                                {{ type.normal_balance_debit ? 'Debit': 'Credit' }}
                            </TableCell>
                            <TableCell class="text-left">
                                <Badge
                                    :variant="getVariant(type.status.color)"
                                >
                                    {{ type.status.label }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                {{ type.created_at }}
                            </TableCell>
                            <TableCell>
                                {{ type.updated_at }}
                            </TableCell>

                            <TableCell v-if="hasPermissions(['update type', 'delete type'])" class="text-right" @click.stop>
                                <DropdownAction>
                                    <DropdownMenuGroup>
                                        <DropdownMenuItem
                                            v-if="hasPermission('update type') && type.is_writable"
                                            @click="handleEdit(type.id)"
                                        >
                                            <SquarePen class="mr-2 h-4 w-4"/>
                                            Edit
                                        </DropdownMenuItem>
                                    </DropdownMenuGroup>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem
                                        v-if="hasPermission('delete type') && type.is_writable"
                                        @click="confirmDelete(type.id)"
                                    >
                                        <Trash2 class="mr-2 h-4 w-4" />
                                        <span>Delete</span>
                                    </DropdownMenuItem>
                                </DropdownAction>
                            </TableCell>
                        </TableRow>
                    </template>

                    <TableDataNotFound v-else :length="6" />
                </TableBody>
            </Table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between px-2">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ selectedRows.length }} of
            {{ types.meta.total }}
            row(s) selected.
        </div>

        <div class="flex items-center space-x-6 lg:space-x-8">
            <RowsPerPageSelect
                label="Per Page"
                v-model="filters.per_page"
                @update:model-value="updatePerPage"
            />

            <TablePagination
                :current-page="types.meta.current_page"
                :last-page="types.meta.last_page"
                :total="types.meta.total"
                :per-page="types.meta.per_page"
                :links="types.meta.links"
                @page-change="changePage"
            />
        </div>
    </div>

    <!-- Confirmation Dialog -->
    <DeleteButton v-model="isConfirmOpen" :is-processing="isDeleteLoading" @on-destroy="handleDelete" />
    <DeleteButton v-model="isBulkConfirmOpen" :is-processing="isBulkDeleteLoading" @on-destroy="handleBulkDelete" />
</template>
