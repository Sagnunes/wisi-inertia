<script setup lang="ts">
import CreateDialog from '@/components/CreateDialog.vue';
import DataTable from '@/components/DataTable/DataTable.vue';
import { statusColumns } from '@/components/DataTable/statuses/columns';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Paginator, Status } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    statuses: {
        type: Object as PropType<Paginator<Status>>,
        required: true,
    },
    can: {
        type: Object as PropType<any>,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Permissões',
        href: '/administracao/permissoes',
    },
];

const fields = [
    { name: 'name', placeholder: 'Name', required: true },
    { name: 'description', placeholder: 'Description' },
];

const goToPage = (page: number) => {
    router.get(
        route('statuses.index'),
        { page },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['statuses'],
        },
    );
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Estados" description="Gerir as permissões do sistema" />
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
                <DataTable :columns="statusColumns" :data="statuses.data">
                    <template #create v-if="can.create">
                        <CreateDialog
                            resource-name="statuses"
                            route-name="statuses.store"
                            :fields="fields"
                            :initial-form-data="{ name: '', description: '' }"
                        >
                            <template #trigger>
                                <Button variant="default" class="hover:cursor-pointer"> Novo Estado</Button>
                            </template>
                        </CreateDialog>
                    </template>
                </DataTable>
                <Pagination :pagination="statuses" @page-change="goToPage" />
            </div>
        </div>
    </AppLayout>
</template>
