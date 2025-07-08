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
    role: {
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
    router.get(route('roles.edit', props.role));
};

const handleAssignPermissionsUrl = () => {
    router.get(route('roles.permissions.edit', props.role));
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
            <DropdownMenuLabel>Ações</DropdownMenuLabel>
            <DropdownMenuItem @click="copyId(role.id)">Copiar ID</DropdownMenuItem>
            <DropdownMenuSeparator v-if="role.can.update || role.can.delete" />

            <DropdownMenuItem @click="handleEditUrl" v-if="role.can.update">Editar</DropdownMenuItem>
            <DropdownMenuItem @click="openDeleteDialog" v-if="role.can.delete">Apagar</DropdownMenuItem>
            <template v-if="role.can.assign">
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="handleAssignPermissionsUrl">Atribuir permissões</DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>

    <DeleteDialog
        v-if="showDeleteDialog"
        :resource="role"
        route-name="roles.destroy"
        resource-name="role"
        :current-page="1"
        identifier-key="id"
        @deleted="closeDeleteDialog"
        v-model:open="showDeleteDialog"
    />
</template>
