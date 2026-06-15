<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Globe, MailCheck, LifeBuoy, LayoutGrid, Users, FileSpreadsheet } from '@lucide/vue'; // Importamos FileSpreadsheet para las boletas
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
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
// Importamos la función de enrutamiento nativa de Wayfinder para el módulo de boletas
import { index as boletasIndexRoute } from '@/routes/boletas';
import type { NavItem } from '@/types';

interface AuthUser {
    id: number;
    name: string;
    email: string;
    roles: string[];
    permissions: string[];
}

const page = usePage();
const authUser = page.props.auth ? (page.props.auth as any).user as AuthUser : null;

// --- MENÚ DE NAVEGACIÓN PRINCIPAL ---
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

// 1. Acceso universal al Módulo de Boletas (Profesores ven lo suyo, Admin/RRHH gestionan)
if (authUser) {
    mainNavItems.push({
        title: 'Boletas de Pago',
        href: boletasIndexRoute().url, // Extraemos la URL tipada de Wayfinder
        icon: FileSpreadsheet,
    });
}

// 2. Acceso restrictivo a la Gestión de Usuarios (Solo Admin / RRHH)
if (authUser && (authUser.roles.includes('Administrador') || authUser.roles.includes('Recursos Humanos'))) {
    mainNavItems.push({
        title: 'Gestión de Usuarios',
        href: '/users', 
        icon: Users,
    });
}

// --- ENLACES INSTITUCIONALES DEL PIE DE PÁGINA ---
const footerNavItems: NavItem[] = [
    {
        title: 'Web Oficial',
        href: 'https://andino.edu.pe',
        icon: Globe,
    },
    {
        title: 'WEBMAIL',
        href: 'https://login.microsoft.com',
        icon: MailCheck,
    },
    {
        title: 'Soporte',
        href: '/soporte',
        icon: LifeBuoy,
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>