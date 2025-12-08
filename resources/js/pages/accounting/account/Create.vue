<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index } from '@/routes/accounting/accounts';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AccountForm from '@/components/app/accounting/account/AccountForm.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Accounts', href: index().url },
    { title: 'Create', href: create().url },
];
</script>

<template>
    <Head title="Create Account" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="space-y-6">
                <HeadingSmall title="Create account" description="Create a new account.">
                    <Button variant="link" as="a" :href="index().url" class="cursor-pointer">
                        <span>Back to accounts</span>
                    </Button>
                </HeadingSmall>

                <!-- Form -->
                <AccountForm />
            </div>
        </div>
    </AppLayout>
</template>
