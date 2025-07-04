<script setup lang="ts">
import CreateDialog from '@/components/CreateDialog.vue';
import DataTable from '@/components/DataTable/DataTable.vue';
import { permissionColumns } from '@/components/DataTable/permissions/columns';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Paginator, Permission } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    permissions: {
        type: Object as PropType<Paginator<Permission>>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissões',
        href: '/administracao/permissoes',
    },
];

const fields = [{ name: 'name', placeholder: 'Name', required: true }];

const goToPage = (page: number) => {
    router.get(
        route('permissions.index'),
        { page },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['permissions'],
        },
    );
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Permissões" description="Gerir as permissões do sistema" />
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
                <DataTable :columns="permissionColumns" :data="permissions.data">
                    <template #create>
                        <CreateDialog
                            resource-name="permission"
                            route-name="permissions.store"
                            :fields="fields"
                            :initial-form-data="{ name: '', description: '' }"
                        >
                            <template #trigger>
                                <Button variant="default" class="hover:cursor-pointer"> Nova Permissão</Button>
                            </template>
                        </CreateDialog>
                    </template>
                </DataTable>
                <Pagination :pagination="permissions" @page-change="goToPage" />
            </div>
        </div>
    </AppLayout>
</template>
