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
    displayKey: { type: String, default: 'name' },
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
                    <DialogTitle> Tem a certeza que deseja apagar o "{{ displayValue }}"? </DialogTitle>
                    <DialogDescription>
                        <slot name="description"> Uma vez que o {{ resourceName }} seja apagado já não é possivel recuperar. </slot>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-4">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal" aria-label="Cancel delete"> Cancelar </Button>
                    </DialogClose>
                    <Button variant="destructive" :disabled="form.processing" aria-label="Confirm delete">
                        <span v-if="form.processing" class="mr-2 animate-spin">⏳</span>
                        Apagar
                    </Button>
                </DialogFooter>
                <div v-if="form.hasErrors" class="mt-2 text-red-500">
                    <div v-for="(error, key) in form.errors" :key="key">{{ error }}</div>
                </div>
            </form>
        </DialogContent>
    </Dialog>
</template>
