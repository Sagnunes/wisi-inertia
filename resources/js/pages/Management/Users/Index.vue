<script setup lang="ts">
import DataTable from '@/components/DataTable/DataTable.vue';
import { userColumns } from '@/components/DataTable/users/columns';
import HeadingSmall from '@/components/HeadingSmall.vue';
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, Paginator, User } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { PropType } from 'vue';

defineProps({
    users: {
        type: Object as PropType<Paginator<User>>,
        required: true,
    },
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Utilizadores',
        href: '/administracao/utilizadores',
    },
];

const goToPage = (page: number) => {
    router.get(
        route('users.index'),
        { page },
        {
            preserveScroll: true,
            preserveState: true,
            replace: true,
            only: ['users'],
        },
    );
};
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col space-y-6 px-4 py-6">
            <div class="flex flex-row items-end justify-between gap-4">
                <HeadingSmall title="Utilizadores" description="Gerir os utilizadores do sistema" />
            </div>
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl">
                <DataTable :columns="userColumns" :data="users.data" />
                <Pagination :pagination="users" @page-change="goToPage" />
            </div>
        </div>
    </AppLayout>
</template>
