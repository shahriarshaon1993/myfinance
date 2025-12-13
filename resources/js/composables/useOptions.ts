import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { SharedData, OptionItem, SelectOption, ModuleWithPermissions } from '@/types';

export function useOptions() {
    const page = usePage<SharedData>();

    const options = computed(() => page.props.options);

    const roles = computed<OptionItem[]>(() => options.value.roles);
    const modules = computed<ModuleWithPermissions[]>(() => options.value.modules);
    const accounts = computed<SelectOption[]>(() => options.value.accounts);
    const accountTypes = computed<SelectOption[]>(() => options.value.accountTypes);
    const activeStatus = computed<SelectOption[]>(() => options.value.activeStatus);

    return { roles, modules, accounts, accountTypes, activeStatus };
}
