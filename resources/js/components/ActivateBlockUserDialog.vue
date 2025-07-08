<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog';
import { computed, defineEmits, defineProps, PropType } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { User } from '@/types';
import Status from '@/enums/Status';

const props = defineProps({
    open: Boolean,
    user: {
        type: Object as PropType<User>,
        required: true,
    }
});
const emit = defineEmits(['update:open', 'close']);

const form = useForm({
    user: props.user?.id,
    status: 0
});
const modelValue = computed({
    get: () => props.open,
    set: (value) => emit('update:open', value)
});

function handleClose() {
    emit('close');
    emit('update:open', false);
}

function handleConfirm(status: number) {
    form.status = status;
    form.post(route('users.validate', props.user), {
        onSuccess: () => {
            form.reset();
        }
    });
    emit('update:open', false);
}
</script>

<template>
    <Dialog v-model:open="modelValue">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ user.status.id === Status.PENDING ? 'Validar o Utilizador?' : user.status.id === Status.ACTIVE ? 'Desativar o Utilizador' : 'Validar o Utilizador'}}
                </DialogTitle>
                <DialogDescription> Tem certeza que deseja {{ user.status.id === Status.PENDING ? 'validar' : user.status.id === Status.ACTIVE ? 'desativar' : 'validar'}} este
                    utilizador?
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="secondary" @click="handleClose">Cancelar</Button>
                <Button @click="handleConfirm(user.status.id === Status.PENDING ? Status.ACTIVE : user.status.id === Status.ACTIVE ? Status.BLOCKED : Status.ACTIVE)"
                        :variant="user.status.id === Status.PENDING ? 'default' : user.status.id === Status.ACTIVE ? 'destructive' : 'default'">
                    {{ user.status.id === Status.PENDING ? 'Validar' : user.status.id === Status.ACTIVE ? 'Desativar' : 'Validar'}}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
