<script lang="ts" setup>
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { useAuthorization } from '@/composables/useAuthorization';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/accounting/types';
import { type BreadcrumbItem, Filters } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { AccountType, AccountTypeResponse } from '@/types/accountType';
import TypeTable from '@/components/app/accounting/type/TypeTable.vue';
import { ref } from 'vue';
import TypeForm from '@/components/app/accounting/type/TypeForm.vue';

interface Props {
    types: AccountTypeResponse;
    filters: Filters;
}

const props = defineProps<Props>();

const { hasPermission } = useAuthorization();

const type = ref<AccountType>();
const openEditType = ref<boolean>(false);
const openCreateType = ref<boolean>(false);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Account types', href: index().url }];

const handleCreateType = () => openCreateType.value = true;

const handleEditType = (id: number) => {
    type.value = props.types.data.find(t => t.id === id);
    openEditType.value = true;
};
</script>

<template>
    <Head title="Account types" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall
                    title="Account types"
                    description="Manage Account types and their permissions."
                >
                    <Button
                        v-if="hasPermission('create type')"
                        @click="handleCreateType"
                        class="cursor-pointer"
                    >
                        <span>Create</span>
                        <Plus class="h-4 w-4" />
                    </Button>
                </HeadingSmall>

                <!-- Table -->
                <TypeTable :types="types" :my-filters="filters" @handle-edit="handleEditType"/>
            </div>
        </div>

        <!-- Create account type -->
        <TypeForm v-model:open="openCreateType"/>

        <!-- Edit account type -->
        <TypeForm v-model:open="openEditType" :type="type"/>
    </AppLayout>
</template>
