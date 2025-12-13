<script setup lang="ts">
import { ref, computed, watch, nextTick } from 'vue';
import { CheckIcon, ChevronsUpDownIcon } from "lucide-vue-next";
import { cn } from "@/lib/utils";

import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from "@/components/ui/command";

import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from "@/components/ui/popover";

import { Button } from "@/components/ui/button";

interface Option {
    label: string
    value: string
}

interface Props {
    width?: string;
    options: Option[];
    placeholder?: string;
    modelValue: any;
}

const props = defineProps<Props>();

const emit = defineEmits(["update:modelValue"]);

const open = ref(false);
const comboboxWidth = ref('300px');
const triggerRef = ref<HTMLElement | null>(null);

watch(open, async (val) => {
    if (val && triggerRef.value) {
        await nextTick();
        const triggerEl =
            triggerRef.value instanceof HTMLElement
                ? triggerRef.value
                : triggerRef.value?.$el;
        if (triggerEl) {
            comboboxWidth.value = `${triggerEl.offsetWidth}px`;
        }
    }
});

const selected = computed(() =>
    props.options.find(o => o.value === props.modelValue)
);

const choose = (val: string) => {
    emit("update:modelValue", val === props.modelValue ? "" : val)
    open.value = false
};
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                ref="triggerRef"
                variant="outline"
                role="combobox"
                :aria-expanded="open"
                class="justify-between"
                :style="{ width: props.width || '300px' }"
            >
                {{ selected?.label || props.placeholder || "Select option..." }}
                <ChevronsUpDownIcon class="opacity-50" />
            </Button>
        </PopoverTrigger>

        <PopoverContent class="p-0" :style="{ width: comboboxWidth }">
            <Command>
                <CommandInput class="h-9" :placeholder="props.placeholder || 'Search...'" />
                <CommandList>
                    <CommandEmpty>No result found.</CommandEmpty>

                    <CommandGroup>
                        <CommandItem
                            v-for="item in props.options"
                            :key="item.value"
                            :value="item.value"
                            @select="(ev) => choose(ev.detail.value)"
                        >
                            {{ item.label }}
                            <CheckIcon :class="cn('ml-auto', props.modelValue === item.value ? 'opacity-100' : 'opacity-0')"/>
                        </CommandItem>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
