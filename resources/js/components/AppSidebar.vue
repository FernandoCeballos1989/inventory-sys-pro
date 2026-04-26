<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    Folder,
    LayoutGrid,
    UserRound,
    Truck,
    Package,
    Warehouse,
} from 'lucide-vue-next';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import categories from '@/routes/categories';
import clients from '@/routes/clients';
import products from '@/routes/products';
import providers from '@/routes/providers';
import stocks from '@/routes/stocks';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Categories',
        href: categories.index().url,
        icon: Folder,
    },
    {
        title: 'Clients',
        href: clients.index().url,
        icon: UserRound,
    },
    {
        title: 'Providers',
        href: providers.index().url,
        icon: Truck,
    },
    {
        title: 'Products',
        href: products.index().url,
        icon: Package,
    },
    {
        title: 'Stocks',
        href: stocks.index().url,
        icon: Warehouse,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
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
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
