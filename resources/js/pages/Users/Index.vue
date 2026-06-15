<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';

interface Role {
    id: number;
    name: string;
    guard_name: string;
}

interface User {
    id: number;
    name: string;
    first_name: string;
    paternal_surname: string;
    maternal_surname: string;
    dni: string;
    phone: string | null;
    email: string;
    status: 'Activo' | 'Inactivo';
    created_at: string;
    deleted_at: string | null;
    roles: Role[];
}

interface PaginatedUsers {
    data: User[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    total: number;
}

defineProps<{
    users: PaginatedUsers;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Gestión de Usuarios', href: '/users' },
        ],
    },
});

const deleteUser = (id: number) => {
    if (confirm('¿Está seguro de dar de baja a este trabajador? El registro se conservará de forma interna.')) {
        router.delete(`/users/${id}`);
    }
};

const restoreUser = (id: number) => {
    if (confirm('¿Desea reincorporar a este trabajador y restaurar todos sus accesos e historial de boletas?')) {
        router.post(`/users/${id}/restore`);
    }
};

// Helpers visuales para Roles
const getRoleBadgeClass = (roleName: string) => {
    switch (roleName) {
        case 'Administrador':
            return 'bg-red-50 text-red-700 ring-red-700/10 dark:bg-red-400/10 dark:text-red-400';
        case 'Recursos Humanos':
            return 'bg-amber-50 text-amber-700 ring-amber-700/10 dark:bg-amber-400/10 dark:text-amber-400';
        default:
            return 'bg-blue-50 text-blue-700 ring-blue-700/10 dark:bg-blue-400/10 dark:text-blue-400';
    }
};

// Formateador de fecha simple para el sistema local
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>

    <Head title="Personal del Plantel" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">Personal
                    Institucional</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400">Panel operativo para el control de
                    asistencias, emisión de boletas y contratos.</p>
            </div>
            <Link href="/users/create"
                class="inline-flex items-center justify-center rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow transition-colors hover:bg-neutral-800 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-50/90">
                Registrar Personal
            </Link>
        </div>

        <div
            class="relative flex-1 rounded-xl border border-sidebar-border/70 bg-white p-4 dark:border-sidebar-border dark:bg-neutral-900 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead
                        class="text-xs uppercase bg-neutral-50 dark:bg-neutral-800/50 text-neutral-700 dark:text-neutral-300 border-b border-sidebar-border/70">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">#</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Empleado</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Documento (DNI)</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Rol</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Último Ingreso</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">Estado</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        <tr v-for="(user, index) in users.data" :key="user.id" :class="[
                            'transition-colors',
                            user.deleted_at
                                ? 'bg-neutral-100/50 dark:bg-neutral-800/20 opacity-75' // Si está de baja, se ve ligeramente opaco
                                : 'hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30'
                        ]">
                            <td class="px-4 py-4 text-center font-mono text-neutral-400 text-xs">
                                {{ (users.current_page - 1) * 20 + index + 1 }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ user.name
                                        }}</span>
                                    <span class="text-xs text-neutral-400">{{ user.email }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap font-mono text-neutral-700 dark:text-neutral-300">
                                {{ user.dni }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-for="role in user.roles" :key="role.id"
                                    :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset', getRoleBadgeClass(role.name)]">
                                    {{ role.name }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-xs text-neutral-600 dark:text-neutral-400">
                                {{ formatDate(user.created_at) }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span v-if="user.deleted_at"
                                    class="inline-flex items-center rounded-md bg-red-100 text-red-700 ring-1 ring-inset ring-red-600/20 px-2 py-0.5 text-xs font-medium">
                                    Baja Laboral
                                </span>
                                <span v-else
                                    :class="['inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium ring-1 ring-inset', user.status === 'Activo' ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400' : 'bg-neutral-50 text-neutral-600 ring-neutral-500/10']">
                                    {{ user.status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                <Link :href="`/users/${user.id}`"
                                    class="text-neutral-600 hover:text-neutral-900 dark:text-neutral-400">Ver</Link>

                                <template v-if="user.deleted_at">
                                    <button @click="restoreUser(user.id)"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Dar
                                        de Alta</button>
                                </template>
                                <template v-else>
                                    <Link :href="`/users/${user.id}/edit`"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400">Editar</Link>
                                    <button @click="deleteUser(user.id)"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400">Baja</button>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="users.last_page > 1"
                class="flex items-center justify-between border-t border-neutral-200 dark:border-neutral-800 px-4 py-3 sm:px-6 mt-4">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link :href="users.links[0].url || '#'"
                        :class="['relative inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800', !users.links[0].url && 'opacity-50 pointer-events-none']">
                        Anterior</Link>
                    <Link :href="users.links[users.links.length - 1].url || '#'"
                        :class="['relative ml-3 inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800', !users.links[users.links.length - 1].url && 'opacity-50 pointer-events-none']">
                        Siguiente</Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-neutral-700 dark:text-neutral-400">
                            Mostrando <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{
                                users.current_page
                                }}</span> de <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{
                                users.last_page
                                }}</span> (<span class="text-xs text-neutral-500">{{ users.total }} empleados en
                                total</span>)
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <Link v-for="(link, key) in users.links" :key="key" :href="link.url || '#'" :class="[
                                'relative inline-flex items-center px-3 py-2 text-sm font-semibold focus:z-20',
                                link.active
                                    ? 'z-10 bg-neutral-900 text-white dark:bg-neutral-50 dark:text-neutral-900'
                                    : 'text-neutral-900 ring-1 ring-inset ring-neutral-300 hover:bg-neutral-50 dark:text-neutral-300 dark:ring-neutral-700 dark:hover:bg-neutral-800/50',
                                !link.url && 'opacity-40 pointer-events-none',
                                key === 0 && 'rounded-l-md',
                                key === users.links.length - 1 && 'rounded-r-md'
                            ]" v-html="link.label" />
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>