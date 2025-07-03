<!-- DataTableDropdown.vue -->
<script setup lang="ts">
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuLabel,  DropdownMenuItem, DropdownMenuSeparator, DropdownMenuContent} from '@/components/ui/dropdown-menu';

defineProps<{ user: { id: string; name: string; email: string } }>()

function copyId(id: string) {
    navigator.clipboard.writeText(id)
}

import { MoreHorizontal } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="copyId(user.id)">Copy ID</DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem>Edit</DropdownMenuItem>
            <DropdownMenuItem>Delete</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
