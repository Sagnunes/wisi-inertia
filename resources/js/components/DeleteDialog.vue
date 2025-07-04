<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { PropType, computed } from 'vue';

const props = defineProps({
    resource: { type: Object as PropType<Record<string, any>>, required: true },
    routeName: { type: String, required: true },
    resourceName: { type: String, required: true },
    currentPage: { type: Number, default: 1 },
    identifierKey: { type: String, default: 'id' },
    displayKey: { type: String, default: 'name' }, // NEW: allow configuring display field
});

const emit = defineEmits(['deleted']);

const form = useForm({});

const closeModal = () => {
    form.clearErrors();
    form.reset();
};

const deleteResource = () => {
    const params = {
        [props.resourceName]: props.resource[props.identifierKey],
        page: props.currentPage,
    };

    form.delete(route(props.routeName, params), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            emit('deleted');
            closeModal();
        },
    });
};

// Computed for display
const displayValue = computed(() => props.resource[props.displayKey] || props.resource.title || '');
</script>

<template>
    <Dialog>
        <DialogTrigger>
            <slot name="trigger">
                <Button variant="link" class="hover:cursor-pointer">Delete</Button>
            </slot>
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="deleteResource">
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        Are you sure you want to delete "{{ displayValue }}"?
                    </DialogTitle>
                    <DialogDescription>
                        <slot name="description">
                            Once the {{ resourceName }} is deleted, all of its resources and data will
                            also be permanently deleted.
                        </slot>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal" aria-label="Cancel delete">
                            Cancel
                        </Button>
                    </DialogClose>
                    <Button variant="destructive" :disabled="form.processing" aria-label="Confirm delete">
                        <span v-if="form.processing" class="animate-spin mr-2">⏳</span>
                        Delete
                    </Button>
                </DialogFooter>
                <div v-if="form.hasErrors" class="text-red-500 mt-2">
                    <div v-for="(error, key) in form.errors" :key="key">{{ error }}</div>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
