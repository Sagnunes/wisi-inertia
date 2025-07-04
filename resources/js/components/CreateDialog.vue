<script setup lang="ts">
import InputError from '@/components/InputError.vue';
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';

const props = defineProps({
    resourceName: {
        type: String,
        required: true,
    },
    routeName: {
        type: String,
        required: true,
    },
    fields: {
        type: Array as PropType<
            Array<{
                name: string;
                label?: string;
                placeholder?: string;
                type?: string;
                required?: boolean;
            }>
        >,
        required: true,
    },
    initialFormData: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['created']);

const showCreateDialog = ref(false);

const initialData = props.fields.reduce(
    (acc, field) => {
        acc[field.name] = '';
        return acc;
    },
    {} as Record<string, string>,
);

const form = useForm({ ...initialData });

const closeModal = () => {
    form.clearErrors();
    form.reset();
    showCreateDialog.value = false;
};

const submit = () => {
    form.post(route(props.routeName), {
        onSuccess: () => {
            closeModal();
            emit('created');
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Dialog :open="showCreateDialog" @update:open="showCreateDialog = $event">
        <DialogTrigger class="space-y-3">
            <slot name="trigger">
                <Button variant="link" class="hover:cursor-pointer"> New {{ resourceName }}</Button>
            </slot>
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="submit" class="space-y-6">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Create a new {{ resourceName }}</DialogTitle>
                    <DialogDescription>Fill the required inputs</DialogDescription>
                </DialogHeader>

                <div v-for="field in fields" :key="field.name" class="grid gap-2">
                    <Label :for="field.name" class="sr-only">
                        {{ field.label || field.name }}
                    </Label>
                    <Input
                        :id="field.name"
                        :type="field.type || 'text'"
                        :name="field.name"
                        v-model="form[field.name]"
                        :placeholder="field.placeholder || field.label || field.name"
                        :required="field.required"
                    />

                    <InputError :message="form.errors[field.name]" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" variant="default" :disabled="form.processing"> Create</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
