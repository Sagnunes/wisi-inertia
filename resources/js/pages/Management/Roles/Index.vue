<script setup lang="ts">
import DataTable from '@/components/DataTable/DataTable.vue';
import { roleColumns } from '@/components/DataTable/roles/columns';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Paginator, Role } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    roles: {
        type: Object as PropType<Paginator<Role>>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const goToPage = (page: number) => {
    router.get(
        '/administracao/perfis',
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
            <HeadingSmall title="Perfis" description="Gerir os perfis do sistema" />
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
                <DataTable :columns="roleColumns" :data="roles.data" />
                <Pagination :pagination="roles" @page-change="goToPage" />
            </div>
        </div>
    </AppLayout>
</template>
