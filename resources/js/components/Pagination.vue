<script setup lang="ts">
import {
    PaginationEllipsis,
    PaginationFirst,
    PaginationLast,
    PaginationList,
    PaginationListItem,
    PaginationNext,
    PaginationPrev,
    PaginationRoot,
} from 'reka-ui';

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['page-change']);

const handlePageChange = (page: number) => {
    if (page < 1 || page > props.pagination.last_page) return;
    emit('page-change', page);
};
</script>

<template>
    <PaginationRoot :page="pagination.current_page" :total="pagination.total" :itemsPerPage="pagination.per_page" @update:page="handlePageChange">
        <PaginationList v-slot="{ items }" class="flex items-center gap-1">
            <!-- First Page Button -->
            <PaginationFirst
                class="flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background transition-colors hover:bg-accent disabled:opacity-50"
                :disabled="pagination.current_page === 1"
                @click="handlePageChange(1)"
            >
                &laquo;
            </PaginationFirst>

            <!-- Previous Button -->
            <PaginationPrev
                class="mr-1 flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background transition-colors hover:bg-accent disabled:opacity-50"
                :disabled="pagination.current_page === 1"
                @click="handlePageChange(pagination.current_page - 1)"
            >
                &lsaquo;
            </PaginationPrev>

            <!-- Page Numbers -->
            <template v-for="(page, index) in items" :key="index">
                <PaginationListItem
                    v-if="page.type === 'page'"
                    :value="page.value"
                    @click="handlePageChange(page.value)"
                    class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-md border border-border transition-colors outline-none select-none focus:ring-2 focus:ring-primary"
                    :class="{
                        'border-primary bg-primary text-primary-foreground': page.value === pagination.current_page,
                        'bg-background hover:bg-accent': page.value !== pagination.current_page,
                    }"
                >
                    {{ page.value }}
                </PaginationListItem>

                <PaginationEllipsis v-else class="flex h-9 w-9 items-center justify-center text-muted-foreground"> ... </PaginationEllipsis>
            </template>

            <!-- Next Button -->
            <PaginationNext
                class="ml-1 flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background transition-colors hover:bg-accent disabled:opacity-50"
                :disabled="pagination.current_page === pagination.last_page"
                @click="handlePageChange(pagination.current_page + 1)"
            >
                &rsaquo;
            </PaginationNext>

            <!-- Last Page Button -->
            <PaginationLast
                class="flex h-9 w-9 items-center justify-center rounded-md border border-border bg-background transition-colors hover:bg-accent disabled:opacity-50"
                :disabled="pagination.current_page === pagination.last_page"
                @click="handlePageChange(pagination.last_page)"
            >
                &raquo;
            </PaginationLast>
        </PaginationList>
    </PaginationRoot>
</template>
