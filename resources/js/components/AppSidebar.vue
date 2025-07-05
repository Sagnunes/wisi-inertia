<script setup lang="ts">
import NavDropdown from '@/components/NavDropdown.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, SquareTerminal } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';
import UserRole from '@/enums/UserRole';

const page = usePage();

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
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
        roles: [UserRole.ADMIN],
        items: [
            {
                title: 'Perfis',
                href: '/administracao/perfis',
            },
            {
                title: 'Permissões',
                href: '/administracao/permissoes',
            },
            {
                title: 'Utilizadores',
                href: '/administracao/utilizadores',
            },
        ],
    },
];

const userRoles = computed(() =>
    page.props.auth.user?.roles?.map((role) => role.name) ?? []
);

const filterNavItemsByUserRoles = (items: NavItem[], userRoles: string[]): NavItem[] => {
    return items.filter((item) => {
        if (!item.roles || item.roles.length === 0) {
            return true;
        }
        return item.roles.some((role) => userRoles.includes(role));
    });
};

const filteredNavManagementItems = computed(() =>
    filterNavItemsByUserRoles(navManagementItems, userRoles.value)
);
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>
        <SidebarFooter>
            <NavDropdown :nav-items="filteredNavManagementItems" v-if="filteredNavManagementItems.length > 0"/>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
