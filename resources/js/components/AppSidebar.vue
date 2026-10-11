<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Globe, MailCheck, LifeBuoy, LayoutGrid, Users, FileSpreadsheet, Settings, Briefcase, UserSquare2 } from '@lucide/vue'; // Importamos UserSquare2 para "Mis Boletas"
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

// Determine si el usuario tiene privilegios de gestión
const isAdminOrRRHH = authUser && (authUser.roles.includes('Administrador') || authUser.roles.includes('Recursos Humanos'));

// --- MENÚ DE NAVEGACIÓN PRINCIPAL ---
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
];

if (authUser) {
    // 1. GRUPO: BOLETAS DE PAGO (Condicional o Desplegable)
    if (isAdminOrRRHH) {
        mainNavItems.push({
            title: 'Boletas de Pago',
            icon: FileSpreadsheet,
            items: [
                { title: 'Dashboard Boletas', href:  '/boletas/empleados'},
                { title: 'Boletas Empleados', href: boletasIndexRoute().url }, // Ruta de ejemplo
                { title: 'Mis Boletas',       href: '/mis-boletas' }
            ]
        });
    } else {
        // Si es un empleado común, solo ve su enlace plano sin desplegables
        mainNavItems.push({
            title: 'Mis Boletas',
            href: '/mis-boletas',
            icon: UserSquare2,
        });
    }

    // 2. GRUPO: PLANILLA (Solo para Admin y RRHH)
    if (isAdminOrRRHH) {
        mainNavItems.push({
            title: 'Planilla',
            icon: Briefcase,
            items: [
                { title: 'Mis Empleados', href: '/users' },
                { title: 'Asistencias',  href: '/asistencias' }
            ]
        });
    }

    // 3. GRUPO: ADMINISTRACIÓN (Solo para Admin y RRHH)
    if (isAdminOrRRHH) {
        mainNavItems.push({
            title: 'Administración',
            icon: Settings,
            items: [
                { title: 'Requerimientos', href: '/requerimientos' },
                { title: 'Configuraciones', href: '/configuracion' },
                { title: 'Boletines', href: '/boletinesadm' }
            ]
        });
    }
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