<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { store, update } from '@/routes/users';
import { SharedData } from '@/types';
import { User, UserForm } from '@/types/users';
import { useForm, usePage } from '@inertiajs/vue3';
import { startCase } from 'lodash';
import { toast } from 'vue-sonner';
import { useOptions } from '@/composables/useOptions';
import { AccountForm } from '@/types/account';
import InputComboboxSelect from '@/components/InputComboboxSelect.vue';

const frameworks = [
    { value: 'next.js', label: 'Next.js' },
    { value: 'sveltekit', label: 'SvelteKit' },
    { value: 'nuxt.js', label: 'Nuxt.js' },
    { value: 'remix', label: 'Remix' },
    { value: 'astro', label: 'Astro' },
]

const page = usePage<SharedData>();
const { accountTypes, activeStatus, accounts } = useOptions();

const form = useForm<AccountForm>({
    code: '',
    name: '',
    account_type_id: null,
    parent_id: null,
    is_summary: null,
    description: '',
    opening_balance: '',
    opening_balance_type: '',
    opening_balance_date: null,
    is_active: null,
});

const submit = () => {};
</script>

<template>
    <div class="w-full md:max-w-4xl">
        <div class="w-full space-y-12">
            <pre>{{form.parent_id}}</pre>
            <form class="space-y-6" @submit.prevent="submit">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <Label for="name">Paren account</Label>
                        <InputComboboxSelect v-model="form.parent_id" :options="frameworks" placeholder="Paren account" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
