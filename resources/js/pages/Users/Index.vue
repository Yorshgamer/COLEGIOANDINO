<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

const props = defineProps<{
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

// --- Filtros rápidos (operan sobre los registros ya cargados en esta página) ---
const search = ref('');
const roleFilter = ref('all');
const statusFilter = ref('all'); // all | Activo | Inactivo | Baja

const availableRoles = computed(() => {
    const set = new Set<string>();
    props.users.data.forEach((u) => u.roles.forEach((r) => set.add(r.name)));
    return Array.from(set).sort();
});

const filteredUsers = computed(() => {
    const term = search.value.trim().toLowerCase();
    return props.users.data.filter((u) => {
        const matchesSearch = !term || u.name.toLowerCase().includes(term) || u.email.toLowerCase().includes(term) || u.dni.includes(term);
        const matchesRole = roleFilter.value === 'all' || u.roles.some((r) => r.name === roleFilter.value);
        const isBaja = !!u.deleted_at;
        const matchesStatus =
            statusFilter.value === 'all' || (statusFilter.value === 'Baja' && isBaja) || (!isBaja && u.status === statusFilter.value);
        return matchesSearch && matchesRole && matchesStatus;
    });
});

const hasActiveFilters = computed(() => search.value.trim() !== '' || roleFilter.value !== 'all' || statusFilter.value !== 'all');

const resetFilters = () => {
    search.value = '';
    roleFilter.value = 'all';
    statusFilter.value = 'all';
};

// --- Helpers visuales ---
const roleStyles: Record<string, { dot: string; text: string }> = {
    Administrador: { dot: 'bg-red-500 dark:bg-red-400', text: 'text-red-700 dark:text-red-400' },
    'Recursos Humanos': { dot: 'bg-amber-500 dark:bg-amber-400', text: 'text-amber-700 dark:text-amber-400' },
};
const defaultRoleStyle = { dot: 'bg-sky-500 dark:bg-sky-400', text: 'text-sky-700 dark:text-sky-400' };
const getRoleStyle = (name: string) => roleStyles[name] ?? defaultRoleStyle;

// Franja de color a la izquierda de cada fila: permite ubicar de un vistazo
// al personal con roles de mayor responsabilidad dentro de listados largos.
const getRowAccent = (user: User) => {
    if (user.deleted_at) return 'border-l-neutral-300 dark:border-l-neutral-700';
    if (user.roles.some((r) => r.name === 'Administrador')) return 'border-l-red-400 dark:border-l-red-500';
    if (user.roles.some((r) => r.name === 'Recursos Humanos')) return 'border-l-amber-400 dark:border-l-amber-500';
    return 'border-l-sky-300 dark:border-l-sky-700';
};

const avatarPalette = [
    'bg-indigo-50 text-indigo-700 dark:bg-indigo-400/10 dark:text-indigo-400',
    'bg-teal-50 text-teal-700 dark:bg-teal-400/10 dark:text-teal-400',
    'bg-amber-50 text-amber-700 dark:bg-amber-400/10 dark:text-amber-400',
    'bg-rose-50 text-rose-700 dark:bg-rose-400/10 dark:text-rose-400',
    'bg-violet-50 text-violet-700 dark:bg-violet-400/10 dark:text-violet-400',
    'bg-sky-50 text-sky-700 dark:bg-sky-400/10 dark:text-sky-400',
];

const getAvatarClass = (user: User) => {
    let hash = 0;
    for (let i = 0; i < user.name.length; i++) hash += user.name.charCodeAt(i);
    return avatarPalette[hash % avatarPalette.length];
};

const getInitials = (user: User) => {
    const a = user.first_name?.[0] ?? user.name?.[0] ?? '';
    const b = user.paternal_surname?.[0] ?? '';
    return (a + b).toUpperCase() || '?';
};

// Formateador de fecha simple para el sistema local
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>

    <Head title="Personal del Plantel" />

    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight text-neutral-900 dark:text-neutral-100">
                        Personal Institucional
                    </h1>
                    <span
                        class="inline-flex items-center rounded-full bg-neutral-100 px-2.5 py-0.5 text-xs font-medium text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400">
                        {{ users.total }} registrados
                    </span>
                </div>
                <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                    Panel operativo para el control de asistencias, emisión de boletas y contratos.
                </p>
            </div>
            <Link href="/users/create"
                class="inline-flex items-center justify-center gap-1.5 rounded-md bg-neutral-900 px-4 py-2 text-sm font-medium text-neutral-50 shadow-sm transition-colors hover:bg-neutral-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-neutral-900 dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200 dark:focus-visible:outline-neutral-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Registrar Personal
            </Link>
        </div>

        <div
            class="relative flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 bg-white dark:border-sidebar-border dark:bg-neutral-900">
            <!-- Barra de filtros -->
            <div
                class="flex flex-col gap-3 border-b border-neutral-200 p-4 sm:flex-row sm:items-center sm:justify-between dark:border-neutral-800">
                <div class="relative w-full sm:max-w-xs">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input v-model="search" type="text" placeholder="Buscar por nombre, correo o DNI"
                        class="w-full rounded-md border border-neutral-300 bg-white py-2 pl-9 pr-3 text-sm text-neutral-900 placeholder:text-neutral-400 focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/5 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-100 dark:placeholder:text-neutral-500" />
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select v-model="roleFilter"
                        class="rounded-md border border-neutral-300 bg-white py-2 px-2.5 text-sm text-neutral-700 focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/5 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-300">
                        <option value="all">Todos los roles</option>
                        <option v-for="role in availableRoles" :key="role" :value="role">{{ role }}</option>
                    </select>
                    <select v-model="statusFilter"
                        class="rounded-md border border-neutral-300 bg-white py-2 px-2.5 text-sm text-neutral-700 focus:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-900/5 dark:border-neutral-700 dark:bg-neutral-950 dark:text-neutral-300">
                        <option value="all">Todos los estados</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="Baja">Baja laboral</option>
                    </select>
                    <button v-if="hasActiveFilters" type="button" @click="resetFilters"
                        class="text-xs font-medium text-neutral-500 underline-offset-2 hover:text-neutral-900 hover:underline dark:text-neutral-400 dark:hover:text-neutral-100">
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <p v-if="hasActiveFilters" class="px-4 pt-3 text-xs text-neutral-400">
                Mostrando {{ filteredUsers.length }} de {{ users.data.length }} registros visibles en esta página
            </p>

            <div class="overflow-x-auto">
                <table v-if="filteredUsers.length" class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
                    <thead
                        class="text-xs uppercase bg-neutral-50 dark:bg-neutral-800/50 text-neutral-700 dark:text-neutral-300 border-b border-sidebar-border/70">
                        <tr>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">#</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Empleado</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Documento (DNI)</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Rol</th>
                            <th scope="col" class="hidden px-6 py-3 font-semibold lg:table-cell">Fecha de Registro</th>
                            <th scope="col" class="px-4 py-3 font-semibold text-center">Estado</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                        <tr v-for="(user, index) in filteredUsers" :key="user.id" :class="[
                            'border-l-[3px] transition-colors',
                            getRowAccent(user),
                            user.deleted_at
                                ? 'bg-neutral-100/50 opacity-75 dark:bg-neutral-800/20'
                                : 'hover:bg-neutral-50/50 dark:hover:bg-neutral-800/30'
                        ]">
                            <td class="px-4 py-4 text-center font-mono text-xs text-neutral-400">
                                {{ (users.current_page - 1) * 20 + index + 1 }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span :class="['flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold', getAvatarClass(user)]">
                                        {{ getInitials(user) }}
                                    </span>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-neutral-900 dark:text-neutral-100">{{ user.name }}</span>
                                        <span class="text-xs text-neutral-400">{{ user.email }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap font-mono tabular-nums text-neutral-700 dark:text-neutral-300">
                                {{ user.dni }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span v-for="role in user.roles" :key="role.id"
                                        :class="['inline-flex items-center gap-1.5 text-xs font-medium', getRoleStyle(role.name).text]">
                                        <span :class="['h-1.5 w-1.5 rounded-full', getRoleStyle(role.name).dot]" />
                                        {{ role.name }}
                                    </span>
                                </div>
                            </td>

                            <td class="hidden whitespace-nowrap px-6 py-4 text-xs text-neutral-600 lg:table-cell dark:text-neutral-400">
                                {{ formatDate(user.created_at) }}
                            </td>

                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span v-if="user.deleted_at"
                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400">
                                    Baja Laboral
                                </span>
                                <span v-else
                                    :class="['inline-flex items-center gap-1.5 text-xs font-medium', user.status === 'Activo' ? 'text-green-700 dark:text-green-400' : 'text-neutral-500 dark:text-neutral-400']">
                                    <span :class="['h-1.5 w-1.5 rounded-full', user.status === 'Activo' ? 'bg-green-500' : 'bg-neutral-400']" />
                                    {{ user.status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <Link :href="`/users/${user.id}`" title="Ver detalle" aria-label="Ver detalle"
                                        class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-neutral-100 hover:text-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-neutral-100">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </Link>

                                    <template v-if="user.deleted_at">
                                        <button @click="restoreUser(user.id)" type="button" title="Dar de alta" aria-label="Dar de alta"
                                            class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-green-50 hover:text-green-700 dark:text-neutral-400 dark:hover:bg-green-500/10 dark:hover:text-green-400">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16.023 9.348h4.992v-.001M2.985 14.652H7.98m9.252-4.652a8.25 8.25 0 1 0-2.046 8.466" />
                                                <path d="M16.023 9.348L20.99 4.5M2.985 14.652L2.5 19.5" stroke-opacity="0" />
                                                <path d="M21 4.5v4.848h-4.848" />
                                                <path d="M3 19.5v-4.848h4.848" />
                                            </svg>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <Link :href="`/users/${user.id}/edit`" title="Editar" aria-label="Editar"
                                            class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-blue-50 hover:text-blue-700 dark:text-neutral-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931z" />
                                                <path d="M19.5 6.75 19.5 19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V8.25A2.25 2.25 0 0 1 6.75 6H10.5" />
                                            </svg>
                                        </Link>
                                        <button @click="deleteUser(user.id)" type="button" title="Dar de baja" aria-label="Dar de baja"
                                            class="rounded-md p-1.5 text-neutral-500 transition-colors hover:bg-red-50 hover:text-red-700 dark:text-neutral-400 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="9" cy="7" r="3.25" />
                                                <path d="M3 19.5c0-3.59 2.91-6.5 6.5-6.5h0c1.45 0 2.79.475 3.879 1.277" />
                                                <path d="M16 11h5" />
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-else class="flex flex-col items-center justify-center gap-2 px-6 py-16 text-center">
                    <svg class="h-10 w-10 text-neutral-300 dark:text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                        {{ users.data.length === 0 ? 'Aún no hay personal registrado' : 'No se encontraron resultados' }}
                    </p>
                    <p class="max-w-xs text-xs text-neutral-500 dark:text-neutral-400">
                        {{ users.data.length === 0 ? 'Comienza registrando al primer trabajador del plantel.' : 'Intenta ajustar los filtros o el término de búsqueda.' }}
                    </p>
                    <button v-if="hasActiveFilters" type="button" @click="resetFilters"
                        class="mt-1 text-xs font-medium text-neutral-900 underline-offset-2 hover:underline dark:text-neutral-100">
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <div v-if="users.last_page > 1"
                class="flex items-center justify-between border-t border-neutral-200 px-4 py-3 sm:px-6 dark:border-neutral-800">
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link :href="users.links[0].url || '#'"
                        :class="['rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300', !users.links[0].url && 'pointer-events-none opacity-40']">
                        Anterior
                    </Link>
                    <Link :href="users.links[users.links.length - 1].url || '#'"
                        :class="['ml-3 rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-300', !users.links[users.links.length - 1].url && 'pointer-events-none opacity-40']">
                        Siguiente
                    </Link>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <p class="text-sm text-neutral-700 dark:text-neutral-400">
                        Página <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{ users.current_page }}</span>
                        de <span class="font-semibold text-neutral-900 dark:text-neutral-100">{{ users.last_page }}</span>
                        <span class="ml-1 text-xs text-neutral-500">({{ users.total }} empleados en total)</span>
                    </p>
                    <nav class="flex flex-wrap items-center gap-1" aria-label="Paginación">
                        <Link v-for="(link, key) in users.links" :key="key" :href="link.url || '#'" :class="[
                            'inline-flex min-w-[2.25rem] items-center justify-center rounded-md px-2.5 py-1.5 text-sm font-medium transition-colors',
                            link.active
                                ? 'bg-neutral-900 text-white dark:bg-neutral-50 dark:text-neutral-900'
                                : 'text-neutral-600 hover:bg-neutral-100 dark:text-neutral-400 dark:hover:bg-neutral-800',
                            !link.url && 'pointer-events-none opacity-40'
                        ]" v-html="link.label" />
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>