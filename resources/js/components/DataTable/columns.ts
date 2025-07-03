// columns.ts
import type { ColumnDef } from '@tanstack/vue-table'
import { h } from 'vue'
import DataTableDropdown from './DataTableDropdown.vue'

export type User = {
    id: string
    name: string
    email: string
}

export const userColumns: ColumnDef<User>[] = [
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => row.original.name,
    },
    {
        accessorKey: 'email',
        header: 'Email',
        cell: ({ row }) => row.original.email,
    },
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        cell: ({ row }) => h(DataTableDropdown, { user: row.original }),
    },
]
