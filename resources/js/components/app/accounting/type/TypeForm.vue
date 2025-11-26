<script setup lang="ts">
import { AccountType, AccountTypeForm } from '@/types/accountType';
import { Switch } from '@/components/ui/switch';
import { useOptions } from '@/composables/useOptions';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea';
import { useForm, usePage } from '@inertiajs/vue3';
import AppSheet from '@/components/app/AppSheet.vue';
import { store, update } from '@/routes/accounting/types';
import { toast } from 'vue-sonner';
import { SharedData } from '@/types';
import { watch } from 'vue';

interface Props {
    open: boolean;
    type?: AccountType;
}

const props = defineProps<Props>();

const page = usePage<SharedData>();
const { activeStatus } = useOptions();

const form = useForm<AccountTypeForm>({
    code: '',
    name: '',
    description: '',
    normal_balance_debit: false,
    is_active: null,
});

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
}>();

watch(() => props.type, (newType) => {
    if (newType) {
        form.code = newType.code;
        form.name = newType.name;
        form.description = newType.description;
        form.normal_balance_debit = newType.normal_balance_debit;
        form.is_active = newType.is_active;
    }
});

const handleSubmit = () => {
    const method = props.type?.id ? 'PATCH' : 'POST';
    const url = props.type?.id ? update(props.type?.id).url : store().url;

    form.submit('post', url, {
        preserveScroll: true,
        forceFormData: true,
        headers: {
            'X-HTTP-Method-Override': method,
        },
        onSuccess: () => {
            emit('update:open', false);
            toast('Success!', { description: page.props.flash.success });

            form.reset();
        }
    });
};
</script>

<template>
    <AppSheet
        :model-value="props.open"
        title="Create Account Type"
        :show-footer="true"
        submit-label="Save"
        @update:model-value="value => emit('update:open', value)"
        @submit="handleSubmit"
    >
        <form class="mt-8 px-2">
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="col-span-full">
                    <Label for="code">Type code</Label>
                    <div class="mt-2">
                        <Input id="code" v-model="form.code" class="mt-1 block w-full" placeholder="Type code" />
                    </div>
                    <InputError :message="form.errors.code" />
                </div>

                <div class="col-span-full">
                    <Label for="name">Type name</Label>
                    <div class="mt-2">
                        <Input id="name" v-model="form.name" class="mt-1 block w-full" placeholder="Type name" />
                    </div>
                    <InputError :message="form.errors.name" />
                </div>

                <div class="col-span-full">
                    <Label for="description">Description</Label>
                    <div class="mt-2">
                        <Textarea v-model="form.description" placeholder="Type your description here." />
                    </div>
                    <InputError :message="form.errors.description" />
                </div>

                <div class="col-span-full">
                    <Label for="normal_balance_debit">
                        Normal Balance ({{form.normal_balance_debit ? 'Debit': 'Credit'}})
                    </Label>
                    <div class="mt-3">
                        <Switch v-model="form.normal_balance_debit" />
                    </div>
                    <InputError :message="form.errors.normal_balance_debit" />
                </div>

                <div class="col-span-full">
                    <Label for="is_active">Status: (Active/Inactive)</Label>
                    <div class="mt-2">
                        <Select v-model="form.is_active">
                            <SelectTrigger>
                                <SelectValue :placeholder="'Select status'"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectItem v-for="status in activeStatus" :key="status.value" :value="status.value">
                                    {{ status.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <InputError :message="form.errors.is_active" />
                </div>
            </div>
        </form>
    </AppSheet>
</template>
