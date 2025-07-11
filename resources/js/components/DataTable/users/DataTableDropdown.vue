<!-- DataTableDropdown.vue -->
<script setup lang="ts">
import ActivateBlockUserDialog from '@/components/ActivateBlockUserDialog.vue';
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
    user: {
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

const handleAssignRolesUrl = () => {
    router.get(route('user.roles.edit', props.user));
};

const showValidateDialog = ref(false);

function openValidateDialog() {
    showValidateDialog.value = true;
}
function closeValidateDialog() {
    showValidateDialog.value = false;
}
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
            <DropdownMenuItem @click="copyId(user.id)">Copiar ID</DropdownMenuItem>
            <DropdownMenuItem @click="openValidateDialog" v-if="user.can.validate">Validar</DropdownMenuItem>
            <template v-if="user.can.delete">
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="openDeleteDialog">Apagar</DropdownMenuItem>
            </template>
            <template v-if="user.can.assign">
                <DropdownMenuSeparator />
                <DropdownMenuItem @click="handleAssignRolesUrl">Atribuir perfil</DropdownMenuItem>
            </template>
        </DropdownMenuContent>
    </DropdownMenu>

    <DeleteDialog
        v-if="showDeleteDialog"
        :resource="user"
        route-name="users.destroy"
        resource-name="user"
        :current-page="1"
        identifier-key="id"
        @deleted="closeDeleteDialog"
        v-model:open="showDeleteDialog"
    />

    <ActivateBlockUserDialog :open="showValidateDialog" :user="user" @update:open="showValidateDialog = $event" @close="closeValidateDialog" />
</template>
