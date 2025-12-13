<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store } from '@/routes/accounting/accounts';
import { SharedData } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import { useOptions } from '@/composables/useOptions';
import { AccountForm } from '@/types/account';
import InputComboboxSelect from '@/components/InputComboboxSelect.vue';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';

import { VueDatePicker } from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const page = usePage<SharedData>();
const { accountTypes, activeStatus, accounts } = useOptions();

const form = useForm<AccountForm>({
    code: '',
    name: '',
    account_type_id: null,
    parent_id: null,
    is_summary: false,
    description: '',
    opening_balance: 0,
    opening_balance_type: '',
    opening_balance_date: '',
    is_active: null,
});

const submit = () => {
    if (form.is_summary) {
        form.opening_balance = 0;
        form.opening_balance_type = '';
        form.opening_balance_date = null;
    }

    form.post(store().url, {
        preserveScroll: true,
        onSuccess: () => {
            toast('Success!', { description: page.props.flash.success });
        },
    });
};
</script>

<template>
    <div class="w-full md:max-w-4xl">
        <div class="w-full space-y-12">
            <form class="space-y-6" @submit.prevent="submit">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <Label for="code">Account code</Label>
                        <div class="mt-2">
                            <Input
                                id="code"
                                v-model="form.code"
                                class="mt-1 block w-full"
                                placeholder="Account code"
                            />
                        </div>
                        <InputError :message="form.errors.code" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="name">Account name</Label>
                        <div class="mt-2">
                            <Input
                                id="name"
                                v-model="form.name"
                                class="mt-1 block w-full"
                                placeholder="Account name"
                            />
                        </div>
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="parent_id">Paren account</Label>
                        <div class="mt-2">
                            <InputComboboxSelect
                                v-model="form.parent_id"
                                :options="accounts"
                                placeholder="Select parent account"
                                width="100%"
                            />
                        </div>
                        <InputError :message="form.errors.parent_id" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="account_type">Account types</Label>
                        <div class="mt-2">
                            <InputComboboxSelect
                                v-model="form.account_type_id"
                                :options="accountTypes"
                                placeholder="Select account type"
                                width="100%"
                            />
                        </div>
                        <InputError :message="form.errors.account_type_id" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="is_active">Status</Label>
                        <div class="mt-2">
                            <Select v-model="form.is_active">
                                <SelectTrigger>
                                    <SelectValue :placeholder="'Select status'"/>
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem
                                        v-for="status in activeStatus"
                                        :key="status.value"
                                        :value="status.value"
                                    >
                                        {{ status.label }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <InputError :message="form.errors.is_active" />
                    </div>

                    <div class="col-span-full">
                        <Label for="is_summary">Is summary account?</Label>
                        <div class="mt-2">
                            <Switch id="is_summary" v-model="form.is_summary" />
                        </div>
                        <InputError :message="form.errors.is_summary" />
                    </div>
                </div>

                <Separator v-if="!form.is_summary" class="my-4" />

                <Transition>
                    <div v-if="!form.is_summary" class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <Label for="opening_balance">Opening balance</Label>
                        <div class="mt-2">
                            <Input
                                id="opening_balance"
                                type="number"
                                v-model="form.opening_balance"
                                class="mt-1 block w-full"
                                placeholder="Opening balance"
                            />
                        </div>
                        <InputError :message="form.errors.opening_balance" />
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="opening_balance_type">Balance Types</Label>

                        <div class="mt-2">
                            <InputComboboxSelect
                                v-model="form.opening_balance_type"
                                :options="[
                                    { label: 'Debit', value: 'D' },
                                    { label: 'Credit', value: 'C' },
                                ]"
                                placeholder="Select parent account"
                                width="100%"
                            />

                            <InputError
                                :message="form.errors.opening_balance_type"
                            />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <Label for="opening_balance_date">Balance Date</Label>
                        <div class="mt-2">
                            <VueDatePicker
                                v-model="form.opening_balance_date"
                                :time-config="{ enableTimePicker: false }"
                            ></VueDatePicker>
                        </div>
                        <InputError
                            :message="form.errors.opening_balance_date"
                        />
                    </div>
                </div>
                </Transition>

                <Separator class="my-4" />

                <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="col-span-full">
                        <Label for="description">Description</Label>
                        <div class="mt-2">
                            <Textarea
                                v-model="form.description"
                                placeholder="Type your description."
                            />
                        </div>
                        <InputError :message="form.errors.description" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing" class="cursor-pointer">
                        Save
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
