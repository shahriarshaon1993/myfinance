<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { BreadcrumbItem, Filters } from '@/types';
import { index, create } from '@/routes/accounting/accounts';
import AppLayout from '@/layouts/AppLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import { useAuthorization } from '@/composables/useAuthorization';
import { AccountNode } from '@/types/account';
import TreeNode from '@/components/app/accounting/account/TreeNode.vue';
import { router } from '@inertiajs/vue3';
import { destroy } from '@/routes/accounting/accounts';
import { toast } from 'vue-sonner';

defineProps<{
    accounts: AccountNode[];
    filters: Filters;
}>();

const { hasPermission } = useAuthorization();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Accounts', href: index().url }];

const createAccount = () => {}
const editAccount = (node: any) => {console.log(node)};

const deleteAccount = (id: number) => {
    toast.promise<string>(
        new Promise<string>((resolve, reject) => {
            router.delete(destroy(id), {
                preserveState: true,
                onSuccess: (page: any) => {
                    const message = page.props.flash?.success ?? "Success";
                    resolve(message);
                },
                onError: (errors: any) => {
                    const message = errors.range ?? "Something went wrong";

                    reject(message);
                }
            });
        }),
        {
            loading: "Loading...",
            success: (msg: string) => msg,
            error: (err: any) => err,
        }
    );
};

const addChildAccount = (node: any) => {console.log(node)};
</script>

<template>
    <Head title="Accounts" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <!-- Heading -->
                <HeadingSmall title="Chart of Accounts" description="Manage your all accounts here">
                    <Button
                        v-if="hasPermission('create account')"
                        as="a" :href="create().url"
                    >
                        <span>Create</span>
                        <Plus class="h-4 w-4" />
                    </Button>
                </HeadingSmall>

                <ul class="space-y-6">
                    <TreeNode
                        v-for="node in accounts"
                        :key="node.id"
                        :node="node"
                        :level="0"
                        @edit="editAccount"
                        @delete="deleteAccount"
                        @add-child="addChildAccount"
                    />
                </ul>
            </div>
        </div>
    </AppLayout>
</template>
