<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, FileText, Tag, Briefcase, Users, MessageSquare, Image } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Articles',
        href: '/dashboard/articles',
        icon: FileText,
    },
    {
        title: 'Categories',
        href: '/dashboard/categories',
        icon: Tag,
    },
    {
        title: 'Portfolio',
        href: '/dashboard/portfolios',
        icon: Briefcase,
    },
    {
        title: 'Media',
        href: '/dashboard/media',
        icon: Image,
    },
    {
        title: 'Contact Inquiries',
        href: '/dashboard/contact-inquiries',
        icon: MessageSquare,
    },
    {
        title: 'Users',
        href: '/dashboard/users',
        icon: Users,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Monitoring',
        href: 'https://stats.uptimerobot.com/5ChPDt2T2d',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://docs.google.com/document/d/1G9rNuDBkzUN7pzGDQbo51YQw4J-Uq9RGTWwymYm_mxU/edit?usp=sharing',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard.index')">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
