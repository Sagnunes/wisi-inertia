<!-- DataTableDropdown.vue -->
<script setup lang="ts">
import DeleteDialog from '@/components/DeleteDialog.vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { router } from '@inertiajs/vue3';
import { MoreHorizontal } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    status: {
        type: Object,
        required: true,
    },
});

const showDeleteDialog = ref(false);

function openDeleteDialog() {
    showDeleteDialog.value = true;
}

function closeDeleteDialog() {
    showDeleteDialog.value = false;
}

function copyId(id: string) {
    navigator.clipboard.writeText(id);
}

const handleEditUrl = () => {
    router.get(route('statuses.edit', props.status));
};
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="copyId(status.id)">Copiar ID</DropdownMenuItem>
            <DropdownMenuSeparator v-if="status.can.update || status.can.delete" />
            <DropdownMenuItem @click="handleEditUrl" v-if="status.can.update">Editar</DropdownMenuItem>
            <DropdownMenuItem @click="openDeleteDialog" v-if="status.can.delete">Apagar</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>

    <DeleteDialog
        v-if="showDeleteDialog"
        :resource="status"
        route-name="statuses.destroy"
        resource-name="estados"
        :current-page="1"
        identifier-key="id"
        @deleted="closeDeleteDialog"
        v-model:open="showDeleteDialog"
    />
</template>
