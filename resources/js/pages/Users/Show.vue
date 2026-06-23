<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';

interface Role { id: number; name: string; }
interface Boleta {
    id: number;
    type: string;
    mes: string;
    anio: number;
    status: 'Procesada' | 'Vista' | 'Firmada' | 'Observada';
    signed_at: string | null;
}

interface User {
    id: number;
    name: string;
    dni: string;
    phone: string | null;
    email: string;
    status: 'Activo' | 'Inactivo';
    created_at: string;
    roles: Role[];
    boletas: Boleta[]; // 🚀 Añadido el tipado de boletas
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

// 🎯 CONTROL REACTIVO DE PESTAÑAS
const activeTab = ref<'profile' | 'boletas'>('profile');

const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
};
</script>

<template>
    <Head :title="`Perfil - ${user.name}`" />

    <div class="flex h-full flex-1 flex-col gap-6 p-6 max-w-3xl mx-auto w-full antialiased text-neutral-900 dark:text-neutral-100">
        
        <div class="flex items-center justify-between">
            <Link href="/users" class="inline-flex items-center gap-1.5 text-xs font-semibold text-neutral-500 hover:text-neutral-900 dark:text-neutral-400 dark:hover:text-neutral-200 transition-colors uppercase tracking-wider">
                ← Regresar al Listado
            </Link>
        </div>

        <div class="rounded-2xl border border-neutral-200/80 bg-white p-6 dark:border-neutral-800 dark:bg-neutral-900 shadow-xs flex flex-col sm:flex-row items-center gap-5">
            <div class="size-16 rounded-full bg-neutral-900 text-neutral-50 dark:bg-neutral-100 dark:text-neutral-900 flex items-center justify-center font-black text-xl tracking-tight shrink-0 select-none shadow-xs">
                {{ getInitials(user.name) }}
            </div>
            <div class="text-center sm:text-left space-y-1">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <h1 class="text-xl font-black tracking-tight text-neutral-950 dark:text-white">{{ user.name }}</h1>
                    <div>
                        <span :class="['inline-flex items-center rounded-md px-2 py-0.5 text-[10px] uppercase font-bold tracking-wider border', 
                            user.status === 'Activo' 
                                ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/30 dark:text-green-400 dark:border-green-900/50' 
                                : 'bg-neutral-50 text-neutral-500 border-neutral-200 dark:bg-neutral-950/30 dark:text-neutral-400 dark:border-neutral-800'
                        ]">
                            {{ user.status }}
                        </span>
                    </div>
                </div>
                <p class="text-xs text-neutral-400 font-medium">Ficha de control operativo del Colegio Andino.</p>
            </div>
        </div>

        <div class="flex border-b border-neutral-200 dark:border-neutral-800 gap-2 select-none">
            <button 
                @click="activeTab = 'profile'"
                :class="['px-4 py-2 text-xs uppercase tracking-wider font-bold border-b-2 transition-all', 
                    activeTab === 'profile' 
                        ? 'border-neutral-900 text-neutral-900 dark:border-white dark:text-white' 
                        : 'border-transparent text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300'
                ]"
            >
                Información del Trabajador
            </button>
            <button 
                @click="activeTab = 'boletas'"
                :class="['px-4 py-2 text-xs uppercase tracking-wider font-bold border-b-2 transition-all flex items-center gap-2', 
                    activeTab === 'boletas' 
                        ? 'border-neutral-900 text-neutral-900 dark:border-white dark:text-white' 
                        : 'border-transparent text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300'
                ]"
            >
                Historial de Boletas 
                <span class="bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 px-1.5 py-0.5 rounded-md text-[10px] font-mono">
                    {{ user.boletas?.length ?? 0 }}
                </span>
            </button>
        </div>

        <div v-if="activeTab === 'profile'" class="space-y-6">
            <div class="rounded-2xl border border-neutral-200/80 bg-white overflow-hidden dark:border-neutral-800 dark:bg-neutral-900 shadow-2xs divide-y divide-neutral-100 dark:divide-neutral-800">
                <div class="p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">Identificación Legal</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-1 items-baseline"><span class="text-xs font-semibold text-neutral-400 sm:text-neutral-500 uppercase sm:normal-case">Nombre Completo</span><span class="text-sm font-bold text-neutral-900 dark:text-neutral-100 sm:col-span-2">{{ user.name }}</span></div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-1 items-baseline"><span class="text-xs font-semibold text-neutral-400 sm:text-neutral-500 uppercase sm:normal-case">DNI (RENIEC)</span><span class="text-sm font-mono font-bold tracking-wider text-neutral-900 dark:text-neutral-100 sm:col-span-2">{{ user.dni }}</span></div>
                </div>
                <div class="p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">Canales de Comunicación</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-1 items-baseline"><span class="text-xs font-semibold text-neutral-400 sm:text-neutral-500 uppercase sm:normal-case">Celular Personal</span><span class="text-sm font-mono text-neutral-900 dark:text-neutral-100 sm:col-span-2">{{ user.phone || 'No registrado' }}</span></div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-1 items-baseline"><span class="text-xs font-semibold text-neutral-400 sm:text-neutral-500 uppercase sm:normal-case">Correo Electrónico</span><span class="text-sm font-medium text-blue-600 dark:text-blue-400 sm:col-span-2 truncate">{{ user.email }}</span></div>
                </div>
                <div class="p-6 space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-neutral-400">Credenciales y Seguridad</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4 py-1 items-center"><span class="text-xs font-semibold text-neutral-400 sm:text-neutral-500 uppercase sm:normal-case">Roles Asignados</span><div class="sm:col-span-2 flex flex-wrap gap-1.5"><span v-for="role in user.roles" :key="role.id" class="inline-flex items-center rounded-lg bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700/60 px-2.5 py-1 text-xs font-bold text-neutral-800 dark:text-neutral-200 tracking-tight">{{ role.name }}</span></div></div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="`/users/${user.id}/edit`" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white dark:bg-neutral-50 dark:text-neutral-900 dark:hover:bg-neutral-200 px-5 py-2.5 text-sm font-bold shadow-sm transition-colors">
                    Modificar Información de Personal
                </Link>
            </div>
        </div>

        <div v-else class="space-y-4">
            <div v-if="!user.boletas || user.boletas.length === 0" class="rounded-2xl border border-dashed border-neutral-200 dark:border-neutral-800 p-12 text-center">
                <p class="text-sm italic text-neutral-400">Este trabajador no cuenta con boletas de pago registradas en la intranet.</p>
            </div>

            <div v-else class="rounded-2xl border border-neutral-200/80 bg-white overflow-hidden dark:border-neutral-800 dark:bg-neutral-900 shadow-2xs">
                <table class="w-full border-collapse text-left text-xs">
                    <thead>
                        <tr class="bg-neutral-50 dark:bg-neutral-950 text-neutral-400 font-bold border-b border-neutral-100 dark:border-neutral-800 uppercase tracking-wider select-none">
                            <th class="p-4">Periodo</th>
                            <th class="p-4">Tipo Documento</th>
                            <th class="p-4">Estado Interno</th>
                            <th class="p-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800 font-medium">
                        <tr v-for="boleta in user.boletas" :key="boleta.id" class="hover:bg-neutral-50/50 dark:hover:bg-neutral-950/40 transition-colors">
                            <td class="p-4 font-bold font-mono">
                                {{ boleta.mes }} / {{ boleta.anio }}
                            </td>
                            <td class="p-4 text-neutral-500 dark:text-neutral-400">
                                {{ boleta.type }}
                            </td>
                            <td class="p-4">
                                <span :class="['px-2 py-0.5 text-[10px] font-bold rounded-md border uppercase',
                                    boleta.status === 'Firmada' ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-950/20 dark:text-green-400 dark:border-green-900/40' :
                                    boleta.status === 'Observada' ? 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/20 dark:text-red-400 dark:border-red-900/40' :
                                    'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/40'
                                ]">
                                    {{ boleta.status }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <Link :href="`/boletas/${boleta.id}`" class="inline-flex items-center justify-center rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-3 py-1.5 text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors shadow-2xs">
                                    Abrir Visor
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>