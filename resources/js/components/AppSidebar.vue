<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Globe, MailCheck, LifeBuoy, LayoutGrid, Users, FileSpreadsheet, UserSquare2 } from '@lucide/vue'; // Importamos UserSquare2 para "Mis Boletas"
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
    // 1. CONTROL DE VISIBILIDAD GESTIÓN GENERAL: Solo visible para Admin y RRHH (/boletas)
    if (isAdminOrRRHH) {
        mainNavItems.push({
            title: 'Boletas de Pago',
            href: boletasIndexRoute().url, // Historial general del plantel
            icon: FileSpreadsheet,
        });
    }

    // 2. ACCESO UNIVERSAL: "Mis Boletas" privado visible para TODOS los roles de la institución (/mis-boletas)
    mainNavItems.push({
        title: 'Mis Boletas',
        href: '/mis-boletas', // Ruta privada de firma
        icon: UserSquare2,
    });

    // 3. GESTIÓN DE USUARIOS: Restrictivo solo para Admin y RRHH (/users)
    if (isAdminOrRRHH) {
        mainNavItems.push({
            title: 'Mis Empleados',
            href: '/users',
            icon: Users,
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