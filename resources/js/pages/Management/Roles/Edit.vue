<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Role } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, PropType } from 'vue';

const props = defineProps({
    role: {
        type: Object as PropType<Role>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perfis',
        href: '/administracao/perfis',
    },
    {
        title: `Editando o Perfil - ${props.role.name}`,
        href: '/administracao/perfis',
    },
];

const headingSmall = computed(() => {
    return `Editando o Perfil - ${props.role.name}`;
});

const role = { ...props.role };

const form = useForm({
    name: role.name,
    description: role.description,
});

const submit = () => {
    form.patch(route('roles.update', role), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <HeadingSmall :title="headingSmall" />
            <form @submit.prevent="submit" class="space-y-6">
                <div class="grid gap-2">
                    <Label for="name">Nome</Label>
                    <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Nome" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="description">Descrição</Label>
                    <Input id="description" type="text" class="mt-1 block w-full" v-model="form.description" required />
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing">Guardar</Button>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Guardado.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
