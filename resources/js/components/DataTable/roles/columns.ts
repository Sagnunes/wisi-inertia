import DataTableDropdown from '@/components/DataTable/roles/DataTableDropdown.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Role } from '@/types';
import type { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h } from 'vue';

export const roleColumns: ColumnDef<Role>[] = [
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
                () => ['Nome', h(ArrowUpDown, { class: 'ml-2 h-4 w-4 cursor-pointer' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'text-center min-w-[50px] max-w-[100px] truncate' }, row.getValue('name')),
        // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        // @ts-expect-error
        isDefaultFilter: true,
    },
    {
        accessorKey: 'description',
        header: () => h('span', { class: 'block' }, 'Descrição'),
        cell: ({ row }) => h('span', { class: 'block' }, row.original.description),
    },
    {
        accessorKey: 'permissions',
        header: () => h('span', { class: 'block' }, 'Permissões'),
        cell: ({ row }) =>
            h(
                'div',
                {
                    class: 'flex flex-wrap gap-2 justify-start items-center',
                    style: { width: '500px' },
                },
                row.original.permissions?.map((permission) =>
                    h(
                        Badge,
                        {
                            class: 'text-xs',
                            key: permission.id,
                        },
                        () => permission.name,
                    ),
                ),
            ),
    },
    {
        id: 'actions',
        header: '',
        enableHiding: false,
        cell: ({ row }) => h(DataTableDropdown, { role: row.original }),
    },
];
