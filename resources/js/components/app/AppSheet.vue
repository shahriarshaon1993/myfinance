<script setup lang="ts">
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetDescription, SheetFooter, SheetClose } from "@/components/ui/sheet";
import { Button } from "@/components/ui/button";
import { computed } from 'vue';

interface Props {
    modelValue: boolean;
    title?: string;
    description?: string;
    submitLabel?: string;
    showFooter?: boolean;
    maxWidth?: string;
}

const props = withDefaults(defineProps<Props>(), {
    maxWidth: 'md',
    showFooter: false,
});

const emit = defineEmits(["update:modelValue", "submit"]);

const closeSheet = () => emit("update:modelValue", false);

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '3xl': 'sm:max-w-3xl',
        '4xl': 'sm:max-w-4xl',
        '5xl': 'sm:max-w-5xl',
        '6xl': 'sm:max-w-6xl',
    }[props.maxWidth];
});
</script>

<template>
    <Sheet :open="props.modelValue" @update:open="emit('update:modelValue', $event)">
        <!-- Sheet body -->
        <SheetContent class="w-full" :class="maxWidthClass">
            <div class="flex flex-col h-full max-h-screen">
                <SheetHeader class="border-b border-b-gray-300">
                    <SheetTitle>{{ props.title }}</SheetTitle>
                    <SheetDescription>{{ props.description }}</SheetDescription>
                </SheetHeader>

                <!-- Custom form fields -->
                <div class="flex-1 overflow-y-auto overflow-x-hidden">
                    <slot />
                </div>

                <!-- Footer -->
                <SheetFooter v-if="showFooter">
                    <Button type="button" @click="emit('submit')" class="cursor-pointer">
                        {{ props.submitLabel || 'Save changes' }}
                    </Button>

                    <SheetClose as-child>
                        <Button variant="outline" @click="closeSheet" class="cursor-pointer">
                            Close
                        </Button>
                    </SheetClose>
                </SheetFooter>
            </div>
        </SheetContent>
    </Sheet>
</template>
