<script setup lang="ts">
import CreateDialog from '@/components/CreateDialog.vue';
import DataTable from '@/components/DataTable/DataTable.vue';
import { roleColumns } from '@/components/DataTable/roles/columns';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Paginator, Role } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    roles: {
        type: Object as PropType<Paginator<Role>>,
        required: true,
    },
    can: {
        type: Object as PropType<any>,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Perfis',
        href: '/administracao/perfis',
    },
];

const fields = [
    { name: 'name', placeholder: 'Nome', required: true },
    { name: 'description', placeholder: 'Descrição' },
];

const goToPage = (page: number) => {
    router.get(
        route('roles.index'),
        { page },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['roles'],
        },
    );
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Perfis" description="Gerir os perfis do sistema" />
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
                <DataTable :columns="roleColumns" :data="roles.data">
                    <template #create v-if="can.create">
                        <CreateDialog
                            resource-name="perfil"
                            route-name="roles.store"
                            :fields="fields"
                            :initial-form-data="{ name: '', description: '' }"
                        >
                            <template #trigger>
                                <Button variant="default" class="hover:cursor-pointer"> Novo Perfil</Button>
                            </template>
                        </CreateDialog>
                    </template>
                </DataTable>
                <Pagination :pagination="roles" @page-change="goToPage" />
            </div>
        </div>
    </AppLayout>
</template>
