import DataTableDropdown from '@/components/DataTable/users/DataTableDropdown.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Role, User } from '@/types';
import type { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const userColumns: ColumnDef<User>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                    class: 'min-w-[50px] max-w-[100px]',
                },
                () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4 cursor-pointer' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-center min-w-[50px] max-w-[100px] truncate' }, row.getValue('name')),
        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-expect-error
        isDefaultFilter: true,
    },
    {
        accessorKey: 'email',
        header: () => h('span', { class: 'block' }, 'Descrição'),
        cell: ({ row }) => h('span', { class: 'block' }, row.original.email),
    },
    {
        accessorKey: 'permissions',
        header: () => h('span', { class: 'block' }, 'Perfis Atribuidos'),
        cell: ({ row }) =>
            h(
                'div',
                {
                    class: 'flex flex-wrap gap-2 justify-start items-center',
                    style: { width: '700px' },
                },
                row.original.roles?.map((role: Role) =>
                    h(
                        Badge,
                        {
                            class: 'text-xs',
                            key: role.id,
                        },
                        () => role.name,
                    ),
                ),
            ),
    },
    {
        id: 'actions',
        header: '',
        enableHiding: false,
        cell: ({ row }) => h(DataTableDropdown, { user: row.original }),
    },
];
