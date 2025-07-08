<script setup lang="ts">
import NavDropdown from '@/components/NavDropdown.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import Permission from '@/enums/Permission';
import Role from '@/enums/Role';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, GalleryVertical, LayoutGrid, SquareTerminal } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Coleção Digital',
        href: '/',
        icon: GalleryVertical,
        permissions: [Permission.VIEW_DIGITAL_COLLECTION],
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];

const navManagementItems: NavItem[] = [
    {
        title: 'Administração',
        href: '#',
        icon: SquareTerminal,
        isActive: false,
        permissions: [Permission.VIEW_ROLES, Permission.VIEW_PERMISSIONS, Permission.VIEW_USERS],
        items: [
            {
                title: 'Perfis',
                href: '/administracao/perfis',
                permissions: [Permission.VIEW_ROLES],
            },
            {
                title: 'Permissões',
                href: '/administracao/permissoes',
                permissions: [Permission.VIEW_PERMISSIONS],
            },
            {
                title: 'Utilizadores',
                href: '/administracao/utilizadores',
                permissions: [Permission.VIEW_USERS],
            },
            {
                title: 'Status',
                href: '/administracao/estados',
                permissions: [Permission.VIEW_STATUS],
            },
        ],
    },
];

const isWatcher = computed(() => {
    return (page.props.auth.user?.roles ?? []).some((role: any) => role.name === Role.WATCHER);
});

const userPermissions = computed(() => {
    const roles = page.props.auth.user?.roles ?? [];
    const allPermissions = roles.flatMap((role: any) => role.permissions ?? []);
    return [...new Set(allPermissions.map((perm: any) => perm.slug))];
});

const filterNavItemsByPermissions = (items: NavItem[], userPermissions: string[], isSuperAdmin: boolean): NavItem[] => {
    return items
        .filter((item) => {
            if (isSuperAdmin) return true; // Watcher sees all
            if (!item.permissions || item.permissions.length === 0) {
                return true;
            }
            return item.permissions.some((permission) => userPermissions.includes(permission));
        })
        .map((item) => ({
            ...item,
            items: item.items ? filterNavItemsByPermissions(item.items, userPermissions, isSuperAdmin) : undefined,
        }));
};

const filteredMainNavItems = computed(() => filterNavItemsByPermissions(mainNavItems, userPermissions.value, isWatcher.value));

const filteredNavManagementItems = computed(() => filterNavItemsByPermissions(navManagementItems, userPermissions.value, isWatcher.value));
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="filteredMainNavItems" />
        </SidebarContent>
        <SidebarFooter>
            <NavDropdown :nav-items="filteredNavManagementItems" v-if="filteredNavManagementItems.length > 0" />
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
