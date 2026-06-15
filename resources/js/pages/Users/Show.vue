<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

interface Role { id: number; name: string; }
interface User {
    id: number;
    name: string;
    dni: string;
    phone: string | null;
    email: string;
    status: 'Activo' | 'Inactivo';
    created_at: string;
    roles: Role[];
}

defineProps<{ user: User }>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Gestión de Usuarios', href: '/users' },
            { title: 'Ficha de Personal', href: '#' },
        ],
    },
});
</script>

<template>
    <Head :title="`Perfil - ${user.name}`" />

    <div class="flex h-full flex-1 flex-col gap-4 p-6 max-w-2xl mx-auto w-full">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">Ficha Informativa de Personal</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400">Datos históricos y estado operativo del trabajador dentro del Colegio Andino.</p>
        </div>

        <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 dark:border-sidebar-border dark:bg-neutral-900 space-y-4">
            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">Nombre Completo</span>
                <span class="text-sm font-semibold text-neutral-900 dark:text-neutral-100 col-span-2">{{ user.name }}</span>
            </div>

            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">DNI (RENIEC)</span>
                <span class="text-sm font-mono text-neutral-900 dark:text-neutral-100 col-span-2">{{ user.dni }}</span>
            </div>

            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">Celular</span>
                <span class="text-sm font-mono text-neutral-900 dark:text-neutral-100 col-span-2">{{ user.phone || 'No registrado' }}</span>
            </div>

            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">Correo Electrónico</span>
                <span class="text-sm text-neutral-900 dark:text-neutral-100 col-span-2">{{ user.email }}</span>
            </div>

            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">Rol de Acceso</span>
                <div class="col-span-2">
                    <span v-for="role in user.roles" :key="role.id" class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400">
                        {{ role.name }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 py-2 border-b border-neutral-100 dark:border-neutral-800">
                <span class="text-xs font-semibold text-neutral-400 uppercase">Estado actual</span>
                <div class="col-span-2">
                    <span :class="['inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset', user.status === 'Activo' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-neutral-50 text-neutral-600 ring-neutral-500/10']">
                        {{ user.status }}
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <Link href="/users" class="px-4 py-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 dark:text-neutral-300">Regresar al Listado</Link>
                <Link :href="`/users/${user.id}/edit`" class="rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900">
                    Modificar Información
                </Link>
            </div>
        </div>
    </div>
</template>