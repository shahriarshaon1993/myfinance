<script setup lang="ts">
import { AccountNode } from '@/types/account';
import { ref } from 'vue';
import {
    ChevronDown,
    ChevronRight,
    Folder,
    FileText,
    Pencil,
    Trash,
    Plus,
} from 'lucide-vue-next';
import TreeNode from '@/components/app/accounting/account/TreeNode.vue';
import { Button } from '@/components/ui/button';
import DeleteButton from '@/components/app/DeleteButton.vue';

interface Props {
    node: AccountNode;
    level: number;
}

const props = defineProps<Props>();

const open = ref(true);
const isConfirmOpen = ref(false);
const isDeleteLoading = ref(false);

const emit = defineEmits(["edit", "delete", "add-child"]);

const toggle = () => {
    if (props.node.is_summary) open.value = !open.value;
};

const emitEdit = () => emit("edit", props.node);
const emitDelete = () => {
    emit('delete', props.node.id);
    isConfirmOpen.value = false;
};
const emitAddChild = () => emit("add-child", props.node);
</script>

<template>
    <li>
        <div
            class="group flex items-center justify-between py-1 px-2 rounded-lg hover:bg-gray-100 cursor-pointer select-none"
            :style="{ marginLeft: level * 16 + 'px' }"
            @click="toggle"
        >
            <div class="flex items-center gap-1">
                <!-- Icon (Group only) -->
                <span v-if="node.is_summary" class="text-gray-600">
                    <ChevronRight v-if="!open" class="w-4 h-4" />
                    <ChevronDown v-else class="w-4 h-4" />
                </span>

                <!-- Spacer for ledger -->
                <span v-else class="inline-block w-4 h-4"></span>

                <!-- Icon -->
                <Folder v-if="node.is_summary" class="w-4 h-4 text-blue-500" />
                <FileText v-else class="w-4 h-4 text-gray-400" />

                <!-- Text -->
                <span class="ml-1" :class="node.is_summary ? 'font-semibold text-gray-900' : 'text-gray-700'">
                    {{ node.code }} — {{ node.name }}
                </span>
            </div>

            <!-- Actions -->
            <div class="hidden group-hover:flex gap-2 pr-2">
                <Button size="icon" variant="ghost" class="h-6 w-6" @click.stop="emitEdit">
                    <Pencil class="w-3 h-3" />
                </Button>

                <Button
                    size="icon"
                    variant="ghost"
                    class="h-6 w-6 text-red-600"
                    @click.stop="() => isConfirmOpen = true"
                >
                    <Trash class="w-3 h-3" />
                </Button>

                <Button v-if="node.is_summary" size="icon" variant="ghost" class="h-6 w-6 text-green-600" @click.stop="emitAddChild">
                    <Plus class="w-3 h-3" />
                </Button>
            </div>
        </div>

        <!-- Children -->
        <Transition>
            <ul v-if="open && node.children?.length">
                <TreeNode
                    v-for="child in node.children"
                    :key="child.id"
                    :node="child"
                    :level="level + 1"
                    @delete="$emit('delete', $event)"
                />
            </ul>
        </Transition>
    </li>

    <!-- Confirmation Dialog -->
    <DeleteButton v-model="isConfirmOpen" :is-processing="isDeleteLoading" @on-destroy="emitDelete" />
</template>
